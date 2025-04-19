<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->paginate(10);
            
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,pending_confirmation,processing,shipping,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order->status = $request->input('status');
        $order->payment_status = $request->input('payment_status');
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');
    }
} 