<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng của người dùng
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with('orderItems')
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->findOrFail($id);

        if (!$order->canCancel()) {
            return redirect()->back()
                ->with('error', 'Không thể hủy đơn hàng này');
        }

        $order->update([
            'status' => Order::STATUS_CANCELLED
        ]);

        return redirect()->back()
            ->with('success', 'Đã hủy đơn hàng thành công');
    }
} 