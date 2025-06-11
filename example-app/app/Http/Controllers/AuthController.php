<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }

    public function checkLogin(Request $request)
    {

        $request->validate([
            'email' => ['required', 'email', 'regex:/@go\.buu\.ac\.th$/'],
            'password' => 'required|min:6',
        ], [
            'email.regex' => 'email ต้องเป็น @go.buu.ac.th เท่านั้น',
            'email.email' => 'รูปแบบ email ไม่ถูกต้อง',
            'email.required' => 'กรุณากรอก email',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return redirect('/home')->with('success', 'เข้าสู่ระบบสำเร็จ');
        } else {
            return back()->withErrors(['password' => 'email หรือ password ไม่ถูกต้อง']);
        }
    }
    public function homePage()
    {
        return view('home');
    }
}
