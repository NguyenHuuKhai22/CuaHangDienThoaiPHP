<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Lọc theo danh mục
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Lọc theo giá
        if ($request->has('price')) {
            switch ($request->price) {
                case 'under-10':
                    $query->where('price', '<', 10000000);
                    break;
                case '10-20':
                    $query->whereBetween('price', [10000000, 20000000]);
                    break;
                case '20-30':
                    $query->whereBetween('price', [20000000, 30000000]);
                    break;
                case 'over-30':
                    $query->where('price', '>', 30000000);
                    break;
            }
        }

        // Sắp xếp
        switch ($request->sort ?? 'latest') {
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name-asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        // Lấy danh sách sản phẩm với phân trang
        $products = $query->paginate(12)->withQueryString();
        
        // Lấy danh sách danh mục để hiển thị bộ lọc
        $categories = Category::all();

        return view('shop.index', compact('products', 'categories'));
    }
} 