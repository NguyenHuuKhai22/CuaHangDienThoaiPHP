<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class PaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Đơn hàng #' . $this->order->order_code . ' đã thanh toán thành công')
            ->greeting('Xin chào ' . $notifiable->name . '!')
            ->line('Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi.')
            ->line('Đơn hàng của bạn đã được thanh toán thành công.')
            ->line('Mã đơn hàng: ' . $this->order->order_code)
            ->line('Tổng tiền: ' . number_format($this->order->total_amount) . ' VNĐ')
            ->line('Phương thức thanh toán: ' . $this->getPaymentMethodName())
            ->line('Địa chỉ giao hàng: ' . $this->order->shipping_address)
            ->action('Xem chi tiết đơn hàng', route('orders.show', $this->order->id))
            ->line('Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.')
            ->salutation('Trân trọng, ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_code' => $this->order->order_code,
            'total_amount' => $this->order->total_amount,
        ];
    }

    protected function getPaymentMethodName()
    {
        return match($this->order->payment_method) {
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'momo' => 'Ví điện tử MoMo',
            'vnpay' => 'VNPay',
            default => 'Không xác định'
        };
    }
} 