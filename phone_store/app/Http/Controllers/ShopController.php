<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Query cơ bản với eager loading
        $query = Product::with(['category', 'promotions'])
                        ->where('status', 1);

        // Lấy danh sách khuyến mãi đang hoạt động
        $activePromotions = \App\Models\Promotion::where('is_active', true)
                            ->whereDate('start_date', '<=', now())
                            ->whereDate('end_date', '>=', now())
                            ->orderBy('end_date', 'asc')
                            ->get();

        // Lọc theo khuyến mãi nếu có
        if ($request->has('promotion_id')) {
            $promotionId = $request->promotion_id;
            $query->whereHas('promotions', function($q) use ($promotionId) {
                $q->where('promotions.id', $promotionId)
                  ->where('is_active', true)
                  ->whereDate('start_date', '<=', now())
                  ->whereDate('end_date', '>=', now());
            });

            // Debug log
            Log::info('Promotion filter', [
                'promotion_id' => $promotionId,
                'current_time' => now(),
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);
        }

        // Lọc theo danh mục
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
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
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
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

        // Nếu là request API
        if ($request->wantsJson()) {
            return response()->json([
                'products' => $products,
                'categories' => $categories
            ]);
        }

        // Nếu là request thông thường
        return view('shop.index', compact('products', 'categories', 'activePromotions'));
    }
} 