<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Notifications\PaymentSuccessNotification;
use Illuminate\Support\Facades\Mail;
use App\Traits\SendPaymentEmail;

class PaymentController extends Controller
{
    use SendPaymentEmail;
    // Các controller xử lý thanh toán
    protected $momoPayment;
    protected $vnpayPayment;

    /**
     * Khởi tạo controller với các phương thức thanh toán
     * @param MomoPaymentController $momoPayment - Controller xử lý thanh toán Momo
     * @param VNPayPaymentController $vnpayPayment - Controller xử lý thanh toán VNPay
     */
    public function __construct(
        MomoPaymentController $momoPayment,
        VNPayPaymentController $vnpayPayment
    ) {
        $this->momoPayment = $momoPayment;
        $this->vnpayPayment = $vnpayPayment;
    }

    /**
     * Hiển thị trang thanh toán
     * Kiểm tra giỏ hàng trước khi hiển thị
     * @return View|RedirectResponse - Trang thanh toán hoặc chuyển hướng nếu giỏ hàng trống
     */
    public function checkout()
    {
        $cart = Auth::user()->cart;
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống');
        }
        
        return view('payment.checkout', compact('cart'));
    }
    
    
    /**
     * Xử lý thanh toán COD
     * @param array $orderData - Dữ liệu đơn hàng
     * @param array $cartItems - Danh sách sản phẩm trong giỏ hàng
     * @param Cart $cart - Giỏ hàng cần xóa
     * @return JsonResponse - Kết quả xử lý thanh toán
     */
    private function processCOD($orderData, $cartItems, $cart)
    {
        DB::beginTransaction();
        try {
            // Tạo đơn hàng
            $order = Order::create($orderData);
            
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
            
            DB::commit();
            
            // Xóa giỏ hàng
            $cart->cartItems()->delete();
            $cart->delete();
            
            // Gửi email thông báo
            $emailSent = $this->sendPaymentEmail($order);
            
            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công',
                'email_sent' => $emailSent,
                'redirect_url' => route('payment.success')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau.'
            ], 500);
        }
    }
    
    /**
     * Xử lý thanh toán
     * @param Request $request - Dữ liệu từ form thanh toán
     * @return JsonResponse - Kết quả xử lý thanh toán
     */
    public function process(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'ward' => 'required|string|max:255',
                'payment_method' => 'required|in:cod,momo,vnpay',
                'note' => 'nullable|string'
            ]);

            $cart = Auth::user()->cart;
            
            // Kiểm tra giỏ hàng
            if (!$cart || $cart->cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng trống'
                ], 400);
            }

            // Chuẩn bị dữ liệu đơn hàng
            $orderData = [
                'user_id' => Auth::id(),
                'total_amount' => $cart->total_amount,
                'shipping_name' => $validated['name'],
                'shipping_phone' => $validated['phone'],
                'shipping_address' => $validated['address'],
                'shipping_city' => $validated['city'],
                'shipping_district' => $validated['district'],
                'shipping_ward' => $validated['ward'],
                'note' => $validated['note'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'order_code' => 'ORD-' . strtoupper(Str::random(10))
            ];

            // Lưu thông tin sản phẩm từ giỏ hàng
            $cartItems = $cart->cartItems->map(function($item) {
                $product = $item->product;
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'product_color' => $product->color,
                    'product_ram' => $product->ram,
                    'product_storage' => $product->storage
                ];
            })->toArray();
            
            // Lưu đơn hàng tạm thời vào session
            session(['pending_order' => [
                'order_data' => $orderData,
                'cart_items' => $cartItems
            ]]);
            
            // Xử lý thanh toán theo phương thức
            switch ($validated['payment_method']) {
                case 'momo':
                    $result = $this->momoPayment->process($orderData);
                    return $result;
                    
                case 'vnpay':
                    $result = $this->vnpayPayment->process($orderData);
                    return $result;
                    
                case 'cod':
                    return $this->processCOD($orderData, $cartItems, $cart);
                    
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Phương thức thanh toán không hợp lệ'
                    ], 400);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau.'
            ], 500);
        }
    }


    /**
     * Xử lý callback từ cổng thanh toán
     * @param Request $request - Dữ liệu callback
     * @param string $method - Phương thức thanh toán
     * @return mixed - Kết quả xử lý callback
     */
    public function callback(Request $request, $method)
    {
        try {
            switch ($method) {
                case 'momo':
                    return $this->momoPayment->callback($request);
                case 'vnpay':
                    return $this->vnpayPayment->callback($request);
                default:
                    throw new \Exception('Phương thức thanh toán không hợp lệ');
            }
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('payment.failed')
                ->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán');
        }
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ cổng thanh toán
     * @param Request $request - Dữ liệu IPN
     * @param string $method - Phương thức thanh toán
     * @return JsonResponse - Kết quả xử lý IPN
     */
    public function handleIPN(Request $request, $method)
    {
        try {
            switch ($method) {
                case 'momo':
                    return $this->momoPayment->handleIPN($request);
                case 'vnpay':
                    return $this->vnpayPayment->handleIPN($request);
                default:
                    throw new \Exception('Phương thức thanh toán không hợp lệ');
            }
        } catch (\Exception $e) {
            Log::error('Payment IPN error: ' . $e->getMessage());
            return response()->json([
                'message' => 'IPN processing error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị trang thanh toán thành công
     * @return View - Trang thông báo thành công
     */
    public function success()
    {
        return view('payment.success');
    }
    
    /**
     * Hiển thị trang thanh toán thất bại
     * @return View - Trang thông báo thất bại
     */
    public function failed()
    {
        return view('payment.failed');
    }
}
