<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'total_amount',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_district',
        'shipping_ward',
        'note',
        'payment_method',
        'status',
        'payment_status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Các trạng thái đơn hàng
     */
    const STATUS_PENDING = 'pending';                     // Chờ xử lý
    const STATUS_PENDING_CONFIRMATION = 'pending_confirmation'; // Chờ xác nhận (COD)
    const STATUS_PROCESSING = 'processing';               // Đang xử lý
    const STATUS_SHIPPING = 'shipping';                  // Đang giao hàng
    const STATUS_COMPLETED = 'completed';                // Hoàn thành
    const STATUS_CANCELLED = 'cancelled';                // Đã hủy

    /**
     * Các trạng thái thanh toán
     */
    const PAYMENT_PENDING = 'pending';       // Chờ thanh toán
    const PAYMENT_PAID = 'paid';            // Đã thanh toán
    const PAYMENT_FAILED = 'failed';        // Thanh toán thất bại
    const PAYMENT_REFUNDED = 'refunded';    // Đã hoàn tiền

    /**
     * Các phương thức thanh toán
     */
    const PAYMENT_METHOD_COD = 'cod';       // Thanh toán khi nhận hàng
    const PAYMENT_METHOD_MOMO = 'momo';     // Thanh toán qua Momo
    const PAYMENT_METHOD_VNPAY = 'vnpay';   // Thanh toán qua VNPay

    /**
     * Quan hệ với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với OrderItem
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Lấy tổng số lượng sản phẩm trong đơn hàng
     */
    public function getTotalQuantityAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    /**
     * Format số tiền thành chuỗi có định dạng tiền tệ
     */
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount) . 'đ';
    }

    /**
     * Kiểm tra xem đơn hàng có thể hủy không
     */
    public function canCancel()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PENDING_CONFIRMATION,
            self::STATUS_PROCESSING
        ]);
    }

    /**
     * Lấy class CSS cho badge trạng thái đơn hàng
     */
    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'warning';
            case self::STATUS_PENDING_CONFIRMATION:
                return 'info';
            case self::STATUS_PROCESSING:
                return 'primary';
            case self::STATUS_SHIPPING:
                return 'info';
            case self::STATUS_COMPLETED:
                return 'success';
            case self::STATUS_CANCELLED:
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Lấy text hiển thị trạng thái đơn hàng
     */
    public function getStatusText()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'Chờ xử lý';
            case self::STATUS_PENDING_CONFIRMATION:
                return 'Chờ xác nhận';
            case self::STATUS_PROCESSING:
                return 'Đang xử lý';
            case self::STATUS_SHIPPING:
                return 'Đang giao hàng';
            case self::STATUS_COMPLETED:
                return 'Hoàn thành';
            case self::STATUS_CANCELLED:
                return 'Đã hủy';
            default:
                return 'Không xác định';
        }
    }

    /**
     * Lấy class CSS cho badge trạng thái thanh toán
     */
    public function getPaymentStatusBadgeClass()
    {
        switch ($this->payment_status) {
            case self::PAYMENT_PENDING:
                return 'warning';
            case self::PAYMENT_PAID:
                return 'success';
            case self::PAYMENT_FAILED:
                return 'danger';
            case self::PAYMENT_REFUNDED:
                return 'info';
            default:
                return 'secondary';
        }
    }

    /**
     * Lấy text hiển thị trạng thái thanh toán
     */
    public function getPaymentStatusText()
    {
        switch ($this->payment_status) {
            case self::PAYMENT_PENDING:
                return 'Chờ thanh toán';
            case self::PAYMENT_PAID:
                return 'Đã thanh toán';
            case self::PAYMENT_FAILED:
                return 'Thanh toán thất bại';
            case self::PAYMENT_REFUNDED:
                return 'Đã hoàn tiền';
            default:
                return 'Không xác định';
        }
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => '#FFA500',    // Màu cam cho đơn hàng đang chờ
            'processing' => '#0088cc',  // Màu xanh dương cho đơn hàng đang xử lý
            'shipping' => '#9933CC',    // Màu tím cho đơn hàng đang giao
            'completed' => '#00A65A',   // Màu xanh lá cho đơn hàng hoàn thành
            'cancelled' => '#DD4B39',   // Màu đỏ cho đơn hàng đã hủy
            default => '#777777'        // Màu xám cho các trạng thái khác
        };
    }

    public function getPaymentStatusColor()
    {
        return match($this->payment_status) {
            'pending' => '#FFA500',    // Màu cam cho thanh toán đang chờ
            'paid' => '#00A65A',       // Màu xanh lá cho đã thanh toán
            'failed' => '#DD4B39',     // Màu đỏ cho thanh toán thất bại
            'refunded' => '#605CA8',   // Màu tím nhạt cho đã hoàn tiền
            default => '#777777'       // Màu xám cho các trạng thái khác
        };
    }
} 