<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ReadNotificationMiddleware
{
    /**
     * Xử lý đánh dấu thông báo đã đọc từ tham số URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu có tham số mark_read và notification_id
        if ($request->has('mark_read') && $request->has('notification_id')) {
            try {
                $promotionId = $request->notification_id;
                $user = Auth::user();

                if ($user) {
                    /** @var User $user */
                    // Lấy tất cả thông báo chưa đọc
                    $notifications = $user->notifications()
                        ->where('type', 'promotion')
                        ->whereNull('read_at')
                        ->get();

                    // Tìm thông báo có promotion_id phù hợp trong JSON data
                    $notification = null;
                    foreach ($notifications as $notif) {
                        $data = json_decode($notif->data, true);
                        
                        if (isset($data['promotion_id']) && $data['promotion_id'] == $promotionId) {
                            $notification = $notif;
                            break;
                        }
                    }

                    if ($notification) {
                        // Dùng phương thức markAsRead thay vì update
                        $notification->markAsRead();
                        Log::info('Notification marked as read via URL', [
                            'user_id' => $user->id,
                            'notification_id' => $notification->id,
                            'promotion_id' => $promotionId
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error in ReadNotificationMiddleware: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
} 