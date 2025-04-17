<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        // Lấy tất cả các sản phẩm có cùng tên
        $variants = Product::where('name', $product->name)
                         ->where('id', '!=', $product->id)
                         ->get();

        return view('products.show', compact('product', 'variants'));
    }

    public function getDetails(Product $product)
    {
        // Trả về thông tin sản phẩm dạng JSON cho AJAX request
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'image' => $product->image ? asset('images/products/' . $product->image) : asset('images/placeholder.jpg'),
            'price' => $product->price,
            'formatted_price' => $product->formatted_price,
            'discount_price' => $product->discount_price,
            'formatted_discount_price' => $product->formatted_discount_price,
            'ram' => $product->ram,
            'storage' => $product->storage,
            'color' => $product->color,
            'description' => $product->description,
        ]);
    }

    public function quickView(Product $product)
    {
        return view('products.quick-view', compact('product'));
    }
} 