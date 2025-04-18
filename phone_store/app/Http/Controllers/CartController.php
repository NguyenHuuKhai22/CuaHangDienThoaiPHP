<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        return view('cart.index', compact('cart'));
    }
   
    public function add(Request $request, Product $product)
    {
        //validate
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['total_amount' => 0]
        );

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->discount_price ?? $product->price
            ]);
        }

        $cart->total_amount = $cart->cartItems->sum(function($item) {
            return $item->quantity * ($item->product->discount_price ?? $item->price);
        });
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng',
            'cartCount' => $cart->cartItems->sum('quantity')
        ]);
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item->quantity = $request->quantity;
        $item->save();

        $cart = $item->cart;
        $cart->total_amount = $cart->cartItems->sum(function($item) {
            return $item->quantity * ($item->product->discount_price ?? $item->price);
        });
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Số lượng sản phẩm đã được cập nhật',
            'total' => $cart->total_amount
        ]);
    }

    public function remove(CartItem $item)
    {
        $cart = $item->cart;
        $item->delete();

        $cart->total_amount = $cart->cartItems->sum(function($item) {
            return $item->quantity * ($item->product->discount_price ?? $item->price);
        });
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng',
            'total' => $cart->total_amount
        ]);
    }

    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cart->cartItems()->delete();
            $cart->total_amount = 0;
            $cart->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Giỏ hàng đã được làm trống'
        ]);
    }
} 