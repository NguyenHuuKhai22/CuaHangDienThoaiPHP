<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class NotificationController extends Controller
{
    public function check()
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->notifications()
            ->where('read_at', null)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'debug' => [
                'user_id' => Auth::id(),
                'count' => $notifications->count()
            ]
        ]);
    }

    public function markAsRead(Request $request)
    {
        try {
            Log::info('markAsRead called', [
                'request' => $request->all(),
                'promotion_id' => $request->promotion_id,
                'user_id' => Auth::id(),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type')
            ]);
            
            $user = Auth::user();
            if (!$user) {
                Log::warning('User not authenticated in markAsRead');
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $promotionId = $request->promotion_id;
            /** @var User $user */
            $user = Auth::user();
            
            // Lấy tất cả thông báo chưa đọc
            $notifications = $user->notifications()
                ->where('type', 'promotion')
                ->whereNull('read_at')
                ->get();
            
            Log::info('Found notifications: ' . $notifications->count(), ['promotion_id' => $promotionId]);

            // Tìm thông báo có promotion_id phù hợp trong JSON data
            $notification = null;
            foreach ($notifications as $notif) {
                $data = json_decode($notif->data, true);
                Log::info('Checking notification data', ['data' => $data]);
                
                if (isset($data['promotion_id']) && $data['promotion_id'] == $promotionId) {
                    $notification = $notif;
                    break;
                }
            }

            if ($notification) {
                Log::info('Notification found, marking as read', [
                    'notification_id' => $notification->id,
                    'promotion_id' => $promotionId,
                    'user_id' => $user->id
                ]);
                
                // Sử dụng phương thức markAsRead() của model
                $notification->markAsRead();
                
                // Lấy số lượng thông báo chưa đọc còn lại
                $unreadCount = $user->notifications()
                    ->where('type', 'promotion')
                    ->whereNull('read_at')
                    ->count();
                
                return response()->json([
                    'success' => true,
                    'unreadCount' => $unreadCount
                ]);
            }

            Log::warning('Notification not found for promotion_id', [
                'promotion_id' => $promotionId,
                'user_id' => $user->id
            ]);
            
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thông báo']);

        } catch (\Exception $e) {
            Log::error('Error in markAsRead: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đánh dấu đã đọc'
            ], 500);
        }
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function checkPromotions()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'upcoming' => [],
                    'started' => [],
                    'total' => 0
                ]);
            }
            /** @var User $user */
            $user = Auth::user();
            // Lấy tất cả thông báo về khuyến mãi của user (cả đã đọc và chưa đọc)
            $notifications = $user->notifications()
                ->where('type', 'promotion')
                ->get();

            $upcoming = [];
            $started = [];

            foreach ($notifications as $notification) {
                $data = json_decode($notification->data, true);
                
                if (!isset($data['start_date']) || !isset($data['end_date'])) {
                    continue;
                }

                $startDate = Carbon::parse($data['start_date']);
                $now = Carbon::now();

                // Nếu thời gian bắt đầu trong vòng 30 phút tới
                if ($now->diffInMinutes($startDate, false) > 0 && $now->diffInMinutes($startDate) <= 30) {
                    $notification->minutes_until_start = $now->diffInMinutes($startDate);
                    $upcoming[] = $notification;
                }
                // Nếu mới bắt đầu trong vòng 30 phút trước
                elseif ($now->diffInMinutes($startDate) <= 30 && $now > $startDate) {
                    $started[] = $notification;
                }
            }

            // Chỉ đếm thông báo chưa đọc cho số lượng hiển thị
            $unreadCount = count(array_filter($upcoming, function($notif) {
                return is_null($notif->read_at);
            })) + count(array_filter($started, function($notif) {
                return is_null($notif->read_at);
            }));

            return response()->json([
                'upcoming' => $upcoming,
                'started' => $started,
                'total' => $unreadCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error in checkPromotions: ' . $e->getMessage());
            return response()->json([
                'error' => 'Có lỗi xảy ra khi kiểm tra thông báo',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 