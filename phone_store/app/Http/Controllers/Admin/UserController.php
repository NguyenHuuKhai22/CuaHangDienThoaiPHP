<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng (trừ admin)
     * Phân trang 10 người dùng mỗi trang
     * Sắp xếp theo thời gian tạo mới nhất
     */
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
                    ->latest()
                    ->paginate(10);
        return view('admin.users.index', compact('users')); 
    }

    /**
     * Chặn/bỏ chặn người dùng
     * @param User $user - Người dùng cần chặn/bỏ chặn
     * @return RedirectResponse - Chuyển hướng về trang trước với thông báo
     */
    public function block(User $user){
        // Không cho phép chặn tài khoản admin
        if($user->role === 'admin'){
            return back()->with('error','Không thể chặn tài khoản admin');
        }

        // Đảo ngược trạng thái chặn
        $user->is_blocked = !$user->is_blocked;
        
        // Cập nhật thời gian chặn nếu bị chặn
        $user->blocked_at = $user->is_blocked ? Carbon::now() : null;
        $user->save();

        $message = $user->is_blocked ? 'Đã chặn người dùng.' : 'Đã bỏ chặn người dùng.';
        return back()->with('success', $message);
    }

    /**
     * Gửi link reset mật khẩu cho người dùng
     * @param User $user - Người dùng cần reset mật khẩu
     * @return RedirectResponse - Chuyển hướng về trang trước với trạng thái
     */
    public function sendPasswordReset(User $user)
    {
        $status = Password::sendResetLink(['email' => $user->email]);

        return back()->with('status', __($status));
    }

    /**
     * Hiển thị form tạo người dùng mới
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Lưu người dùng mới vào database
     * @param Request $request - Dữ liệu từ form
     * @return RedirectResponse - Chuyển hướng về danh sách người dùng
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'role' => 'required|in:user,admin'
        ]);

        // Mã hóa mật khẩu
        $validated['password'] = Hash::make($validated['password']);
        
        // Tạo người dùng mới
        User::create($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Người dùng đã được tạo thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa thông tin người dùng
     * @param User $user - Người dùng cần chỉnh sửa
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng
     * @param Request $request - Dữ liệu từ form
     * @param User $user - Người dùng cần cập nhật
     * @return RedirectResponse - Chuyển hướng về danh sách người dùng
     */
    public function update(Request $request, User $user)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'role' => 'required|in:user,admin'
        ]);

        // Nếu có thay đổi mật khẩu thì validate và mã hóa
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed'
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        // Cập nhật thông tin người dùng
        $user->update($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Thông tin người dùng đã được cập nhật.');
    }
}