<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Cập nhật discount_price khi có thay đổi
     */
    public function saved(Product $product)
    {
        $this->updateDiscountPrice($product);
    }

    /**
     * Cập nhật discount_price khi thêm promotion
     */
    public function updated(Product $product)
    {
        $this->updateDiscountPrice($product);
    }

    /**
     * Hàm cập nhật discount_price
     */
    private function updateDiscountPrice(Product $product)
    {
        $promotion = $product->getActivePromotion();
        
        if ($promotion) {
            $discountPrice = $promotion->calculateDiscountPrice($product->price);
            // Cập nhật trực tiếp vào database để tránh gọi lại observer
            Product::withoutEvents(function () use ($product, $discountPrice) {
                $product->update(['discount_price' => $discountPrice]);
            });
        } else {
            // Nếu không có khuyến mãi, set discount_price về null
            Product::withoutEvents(function () use ($product) {
                $product->update(['discount_price' => null]);
            });
        }
    }
} 