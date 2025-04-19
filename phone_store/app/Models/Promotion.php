<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Các hằng số cho loại giảm giá
    const DISCOUNT_TYPE_PERCENTAGE = 'percentage';
    const DISCOUNT_TYPE_FIXED = 'fixed';

    /**
     * Quan hệ nhiều-nhiều với Product
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_promotion')
                    ->withTimestamps();
    }

    /**
     * Kiểm tra xem khuyến mãi có còn hiệu lực không
     */
    public function isValid()
    {
        $now = Carbon::now();
        return $this->is_active && $now->between($this->start_date, $this->end_date);
    }

    /**
     * Tính giá sau khuyến mãi cho sản phẩm
     */
    public function calculateDiscountPrice($originalPrice)
    {
        if (!$this->isValid()) {
            return $originalPrice;
        }

        if ($this->discount_type === self::DISCOUNT_TYPE_PERCENTAGE) {
            return $originalPrice * (1 - $this->discount_value / 100);
        }

        return max(0, $originalPrice - $this->discount_value);
    }

    /**
     * Lấy text hiển thị loại giảm giá
     */
    public function getDiscountTypeText()
    {
        return $this->discount_type === self::DISCOUNT_TYPE_PERCENTAGE
            ? 'Giảm ' . $this->discount_value . '%'
            : 'Giảm ' . number_format($this->discount_value) . 'đ';
    }

    /**
     * Lấy trạng thái hiệu lực
     */
    public function getStatusText()
    {
        if (!$this->is_active) {
            return 'Đã vô hiệu';
        }

        $now = Carbon::now();
        if ($now->lt($this->start_date)) {
            return 'Chưa bắt đầu';
        }
        if ($now->gt($this->end_date)) {
            return 'Đã kết thúc';
        }

        return 'Đang hoạt động';
    }

    /**
     * Lấy class CSS cho badge trạng thái
     */
    public function getStatusBadgeClass()
    {
        if (!$this->is_active) {
            return 'danger';
        }

        $now = Carbon::now();
        if ($now->lt($this->start_date)) {
            return 'warning';
        }
        if ($now->gt($this->end_date)) {
            return 'secondary';
        }

        return 'success';
    }
} 