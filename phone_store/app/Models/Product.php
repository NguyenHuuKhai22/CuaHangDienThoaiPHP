<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'price',
        'discount_price',
        'stock_quantity',
        'description',
        'image',
        'color',
        'storage',
        'ram',
        'screen_size',
        'battery_capacity',
        'operating_system',
        'is_featured',
        'status',
        'deleted_at'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' ₫';
    }

    public function getFormattedDiscountPriceAttribute()
    {
        return $this->discount_price ? number_format($this->discount_price, 0, ',', '.') . ' ₫' : null;
    }

    /**
     * Quan hệ với Promotion
     */
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'product_promotion')
                    ->withTimestamps();
    }

    /**
     * Lấy khuyến mãi đang áp dụng
     */
    public function getActivePromotion()
    {
        return $this->promotions()
                    ->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->orderBy('discount_value', 'desc')
                    ->first();
    }

    /**
     * Lấy giá sau khuyến mãi
     */
    public function getDiscountedPrice()
    {
        $promotion = $this->getActivePromotion();
        if (!$promotion) {
            return $this->price;
        }
        return $promotion->calculateDiscountPrice($this->price);
    }
} 