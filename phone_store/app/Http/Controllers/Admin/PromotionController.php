<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\PromotionEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Models\Notification;
use App\Models\User;

class PromotionController extends Controller
{
    /**
     * Hiển thị danh sách khuyến mãi
     * Phân trang 10 khuyến mãi mỗi trang
     * Sắp xếp theo thời gian tạo mới nhất
     */
    public function index()
    {
        $promotions = Promotion::latest()->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Hiển thị form tạo khuyến mãi mới
     * Lấy danh sách sản phẩm đang hoạt động
     */
    public function create()
    {
        $products = Product::where('status', 1)->get();
        return view('admin.promotions.form', compact('products'));
    }

    /**
     * Tính toán giá khuyến mãi cho sản phẩm
     * @param Product $product - Sản phẩm cần tính giá
     * @param Promotion $promotion - Khuyến mãi áp dụng
     * @return float|null - Giá sau khuyến mãi hoặc null nếu không áp dụng được
     */
    private function calculateDiscountPrice($product, $promotion)
    {
        // Kiểm tra khuyến mãi có đang hoạt động không
        if (!$promotion->is_active) {
            return null;
        }

        // Kiểm tra thời gian khuyến mãi
        $now = now();
        if ($now < $promotion->start_date || $now > $promotion->end_date) {
            return null;
        }

        // Tính giá theo loại khuyến mãi (phần trăm hoặc số tiền cố định)
        if ($promotion->discount_type == 'percentage') {
            return round($product->price * (1 - $promotion->discount_value/100));
        } else {
            return max(0, $product->price - $promotion->discount_value);
        }
    }

    /**
     * Cập nhật giá khuyến mãi cho danh sách sản phẩm
     * @param Collection $products - Danh sách sản phẩm cần cập nhật
     * @param Promotion|null $promotion - Khuyến mãi mới (nếu có)
     */
    private function updateProductDiscounts($products, $promotion = null)
    {
        foreach ($products as $product) {
            // Nếu có khuyến mãi mới và đang active
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

    /**
     * Lưu khuyến mãi mới vào database
     * @param Request $request - Dữ liệu từ form
     * @return RedirectResponse - Chuyển hướng về danh sách khuyến mãi
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
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
            // Tạo khuyến mãi mới
            $promotion = Promotion::create([
                'name' => $request->name,
                'description' => $request->description,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'start_date' => Carbon::parse($request->start_date),
                'end_date' => Carbon::parse($request->end_date),
                'is_active' => true
            ]);

            // Log tạo khuyến mãi
            Log::info('Created new promotion', [
                'promotion_id' => $promotion->id,
                'name' => $promotion->name
            ]);

            // Lấy danh sách sản phẩm và tạo liên kết
            $products = Product::whereIn('id', $request->products)->get();
            $promotion->products()->attach($request->products);
            
            // Cập nhật giá khuyến mãi cho sản phẩm
            $this->updateProductDiscounts($products, $promotion);

            // Tạo thông báo cho tất cả người dùng
            $users = User::where('role', '!=', 'admin')->get();
            foreach ($users as $user) {
                $user->notifications()->create([
                    'type' => 'promotion',
                    'data' => json_encode([
                        'title' => 'Khuyến mãi mới',
                        'content' => "Khuyến mãi {$promotion->name} đã được tạo. Giảm giá {$promotion->discount_value}" . 
                                   ($promotion->discount_type == 'percentage' ? '%' : ' VNĐ'),
                        'promotion_id' => $promotion->id,
                        'start_date' => $promotion->start_date,
                        'end_date' => $promotion->end_date
                    ])
                ]);
            }
            
            DB::commit();
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Khuyến mãi đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to create promotion', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Có lỗi xảy ra khi tạo khuyến mãi: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết khuyến mãi
     * @param Promotion $promotion - Khuyến mãi cần xem
     * @return View - Trang hiển thị chi tiết
     */
    public function show(Promotion $promotion)
    {
        $promotion->load('products');
        
        // Tính toán trạng thái và thời gian đếm ngược
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

    /**
     * Lấy trạng thái khuyến mãi (API)
     * @param Promotion $promotion - Khuyến mãi cần kiểm tra
     * @return JsonResponse - Trạng thái và thời gian đếm ngược
     */
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

    /**
     * Hiển thị form chỉnh sửa khuyến mãi
     * @param Promotion $promotion - Khuyến mãi cần chỉnh sửa
     * @return View - Trang form chỉnh sửa
     */
    public function edit(Promotion $promotion)
    {
        $products = Product::where('status', 1)->get();
        $selectedProducts = $promotion->products->pluck('id')->toArray();
        return view('admin.promotions.edit', compact('promotion', 'products', 'selectedProducts'));
    }

    /**
     * Cập nhật thông tin khuyến mãi
     * @param Request $request - Dữ liệu từ form
     * @param Promotion $promotion - Khuyến mãi cần cập nhật
     * @return RedirectResponse - Chuyển hướng về danh sách khuyến mãi
     */
    public function update(Request $request, Promotion $promotion)
    {
        // Validate dữ liệu đầu vào
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
            // Log bắt đầu cập nhật
            Log::info('Starting promotion update', [
                'promotion_id' => $promotion->id
            ]);

            // Lấy danh sách sản phẩm cũ để cập nhật lại giá
            $oldProducts = $promotion->products()->get();

            // Cập nhật thông tin khuyến mãi
            $promotion->update([
                'name' => $request->name,
                'description' => $request->description,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'start_date' => Carbon::parse($request->start_date),
                'end_date' => Carbon::parse($request->end_date)
            ]);

            // Log cập nhật thông tin
            Log::info('Updated promotion details', [
                'promotion_id' => $promotion->id
            ]);

            // Cập nhật lại giá cho sản phẩm cũ
            $this->updateProductDiscounts($oldProducts);

            // Cập nhật danh sách sản phẩm mới
            $promotion->products()->sync($request->products);
            
            // Cập nhật giá khuyến mãi cho sản phẩm mới
            $newProducts = Product::whereIn('id', $request->products)->get();
            $this->updateProductDiscounts($newProducts, $promotion);

            // Tạo thông báo cập nhật cho tất cả người dùng
            $users = User::where('role', '!=', 'admin')->get();
            foreach ($users as $user) {
                $user->notifications()->create([
                    'type' => 'promotion',
                    'data' => json_encode([
                        'title' => 'Cập nhật khuyến mãi',
                        'content' => "Khuyến mãi {$promotion->name} đã được cập nhật. Giảm giá {$promotion->discount_value}" . 
                                   ($promotion->discount_type == 'percentage' ? '%' : ' VNĐ'),
                        'promotion_id' => $promotion->id,
                        'start_date' => $promotion->start_date,
                        'end_date' => $promotion->end_date
                    ])
                ]);
            }
            
            DB::commit();
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Khuyến mãi đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update promotion', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật khuyến mãi: ' . $e->getMessage());
        }
    }

    /**
     * Xóa khuyến mãi
     * @param Promotion $promotion - Khuyến mãi cần xóa
     * @return RedirectResponse - Chuyển hướng về danh sách khuyến mãi
     */
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
            
            // Cập nhật lại giá cho các sản phẩm
            $this->updateProductDiscounts($products);
            
            DB::commit();
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Khuyến mãi đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi xóa khuyến mãi: ' . $e->getMessage());
        }
    }

    /**
     * Bật/tắt trạng thái khuyến mãi (API)
     * @param Promotion $promotion - Khuyến mãi cần thay đổi trạng thái
     * @return JsonResponse - Kết quả thay đổi trạng thái
     */
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