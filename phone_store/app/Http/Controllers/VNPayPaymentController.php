<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\SendPaymentEmail;

class VNPayPaymentController extends Controller
{
    use SendPaymentEmail;
    /**
     * Các hằng số trạng thái thanh toán
     */
    const PAYMENT_SUCCESS = 'paid';        // Thanh toán thành công
    const PAYMENT_FAILED = 'payment_failed'; // Thanh toán thất bại
    const PAYMENT_PENDING = 'pending';     // Đang chờ thanh toán
    
    /**
     * Các hằng số trạng thái đơn hàng
     */
    const ORDER_PENDING = 'pending';       // Đơn hàng đang chờ xử lý
    const ORDER_PROCESSING = 'processing'; // Đơn hàng đang được xử lý
    const ORDER_COMPLETED = 'completed';   // Đơn hàng đã hoàn thành
    const ORDER_CANCELLED = 'cancelled';   // Đơn hàng đã bị hủy

    /**
     * Cấu hình VNPay
     */
    private $vnp_TmnCode;     // Mã website của merchant trên hệ thống của VNPAY
    private $vnp_HashSecret;  // Chuỗi bí mật dùng để tạo checksum
    private $vnp_Url;         // URL của VNPay

    /**
     * Constructor - Khởi tạo các thông số cấu hình VNPay
     */
    public function __construct()
    {
        $this->vnp_TmnCode = env('VNPAY_TMN_CODE');
        $this->vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $this->vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    }

    /**
     * Xử lý thanh toán VNPay
     * 
     * @param array $orderData Thông tin đơn hàng
     * @return \Illuminate\Http\JsonResponse
     */
    public function process($orderData)
    {
        try {
            $this->logPaymentConfiguration();
            
            $inputData = $this->prepareInputData($orderData);
            $cartItems = $this->getCartItems();
            
            $this->storePendingOrder($orderData, $cartItems);
            
            $paymentUrl = $this->generatePaymentUrl($inputData);
            
            return response()->json([
                'success' => true,
                'payment_url' => $paymentUrl
            ]);
        } catch (\Exception $e) {
            $this->logPaymentError($e, $orderData);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý thanh toán VNPay: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Xử lý callback từ VNPay sau khi thanh toán
     * 
     * @param Request $request Request từ VNPay
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        try {
            $this->logCallbackData($request);
            
            $pendingOrder = $this->getPendingOrder();
            if (!$pendingOrder) {
                return $this->handleMissingOrder();
            }

            $orderData = $pendingOrder['order_data'];
            $cartItems = $this->getValidCartItems($pendingOrder);
            
            if ($this->isPaymentSuccessful($request)) {
                return $this->handleSuccessfulPayment($request, $orderData, $cartItems);
            } 
            
            if ($this->isPaymentCancelled($request)) {
                return $this->handleCancelledPayment($orderData, $cartItems);
            }
            
            return $this->handleFailedPayment($request, $orderData, $cartItems);
            
        } catch (\Exception $e) {
            $this->logCallbackError($e);
            return redirect()->route('payment.failed')
                ->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán');
        }
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ VNPay
     * 
     * @param Request $request Request từ VNPay
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleIPN(Request $request)
    {
        try {
            if (!$this->validateIPN($request)) {
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            $order = $this->findOrder($request->vnp_TxnRef);
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            if ($request->vnp_ResponseCode == '00') {
                $this->processSuccessfulIPN($request, $order);
            } else {
                $this->processFailedIPN($request, $order);
            }

            return response()->json(['message' => 'Processed']);
        } catch (\Exception $e) {
            Log::error('VNPay IPN error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing IPN'], 500);
        }
    }

    /**
     * Ghi log cấu hình thanh toán
     */
    private function logPaymentConfiguration()
    {
        Log::info('VNPay payment configuration:', [
            'vnp_TmnCode' => $this->vnp_TmnCode,
            'vnp_HashSecret' => substr($this->vnp_HashSecret, 0, 4) . '****'
        ]);
    }

    /**
     * Chuẩn bị dữ liệu đầu vào cho VNPay
     * 
     * @param array $orderData Thông tin đơn hàng
     * @return array
     */
    private function prepareInputData($orderData)
    {
        return [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $orderData['total_amount'] * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toán đơn hàng #" . $orderData['order_code'],
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => route('payment.callback', ['method' => 'vnpay']),
            "vnp_TxnRef" => $orderData['order_code'],
            "vnp_BankCode" => "NCB"
        ];
    }

    /**
     * Lấy thông tin giỏ hàng
     * 
     * @return array
     */
    private function getCartItems()
    {
        $cartItems = session()->get('cart_items', []);
        if (empty($cartItems)) {
            $cart = Auth::user()->cart;
            if ($cart) {
                $cartItems = $cart->cartItems->map(function($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'product_name' => $item->product->name,
                        'product_image' => $item->product->image,
                        'product_color' => $item->product->color,
                        'product_ram' => $item->product->ram,
                        'product_storage' => $item->product->storage
                    ];
                })->toArray();
            }
        }
        return $cartItems;
    }

    /**
     * Lưu thông tin đơn hàng đang chờ vào session
     * 
     * @param array $orderData Thông tin đơn hàng
     * @param array $cartItems Thông tin giỏ hàng
     */
    private function storePendingOrder($orderData, $cartItems)
    {
        session(['pending_order' => [
            'order_data' => $orderData,
            'cart_items' => $cartItems
        ]]);
    }

    /**
     * Tạo URL thanh toán VNPay
     * 
     * @param array $inputData Dữ liệu đầu vào
     * @return string
     */
    private function generatePaymentUrl($inputData)
    {
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $this->vnp_Url . "?" . $query;
        if (isset($this->vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        
        return $vnp_Url;
    }

    /**
     * Ghi log lỗi thanh toán
     * 
     * @param \Exception $e Exception
     * @param array $orderData Thông tin đơn hàng
     */
    private function logPaymentError($e, $orderData)
    {
        Log::error('VNPay payment error: ' . $e->getMessage(), [
            'exception' => $e,
            'order_data' => $orderData
        ]);
    }

    /**
     * Ghi log dữ liệu callback
     * 
     * @param Request $request Request từ VNPay
     */
    private function logCallbackData($request)
    {
        Log::info('VNPay callback received all data:', [
            'request_data' => $request->all()
        ]);
    }

    /**
     * Lấy thông tin đơn hàng đang chờ từ session
     * 
     * @return array|null
     */
    private function getPendingOrder()
    {
        return session('pending_order');
    }

    /**
     * Xử lý trường hợp không tìm thấy đơn hàng
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleMissingOrder()
    {
        Log::error('VNPay callback: No pending order found in session');
        return redirect()->route('payment.failed')
            ->with('error', 'Không tìm thấy thông tin đơn hàng');
    }

    /**
     * Lấy thông tin giỏ hàng hợp lệ
     * 
     * @param array $pendingOrder Thông tin đơn hàng đang chờ
     * @return array
     * @throws \Exception
     */
    private function getValidCartItems($pendingOrder)
    {
        $cartItems = $pendingOrder['cart_items'] ?? [];
        if (empty($cartItems)) {
            Log::error('VNPay callback: No cart items found in session');
            throw new \Exception('Không tìm thấy thông tin sản phẩm trong giỏ hàng');
        }
        return $cartItems;
    }

    /**
     * Kiểm tra thanh toán thành công
     * 
     * @param Request $request Request từ VNPay
     * @return bool
     */
    private function isPaymentSuccessful($request)
    {
        return $request->has('vnp_ResponseCode') && $request->vnp_ResponseCode == '00';
    }

    /**
     * Kiểm tra thanh toán bị hủy
     * 
     * @param Request $request Request từ VNPay
     * @return bool
     */
    private function isPaymentCancelled($request)
    {
        return !$request->has('vnp_ResponseCode');
    }

    /**
     * Xử lý thanh toán thành công
     * 
     * @param Request $request Request từ VNPay
     * @param array $orderData Thông tin đơn hàng
     * @param array $cartItems Thông tin giỏ hàng
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleSuccessfulPayment($request, $orderData, $cartItems)
    {
        Log::info('VNPay payment successful', [
            'order_code' => $orderData['order_code'],
            'transaction_id' => $request->vnp_TransactionNo
        ]);

        return DB::transaction(function() use ($request, $orderData, $cartItems) {
            $order = $this->createOrder($orderData, self::ORDER_PENDING, self::PAYMENT_SUCCESS);
            $this->createOrderItems($order, $cartItems);
            $this->updateOrderTransaction($order, $request->vnp_TransactionNo);
            $this->clearCart();
            $this->clearSession();
            $emailSent = $this->sendPaymentEmail($order);
            return redirect()->route('payment.success')
                ->with('success', 'Thanh toán thành công');
        });
    }

    /**
     * Xử lý thanh toán bị hủy
     * 
     * @param array $orderData Thông tin đơn hàng
     * @param array $cartItems Thông tin giỏ hàng
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleCancelledPayment($orderData, $cartItems)
    {
        Log::info('VNPay payment cancelled', [
            'order_code' => $orderData['order_code']
        ]);

        return DB::transaction(function() use ($orderData, $cartItems) {
            $order = $this->createOrder($orderData, self::ORDER_CANCELLED, self::PAYMENT_FAILED);
            $this->createOrderItems($order, $cartItems);
            $this->clearSession();
            
            return redirect()->route('payment.failed')
                ->with('error', 'Bạn đã hủy giao dịch thanh toán');
        });
    }

    /**
     * Xử lý thanh toán thất bại
     * 
     * @param Request $request Request từ VNPay
     * @param array $orderData Thông tin đơn hàng
     * @param array $cartItems Thông tin giỏ hàng
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleFailedPayment($request, $orderData, $cartItems)
    {
        Log::error('VNPay payment failed', [
            'order_code' => $orderData['order_code'],
            'response_code' => $request->vnp_ResponseCode
        ]);

        return DB::transaction(function() use ($request, $orderData, $cartItems) {
            $order = $this->createOrder($orderData, self::ORDER_CANCELLED, self::PAYMENT_FAILED);
            $this->createOrderItems($order, $cartItems);
            $this->clearSession();
            
            return redirect()->route('payment.failed')
                ->with('error', 'Thanh toán thất bại. Mã lỗi: ' . $request->vnp_ResponseCode);
        });
    }

    /**
     * Tạo đơn hàng mới
     * 
     * @param array $orderData Thông tin đơn hàng
     * @param string $status Trạng thái đơn hàng
     * @param string $paymentStatus Trạng thái thanh toán
     * @return Order
     */
    private function createOrder($orderData, $status, $paymentStatus = self::PAYMENT_SUCCESS)
    {
        $orderData['status'] = $status;
        $orderData['payment_status'] = $paymentStatus;
        return Order::create($orderData);
    }

    /**
     * Tạo các sản phẩm trong đơn hàng
     * 
     * @param Order $order Đơn hàng
     * @param array $cartItems Thông tin giỏ hàng
     */
    private function createOrderItems($order, $cartItems)
    {
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
    }

    /**
     * Cập nhật mã giao dịch cho đơn hàng
     * 
     * @param Order $order Đơn hàng
     * @param string $transactionId Mã giao dịch
     */
    private function updateOrderTransaction($order, $transactionId)
    {
        if ($transactionId) {
            $order->update(['transaction_id' => $transactionId]);
        }
    }

    /**
     * Xóa giỏ hàng
     */
    private function clearCart()
    {
        $cart = Auth::user()->cart;
        if ($cart) {
            $cart->cartItems()->delete();
            $cart->delete();
        }
    }

    /**
     * Xóa dữ liệu session
     */
    private function clearSession()
    {
        session()->forget('pending_order');
    }

    /**
     * Ghi log lỗi callback
     * 
     * @param \Exception $e Exception
     */
    private function logCallbackError($e)
    {
        Log::error('VNPay callback error: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);
    }

    /**
     * Xác thực IPN từ VNPay
     * 
     * @param Request $request Request từ VNPay
     * @return bool
     */
    private function validateIPN($request)
    {
        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        if (!isset($inputData['vnp_SecureHash'])) {
            Log::error('VNPay IPN: Missing vnp_SecureHash');
            return false;
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= $key . "=" . $value . "&";
        }
        
        $hashData = rtrim($hashData, "&");
        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);
        
        return $secureHash == $vnp_SecureHash;
    }

    /**
     * Tìm đơn hàng theo mã đơn hàng
     * 
     * @param string $orderCode Mã đơn hàng
     * @return Order|null
     */
    private function findOrder($orderCode)
    {
        $order = Order::where('order_code', $orderCode)->first();
        if (!$order) {
            Log::error('VNPay IPN: Order not found', ['order_code' => $orderCode]);
        }
        return $order;
    }

    /**
     * Xử lý IPN thành công
     * 
     * @param Request $request Request từ VNPay
     * @param Order $order Đơn hàng
     */
    private function processSuccessfulIPN($request, $order)
    {
        DB::transaction(function() use ($request, $order) {
            $order->update([
                'status' => self::ORDER_PENDING,
                'payment_status' => self::PAYMENT_SUCCESS,
                'transaction_id' => $request->vnp_TransactionNo
            ]);
            
            if ($order->user && $order->user->cart) {
                $order->user->cart->cartItems()->delete();
                $order->user->cart->delete();
            }
        });

        Log::info('VNPay payment completed successfully', [
            'order_code' => $order->order_code,
            'transaction_id' => $request->vnp_TransactionNo
        ]);
    }

    /**
     * Xử lý IPN thất bại
     * 
     * @param Request $request Request từ VNPay
     * @param Order $order Đơn hàng
     */
    private function processFailedIPN($request, $order)
    {
        $order->update([
            'status' => self::ORDER_CANCELLED,
            'payment_status' => self::PAYMENT_FAILED
        ]);

        Log::error('VNPay payment failed', [
            'order_code' => $order->order_code,
            'response_code' => $request->vnp_ResponseCode
        ]);
    }
} 