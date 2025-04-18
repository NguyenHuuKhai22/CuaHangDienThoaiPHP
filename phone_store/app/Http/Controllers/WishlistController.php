<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->first();
        return view('wishlist.index', compact('wishlist'));
    }

    public function add(Request $request, Product $product)
    {
        $wishlist = Wishlist::firstOrCreate(
            ['user_id' => Auth::id()]
        );

        $wishlistItem = WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$wishlistItem) {
            WishlistItem::create([
                'wishlist_id' => $wishlist->id,
                'product_id' => $product->id
            ]);
        }

        // Refresh wishlist count
        $wishlistCount = WishlistItem::where('wishlist_id', $wishlist->id)->count();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào danh sách yêu thích',
            'wishlistCount' => $wishlistCount
        ]);
    }

    public function remove(Product $product)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->first();
        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh sách yêu thích'
            ]);
        }

        $wishlistItem = WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
        }

        // Refresh wishlist count
        $wishlistCount = WishlistItem::where('wishlist_id', $wishlist->id)->count();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa khỏi danh sách yêu thích',
            'wishlistCount' => $wishlistCount
        ]);
    }

    public function checkStatus(Product $product)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->first();
        $isInWishlist = false;
        
        if ($wishlist) {
            $isInWishlist = $wishlist->wishlistItems()
                ->where('product_id', $product->id)
                ->exists();
        }
        
        return response()->json([
            'isInWishlist' => $isInWishlist
        ]);
    }
} 