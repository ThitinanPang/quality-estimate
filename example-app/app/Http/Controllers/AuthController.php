<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use LdapRecord\Laravel\LdapRecord;
use LdapRecord\Ldap;
use LdapRecord\Container;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Connection;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }

    public function checkLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

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

        $username = explode('@', $request->email)[0];
        $ldapUsername = 'BUU\\' . $username;
        $ldapPassword = $request->password;

        try {
            // สร้าง connection ใหม่ด้วย username/password ที่ผู้ใช้กรอก
            $connection = new Connection([
                'hosts' => [env('LDAP_HOST_2')],
                'base_dn' => env('LDAP_BASE_DN'),
                'username' => $ldapUsername,
                'password' => $ldapPassword,
                'port' => env('LDAP_PORT', 389),
            ]);

            $connection->connect();
            $connection->auth()->bind();
            session(['user_email' => $request->email]);
            // ถ้าไม่ throw exception แปลว่า login สำเร็จ
            logger('LDAP bind success: ' . $ldapUsername);

            return redirect('/home')->with('success', 'เข้าสู่ระบบสำเร็จ');

        } catch (\LdapRecord\Auth\BindException $e) {
            logger('LDAP bind failed: ' . $e->getMessage());

            return back()->withErrors([
                'email' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
            ]);
        }
    }
    public function homePage()
    {
        return view('home');
    }
}
