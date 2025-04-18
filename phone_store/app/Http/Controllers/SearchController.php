<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Controller xử lý chức năng tìm kiếm sản phẩm
 */
class SearchController extends Controller
{
    /**
     * Xử lý tìm kiếm sản phẩm dựa trên từ khóa người dùng nhập
     * 
     * @param Request $request Request chứa thông tin tìm kiếm
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ request
        $query = $request->input('q');
        
        // Nếu không có từ khóa, quay lại trang trước
        if (!$query) {
            return redirect()->back();
        }

        // Tìm kiếm sản phẩm theo:
        // - Tên sản phẩm
        // - Mô tả sản phẩm
        // - Tên danh mục
        // Kết quả được phân trang, mỗi trang 12 sản phẩm
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhereHas('category', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->paginate(12);

        // Trả về view hiển thị kết quả tìm kiếm
        return view('search.results', compact('products', 'query'));
    }
} 