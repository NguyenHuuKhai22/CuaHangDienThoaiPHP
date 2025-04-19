<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CategoryAdminController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            
            // Đảm bảo thư mục tồn tại
            $path = public_path('images/categories');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }
            
            // Di chuyển file vào thư mục public/images/categories
            $image->move($path, $filename);
            $validated['image'] = $filename;
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được tạo thành công.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($category->image) {
                $oldImagePath = public_path('images/categories/' . $category->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            
            // Đảm bảo thư mục tồn tại
            $path = public_path('images/categories');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }
            
            // Di chuyển file vào thư mục public/images/categories
            $image->move($path, $filename);
            $validated['image'] = $filename;
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có sản phẩm không
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì có sản phẩm liên quan.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được xóa thành công.');
    }

    // Phương thức để hiển thị danh mục đã xóa
    public function trashed()
    {
        $categories = Category::onlyTrashed()->withCount('products')->latest()->paginate(10);
        return view('admin.categories.trashed', compact('categories'));
    }

    // Phương thức để khôi phục danh mục đã xóa
    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('admin.categories.trashed')
            ->with('success', 'Danh mục đã được khôi phục thành công.');
    }

    // Phương thức để xóa vĩnh viễn danh mục
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        
        // Xóa ảnh nếu có
        if ($category->image) {
            $imagePath = public_path('images/categories/' . $category->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $category->forceDelete();

        return redirect()->route('admin.categories.trashed')
            ->with('success', 'Danh mục đã được xóa vĩnh viễn.');
    }
} 