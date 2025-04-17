<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $products = $category->products()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }
    
} 