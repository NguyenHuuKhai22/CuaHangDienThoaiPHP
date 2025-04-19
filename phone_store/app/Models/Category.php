<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Tự động tạo slug từ name
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            $category->slug = str()->slug($category->name);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = str()->slug($category->name);
            }
        });
    }
} 