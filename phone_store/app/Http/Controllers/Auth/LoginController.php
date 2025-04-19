<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        // Kiểm tra thông tin đăng nhập
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['Email hoặc mật khẩu không chính xác'],
            ]);
        }

         /** @var User $user */
         $user = Auth::user();
         // Kiểm tra tài khoản bị khóa
         if ($user ->isBlocked()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.'],
            ]);
        }
        // Tạo session mới
        $request->session()->regenerate();

        // Chuyển hướng dựa vào role
       
        return redirect()->intended(route('home'))
            ->with('success', 'Đăng nhập thành công');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Xóa session và tạo token mới
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Đăng xuất thành công');
    }

    /**
     * Xử lý khi đăng nhập thất bại quá nhiều lần
     */
    protected function sendLockoutResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => ['Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau vài phút.'],
        ]);
    }

    /**
     * Xử lý khi người dùng bị chặn trong quá trình đăng nhập
     */
    protected function sendBlockedResponse()
    {
        throw ValidationException::withMessages([
            'email' => ['Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin để được hỗ trợ.'],
        ]);
    }

   
}