<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    protected $appends = ['items_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    protected function itemsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->wishlistItems()->count(),
        );
    }
} 