<?php  

namespace App\Http\Controllers\Admin;  

use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;  
use App\Models\Order;  
use App\Models\Product;  
use App\Models\User;  
use Carbon\Carbon;  
use Illuminate\Support\Facades\DB;  
use App\Models\OrderItem;  

class DashboardController extends Controller {     
    public function dashboard()     
    {         
        // Thống kê cơ bản         
        $totalUsers = User::where('role', 'user')->count(); // Tổng số người dùng có role là 'user'         
        $totalProducts = Product::count(); // Tổng số sản phẩm         
        $totalOrders = Order::count(); // Tổng số đơn hàng         
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount'); // Tổng doanh thu từ các đơn hàng đã hoàn thành

        // Lấy ngày bắt đầu và kết thúc của tuần hiện tại         
        $startOfWeek = Carbon::now()->startOfWeek();         
        $endOfWeek = Carbon::now()->endOfWeek();  

        // Lấy doanh thu mỗi ngày trong tuần hiện tại         
        $weeklyRevenue = Order::where('status', 'completed')             
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])             
            ->select(                 
                DB::raw('DATE(created_at) as date'),                 
                DB::raw('SUM(total_amount) as daily_revenue')             
            )             
            ->groupBy('date')             
            ->get()             
            ->keyBy('date'); // Sắp xếp kết quả theo ngày

        // Chuẩn bị mảng doanh thu cho từng ngày trong tuần         
        $revenueData = [];         
        $labels = [];                  
        for ($date = $startOfWeek->copy(); $date <= $endOfWeek; $date->addDay()) {             
            $dayName = $date->format('D'); // Lấy tên thứ bằng tiếng Anh             
            switch($dayName) {                 
                case 'Mon': $labels[] = 'T2'; break;                 
                case 'Tue': $labels[] = 'T3'; break;                 
                case 'Wed': $labels[] = 'T4'; break;                 
                case 'Thu': $labels[] = 'T5'; break;                 
                case 'Fri': $labels[] = 'T6'; break;                 
                case 'Sat': $labels[] = 'T7'; break;                 
                case 'Sun': $labels[] = 'CN'; break;             
            }                          
            $currentDate = $date->format('Y-m-d'); // Định dạng ngày hiện tại             
            $revenueData[] = $weeklyRevenue->has($currentDate)                  
                ? (int)$weeklyRevenue[$currentDate]->daily_revenue // Nếu có dữ liệu doanh thu trong ngày thì lấy             
                : 0; // Nếu không có thì gán bằng 0         
        }  

        // Phân bố sản phẩm theo danh mục         
        $categoryDistribution = Product::select('category_id', DB::raw('count(*) as total'))             
            ->groupBy('category_id')             
            ->with('category')             
            ->get()             
            ->map(function ($item) {                 
                return [                     
                    'label' => $item->category->name ?? 'Khác', // Nếu không có tên danh mục thì gán là 'Khác'                     
                    'value' => $item->total                 
                ];             
            });  

        // Tính phần trăm tăng trưởng doanh thu         
        $previousRevenue = Order::where('status', 'completed')             
            ->where('created_at', '<', $startOfWeek)             
            ->whereBetween('created_at', [$startOfWeek->copy()->subWeek(), $startOfWeek])             
            ->sum('total_amount'); // Tổng doanh thu của tuần trước tuần hiện tại

        $currentRevenue = array_sum($revenueData); // Tổng doanh thu tuần hiện tại         
        $revenueGrowth = $previousRevenue > 0              
            ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 1) // Tính phần trăm tăng trưởng nếu có doanh thu tuần trước             
            : 0; // Nếu không thì bằng 0  

        // Lấy 5 sản phẩm được mua gần nhất         
        $recentProducts = OrderItem::with(['product', 'order'])             
            ->whereHas('order', function($query) {                 
                $query->where('status', '!=', 'cancelled'); // Chỉ lấy đơn không bị hủy             
            })             
            ->select(                 
                'product_id',                      
                DB::raw('MAX(created_at) as last_purchased'), // Ngày mua gần nhất                     
                DB::raw('COUNT(*) as purchase_count'), // Số lần mua sản phẩm đó                     
                DB::raw('SUM(quantity) as total_quantity') // Tổng số lượng sản phẩm đã mua             
            )             
            ->groupBy('product_id')             
            ->orderBy('last_purchased', 'desc') // Sắp xếp theo ngày mua gần nhất             
            ->take(5) // Chỉ lấy 5 sản phẩm             
            ->get();  

        // Trả dữ liệu về view dashboard         
        return view('admin.dashboard', compact(             
            'totalUsers',             
            'totalProducts',             
            'totalOrders',             
            'totalRevenue',             
            'revenueData',             
            'labels',             
            'categoryDistribution',             
            'revenueGrowth',             
            'recentProducts'         
        ));     
    } 
}
