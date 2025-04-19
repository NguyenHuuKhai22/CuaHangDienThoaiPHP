<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;


class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(['dashboard', 'logout']);
    }

    public function showLoginForm()
    {
        /** @var User $user */
        $user = Auth::user();
        if(Auth::check() && $user->isAdmin()){
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu là bắt buộc',
        ]);

        if(Auth::attempt($credentials)){
            /** @var User $user */
            $user = Auth::user();
            if($user->isAdmin()){
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
            return redirect()->back()->withErrors('Bạn không có quyền truy cập vào trang quản trị');
        }
        return redirect()->back()->withErrors('Email hoặc mật khẩu không chính xác');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    
}   
