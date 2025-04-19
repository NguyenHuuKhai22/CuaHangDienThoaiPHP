<?php

namespace App\Traits;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

trait SendPaymentEmail
{
    /**
     * Gửi email thông báo thanh toán thành công
     * 
     * @param Order $order Đơn hàng
     * @return bool
     */
    private function sendPaymentEmail(Order $order)
    {
        try {
            $user = $order->user;
            if (!$user) {
                Log::error('User not found for order: ' . $order->order_code);
                return false;
            }

            // Sử dụng Mail facade thay vì notification
            Mail::send('emails.payment_success', [
                'order' => $order,
                'user' => $user
            ], function($message) use ($user, $order) {
                $message->to($user->email)
                       ->subject('Đơn hàng #' . $order->order_code . ' đã thanh toán thành công');
            });

            Log::info('Email sent successfully for order: ' . $order->order_code);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send email notification for order ' . $order->order_code . ': ' . $e->getMessage());
            Log::error('Email error details: ' . $e->getTraceAsString());
            return false;
        }
    }
} 