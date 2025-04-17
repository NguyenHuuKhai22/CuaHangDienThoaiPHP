<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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
        'status'
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
} 