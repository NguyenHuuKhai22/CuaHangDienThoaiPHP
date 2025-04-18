<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'product_name',
        'product_image',
        'product_color',
        'product_ram',
        'product_storage'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Quan hệ với Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ với Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Tính tổng tiền của item
     */
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Format số tiền thành chuỗi có định dạng tiền tệ
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price) . 'đ';
    }

    /**
     * Format tổng tiền thành chuỗi có định dạng tiền tệ
     */
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total) . 'đ';
    }
} 