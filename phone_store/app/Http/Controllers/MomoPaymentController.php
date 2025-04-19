<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Traits\SendPaymentEmail;
class MomoPaymentController extends Controller
{
    use SendPaymentEmail;
    // Các hằng số trạng thái thanh toán
    const PAYMENT_SUCCESS = 'paid';
    const PAYMENT_FAILED = 'payment_failed';
    const PAYMENT_PENDING = 'pending';

    /**
     * Xử lý thanh toán qua Momo
     */
    public function process($orderData)
    {
        try {
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            
            $partnerCode = env('MOMO_PARTNER_CODE');
            $accessKey = env('MOMO_ACCESS_KEY');
            $secretKey = env('MOMO_SECRET_KEY');
            
            Log::info('Momo payment configuration:', [
                'partnerCode' => $partnerCode,
                'accessKey' => $accessKey,
                'secretKey' => substr($secretKey, 0, 4) . '****'
            ]);
            
            $orderInfo = "Thanh toán đơn hàng #" . $orderData['order_code'];
            $amount = (int)($orderData['total_amount']);
            $orderId = $orderData['order_code'];
            $redirectUrl = route('payment.callback', ['method' => 'momo']);
            $ipnUrl = route('payment.ipn', ['method' => 'momo']);
            $requestType = "captureWallet";
            $requestId = time() . "";
            $extraData = "";
            
            // Tạo chuỗi hash
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
            $signature = hash_hmac('sha256', $rawHash, $secretKey);
            
            $data = [
                'partnerCode' => $partnerCode,
                'partnerName' => "Test",
                'storeId' => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            ];
            
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);
            
            if (isset($jsonResult['payUrl'])) {
                return response()->json([
                    'success' => true,
                    'payment_url' => $jsonResult['payUrl']
                ]);
            } else {
                Log::error('Momo payment error: Invalid response format', [
                    'response' => $jsonResult
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể kết nối đến cổng thanh toán Momo. Chi tiết: ' . ($jsonResult['message'] ?? 'Không có thông tin lỗi')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Momo payment error: ' . $e->getMessage(), [
                'exception' => $e,
                'order_data' => $orderData
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý thanh toán Momo: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Xử lý callback từ Momo
     */
    public function callback(Request $request)
    {
        try {
            $pendingOrder = session('pending_order');
            
            if (!$pendingOrder) {
                Log::error('Momo callback: No pending order found in session');
                return redirect()->route('payment.failed')
                    ->with('error', 'Không tìm thấy thông tin đơn hàng');
            }

            $orderData = $pendingOrder['order_data'];
            $cartItems = $pendingOrder['cart_items'];

            Log::info('Momo callback received', [
                'resultCode' => $request->resultCode ?? 'No resultCode',
                'message' => $request->message ?? 'No message',
                'orderInfo' => $request->orderInfo ?? 'No orderInfo'
            ]);

            // Nếu không có resultCode hoặc là mã hủy giao dịch (1006)
            if (!$request->has('resultCode') || $request->resultCode == '1006') {
                return $this->handleCancelledPayment($orderData, $cartItems);
            }

            // Kiểm tra thanh toán thành công (resultCode = 0)
            if ($request->resultCode == '0') {
                return $this->handleSuccessfulPayment($request, $orderData, $cartItems);
            }

            // Thanh toán thất bại (các mã lỗi khác)
            return $this->handleFailedPayment($request, $orderData, $cartItems);
        } catch (\Exception $e) {
            Log::error('Momo callback error: ' . $e->getMessage());
            return redirect()->route('payment.failed')
                ->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán');
        }
    }
    
    /**
     * Xử lý thanh toán thành công
     */
    private function handleSuccessfulPayment($request, $orderData, $cartItems)
    {
        Log::info('Momo payment successful', [
            'order_code' => $orderData['order_code'],
            'transId' => $request->transId ?? 'No transId'
        ]);

        return DB::transaction(function() use ($request, $orderData, $cartItems) {
            // Tạo đơn hàng
            $order = Order::create([
                ...$orderData,
                'status' => 'pending',
                'payment_status' => 'paid',
                'transaction_id' => $request->transId
            ]);
            
            // Tạo chi tiết đơn hàng và cập nhật số lượng tồn kho
            foreach ($cartItems as $item) {
                // Tạo chi tiết đơn hàng
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'product_name' => $item['product_name'],
                    'product_image' => $item['product_image'],
                    'product_color' => $item['product_color'],
                    'product_ram' => $item['product_ram'],
                    'product_storage' => $item['product_storage']
                ]);

                // Cập nhật số lượng tồn kho
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }
            
            // Xóa giỏ hàng
            $cart = Auth::user()->cart;
            if ($cart) {
                $cart->cartItems()->delete();
                $cart->delete();
            }

                // Gửi email thông báo
                
                $emailSent = $this->sendPaymentEmail($order);
            // Xóa session
            session()->forget('pending_order');
            
            return redirect()->route('payment.success')
                ->with('success', 'Thanh toán thành công');
        });
    }

    /**
     * Xử lý thanh toán bị hủy
     */
    private function handleCancelledPayment($orderData, $cartItems)
    {
        Log::info('Momo payment cancelled', [
            'order_code' => $orderData['order_code']
        ]);

        return DB::transaction(function() use ($orderData, $cartItems) {
            // Tạo đơn hàng với trạng thái hủy
            $order = Order::create([
                ...$orderData,
                'status' => 'cancelled',
                'payment_status' => 'failed'
            ]);
            
            // Tạo chi tiết đơn hàng
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'product_name' => $item['product_name'],
                    'product_image' => $item['product_image'],
                    'product_color' => $item['product_color'],
                    'product_ram' => $item['product_ram'],
                    'product_storage' => $item['product_storage']
                ]);
            }
            
            // Xóa session
            session()->forget('pending_order');
            
            return redirect()->route('payment.failed')
                ->with('error', 'Bạn đã hủy giao dịch thanh toán');
        });
    }

    /**
     * Xử lý thanh toán thất bại
     */
    private function handleFailedPayment($request, $orderData, $cartItems)
    {
        Log::error('Momo payment failed', [
            'order_code' => $orderData['order_code'],
            'resultCode' => $request->resultCode,
            'message' => $request->message
        ]);

        return DB::transaction(function() use ($request, $orderData, $cartItems) {
            // Tạo đơn hàng với trạng thái thất bại
            $order = Order::create([
                ...$orderData,
                'status' => 'cancelled',
                'payment_status' => 'failed'
            ]);
            
            // Tạo chi tiết đơn hàng
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'product_name' => $item['product_name'],
                    'product_image' => $item['product_image'],
                    'product_color' => $item['product_color'],
                    'product_ram' => $item['product_ram'],
                    'product_storage' => $item['product_storage']
                ]);
            }
            
            // Xóa session
            session()->forget('pending_order');
            
            return redirect()->route('payment.failed')
                ->with('error', 'Thanh toán thất bại. ' . ($request->message ?? ''));
        });
    }

    /**
     * Xử lý IPN từ Momo
     */
    public function handleIPN(Request $request)
    {
        try {
            $secretKey = env('MOMO_SECRET_KEY');
            
            // Verify signature
            $rawHash = "accessKey=" . $request->accessKey .
                "&amount=" . $request->amount .
                "&extraData=" . $request->extraData .
                "&message=" . $request->message .
                "&orderId=" . $request->orderId .
                "&orderInfo=" . $request->orderInfo .
                "&orderType=" . $request->orderType .
                "&partnerCode=" . $request->partnerCode .
                "&payType=" . $request->payType .
                "&requestId=" . $request->requestId .
                "&responseTime=" . $request->responseTime .
                "&resultCode=" . $request->resultCode .
                "&transId=" . $request->transId;

            $signature = hash_hmac('sha256', $rawHash, $secretKey);

            if ($signature !== $request->signature) {
                Log::error('Momo IPN: Invalid signature', [
                    'received' => $request->signature,
                    'calculated' => $signature
                ]);
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            // Process the payment result
            $order = Order::where('order_code', $request->orderId)->first();
            if (!$order) {
                Log::error('Momo IPN: Order not found', ['order_code' => $request->orderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            if ($request->resultCode == 0) {
                DB::transaction(function() use ($request, $order) {
                    // Cập nhật trạng thái thanh toán thành paid
                    $order->update([
                        'status' => 'pending',
                        'payment_status' => 'paid',
                        'transaction_id' => $request->transId
                    ]);

                    // Xóa giỏ hàng nếu có
                    if ($order->user && $order->user->cart) {
                        $order->user->cart->cartItems()->delete();
                        $order->user->cart->delete();
                    }
                });

                Log::info('Momo payment completed successfully', [
                    'order_code' => $order->order_code,
                    'transaction_id' => $request->transId
                ]);
            } else {
                $order->update([
                    'status' => self::PAYMENT_FAILED,
                    'payment_status' => 'failed'
                ]);

                Log::error('Momo payment failed', [
                    'order_code' => $order->order_code,
                    'result_code' => $request->resultCode,
                    'message' => $request->message
                ]);
            }

            return response()->json(['message' => 'Processed']);
        } catch (\Exception $e) {
            Log::error('Momo IPN error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing IPN'], 500);
        }
    }

    /**
     * Hàm gửi request POST
     */
    private function execPostRequest($url, $data)
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
            $result = curl_exec($ch);
            $error = curl_error($ch);
            
            if ($error) {
                Log::error('CURL Error:', [
                    'error' => $error,
                    'curl_info' => curl_getinfo($ch)
                ]);
                throw new \Exception('CURL Error: ' . $error);
            }
            
            curl_close($ch);
            return $result;
        } catch (\Exception $e) {
            Log::error('execPostRequest error:', [
                'exception' => $e,
                'url' => $url
            ]);
            throw $e;
        }
    }
} 