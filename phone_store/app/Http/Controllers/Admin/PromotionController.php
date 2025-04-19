<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::where('status', 1)->get();
        return view('admin.promotions.form', compact('products'));
    }

    private function calculateDiscountPrice($product, $promotion)
    {
        if (!$promotion->is_active) {
            return null;
        }

        $now = now();
        if ($now < $promotion->start_date || $now > $promotion->end_date) {
            return null;
        }

        if ($promotion->discount_type == 'percentage') {
            return round($product->price * (1 - $promotion->discount_value/100));
        } else {
            return max(0, $product->price - $promotion->discount_value);
        }
    }

    private function updateProductDiscounts($products, $promotion = null)
    {
        foreach ($products as $product) {
            // Nếu có promotion mới và đang active
            if ($promotion && $promotion->is_active) {
                $discountPrice = $this->calculateDiscountPrice($product, $promotion);
                if ($discountPrice !== null) {
                    $product->update(['discount_price' => $discountPrice]);
                }
            } else {
                // Tìm khuyến mãi active khác cho sản phẩm
                $activePromotion = $product->promotions()
                    ->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->orderBy('discount_value', 'desc')
                    ->first();

                if ($activePromotion) {
                    $discountPrice = $this->calculateDiscountPrice($product, $activePromotion);
                    $product->update(['discount_price' => $discountPrice]);
                } else {
                    $product->update(['discount_price' => null]);
                }
            }
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id'
        ]);

        DB::beginTransaction();
        try {
            $promotion = Promotion::create([
                'name' => $request->name,
                'description' => $request->description,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'start_date' => Carbon::parse($request->start_date),
                'end_date' => Carbon::parse($request->end_date),
                'is_active' => true
            ]);

            $products = Product::whereIn('id', $request->products)->get();
            $promotion->products()->attach($request->products);
            
            // Cập nhật giá khuyến mãi cho sản phẩm
            $this->updateProductDiscounts($products, $promotion);
            
            DB::commit();
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Khuyến mãi đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi tạo khuyến mãi: ' . $e->getMessage());
        }
    }

    public function show(Promotion $promotion)
    {
        $promotion->load('products');
        
        $now = now()->timestamp;
        $status = 'inactive';
        $countdown = null;

        if ($promotion->is_active) {
            if ($now < $promotion->start_date->timestamp) {
                $status = 'upcoming';
                $diff = now()->diff($promotion->start_date);
                $countdown = [
                    'days' => $diff->days,
                    'hours' => $diff->h,
                    'minutes' => $diff->i,
                    'seconds' => $diff->s,
                    'total_seconds' => now()->diffInSeconds($promotion->start_date)
                ];
            } elseif ($now > $promotion->end_date->timestamp) {
                $status = 'expired';
            } else {
                $status = 'active';
                $diff = now()->diff($promotion->end_date);
                $countdown = [
                    'days' => $diff->days,
                    'hours' => $diff->h,
                    'minutes' => $diff->i,
                    'seconds' => $diff->s,
                    'total_seconds' => now()->diffInSeconds($promotion->end_date)
                ];
            }
        }

        return view('admin.promotions.show', compact('promotion', 'status', 'countdown'));
    }

    public function getPromotionStatus(Promotion $promotion)
    {
        $now = now()->timestamp;
        $status = 'inactive';
        $countdown = null;

        if ($promotion->is_active) {
            if ($now < $promotion->start_date->timestamp) {
                $status = 'upcoming';
                $diff = now()->diff($promotion->start_date);
                $countdown = [
                    'days' => $diff->days,
                    'hours' => $diff->h,
                    'minutes' => $diff->i,
                    'seconds' => $diff->s,
                    'total_seconds' => now()->diffInSeconds($promotion->start_date)
                ];
            } elseif ($now > $promotion->end_date->timestamp) {
                $status = 'expired';
            } else {
                $status = 'active';
                $diff = now()->diff($promotion->end_date);
                $countdown = [
                    'days' => $diff->days,
                    'hours' => $diff->h,
                    'minutes' => $diff->i,
                    'seconds' => $diff->s,
                    'total_seconds' => now()->diffInSeconds($promotion->end_date)
                ];
            }
        }

        return response()->json([
            'status' => $status,
            'countdown' => $countdown,
            'message' => $this->getStatusMessage($status, $countdown)
        ]);
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::where('status', 1)->get();
        $selectedProducts = $promotion->products->pluck('id')->toArray();
        return view('admin.promotions.edit', compact('promotion', 'products', 'selectedProducts'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id'
        ]);

        DB::beginTransaction();
        try {
            // Lấy danh sách sản phẩm cũ để cập nhật lại giá
            $oldProducts = $promotion->products()->get();

            $promotion->update([
                'name' => $request->name,
                'description' => $request->description,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'start_date' => Carbon::parse($request->start_date),
                'end_date' => Carbon::parse($request->end_date)
            ]);

            // Cập nhật lại giá cho sản phẩm cũ (tìm khuyến mãi khác nếu có)
            $this->updateProductDiscounts($oldProducts);

            // Cập nhật danh sách sản phẩm mới
            $promotion->products()->sync($request->products);
            
            // Cập nhật giá khuyến mãi cho sản phẩm mới
            $newProducts = Product::whereIn('id', $request->products)->get();
            $this->updateProductDiscounts($newProducts, $promotion);
            
            DB::commit();
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Khuyến mãi đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật khuyến mãi: ' . $e->getMessage());
        }
    }

    public function destroy(Promotion $promotion)
    {
        DB::beginTransaction();
        try {
            // Lấy danh sách sản phẩm trước khi xóa
            $products = $promotion->products()->get();
            
            // Xóa liên kết với sản phẩm
            $promotion->products()->detach();
            
            // Xóa khuyến mãi
            $promotion->delete();
            
            // Cập nhật lại giá cho các sản phẩm (tìm khuyến mãi khác nếu có)
            $this->updateProductDiscounts($products);
            
            DB::commit();
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Khuyến mãi đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi xóa khuyến mãi: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Promotion $promotion)
    {
        DB::beginTransaction();
        try {
            $oldStatus = $promotion->is_active;
            $promotion->is_active = !$oldStatus;
            $promotion->save();
            
            // Cập nhật lại giá khuyến mãi cho các sản phẩm
            $products = $promotion->products()->get();
            $this->updateProductDiscounts($products, $promotion->is_active ? $promotion : null);
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Trạng thái khuyến mãi đã được cập nhật.',
                'is_active' => $promotion->is_active
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }
} 