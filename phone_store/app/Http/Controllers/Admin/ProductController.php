<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'color' => 'required|string|max:50',
            'storage' => 'required|string|max:50',
            'ram' => 'required|string|max:50',
            'screen_size' => 'required|string|max:50',
            'battery_capacity' => 'required|string|max:50',
            'operating_system' => 'required|string|max:50',
            'is_featured' => 'boolean',
            'status' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            
            // Đảm bảo thư mục tồn tại
            $path = public_path('images/products');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }
            
            // Di chuyển file vào thư mục public/images/products
            $image->move($path, $filename);
            $validated['image'] = $filename;
        }

        $validated['slug'] = Str::slug($request->name);
        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'color' => 'required|string|max:50',
            'storage' => 'required|string|max:50',
            'ram' => 'required|string|max:50',
            'screen_size' => 'required|string|max:50',
            'battery_capacity' => 'required|string|max:50',
            'operating_system' => 'required|string|max:50',
            'is_featured' => 'boolean',
            'status' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($product->image) {
                $oldImagePath = public_path('images/products/' . $product->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            
            // Đảm bảo thư mục tồn tại
            $path = public_path('images/products');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }
            
            // Di chuyển file vào thư mục public/images/products
            $image->move($path, $filename);
            $validated['image'] = $filename;
        }

        $validated['slug'] = Str::slug($request->name);
        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        
        // Xóa ảnh nếu tồn tại
        if ($product->image) {
            $imagePath = public_path('images/products/' . $product->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        
        $product->forceDelete();
        return redirect()->route('admin.products.trashed')
            ->with('success', 'Sản phẩm đã được xóa vĩnh viễn.');
    }

    public function trashed()
    {
        $trashedProducts = Product::onlyTrashed()->with('category')->latest()->paginate(10);
        return view('admin.products.trashed', compact('trashedProducts'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('admin.products.trashed')
            ->with('success', 'Sản phẩm đã được khôi phục thành công.');
    }
}