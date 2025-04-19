<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if(!Auth::check() || !$user->isAdmin()){
            return redirect()->route('admin.login')
            ->with('error', 'Bạn không có quyền truy cập vào trang quản trị');
        }
        return $next($request);
    }
}