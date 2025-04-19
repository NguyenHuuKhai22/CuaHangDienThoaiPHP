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
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
                    ->latest()
                    ->paginate(10);
        return view('admin.users.index', compact('users')); 
    }
    public function block(User $user){
        if($user->role === 'admin'){
            return back()->with('error','Không thể xóa tài khoản admin');
        }
        $user->is_blocked = !$user->is_blocked;
        $user->blocked_at = $user->is_blocked ? Carbon::now() : null;
        $user->save();

        $message = $user->is_blocked ? 'Đã chặn người dùng.' : 'Đã bỏ chặn người dùng.';
        return back()->with('success', $message);
    }
    public function sendPasswordReset(User $user)
    {
        $status = Password::sendResetLink(['email' => $user->email]);

        return back()->with('status', __($status));
    }
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'role' => 'required|in:user,admin'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Người dùng đã được tạo thành công.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'role' => 'required|in:user,admin'
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed'
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Thông tin người dùng đã được cập nhật.');
    }

    
}