<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function show()
    {
        return view('profile.show',['user'=>Auth::user()]);
    }
    public function update(Request $request)
    {
       $user = Auth::user();
       $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'phone' => ['nullable', 'string', 'max:20'],
        'address' => ['nullable', 'string', 'max:255'],
       ]);
      DB::table('users')
      ->where('id', $user->id)
      ->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
      ]);
       return redirect()->route('profile')->with('success', 'Cập nhật thông tin thành công');
    }
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);
        DB::table('users')
        ->where('id', $user->id)
        ->update([
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('profile')->with('success', 'Cập nhật mật khẩu thành công');
    }
}


        
 
            
