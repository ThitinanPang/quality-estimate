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
            'email' => ['required', 'email'],
            'password' => 'required|min:6',
        ], [
            // 'email.regex' => 'email ต้องเป็น @go.buu.ac.th เท่านั้น',
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
                'hosts' => [config('app.ldap_host_2')],
                'base_dn' => config('app.ldap_base_dn'),
                'username' => $ldapUsername,
                'password' => $ldapPassword,
                'port' => config('app.ldap_port'),
            ]);

            $connection->connect();
            $connection->auth()->bind();
            // ดึงข้อมูล
            $ldapUser = $connection->query()->where('samaccountname', '=', $username)->first();
            
            $email = $ldapUser['mail'][0] ?? $request->email;

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'role' => $user->role ?? null,
                    'status' => 'active',
                ]
            );
            session(['user_name' => $user->name]);
            session(['user_email' => $request->email]);
            if (empty($user->role)) {
                return back()->withErrors([
                    'email' => 'บัญชีนี้ไม่มีสิทธิ์เข้าใช้งาน',
                ]);
            } elseif ($user->role == 'admin') {
                return redirect('/home')->with('success', 'เข้าสู่ระบบสำเร็จ');
            } elseif ($user->role == 'user') {
                return redirect('/home')->with('success', 'เข้าสู่ระบบสำเร็จ');
            } elseif ($user->role == 'admin_buu'){
                return redirect('/home')->with('success', 'เข้าสู่ระบบสำเร็จ');
            }
            // ถ้าไม่ throw exception แปลว่า login สำเร็จ
            logger('LDAP bind success: ' . $ldapUsername);
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
    public function userPage()
    {
        return view('user');
    }
    public function assessmentPage()
    {
        return view('assessment');
    }
    public function evaluationPage()
    {
        return view('evaluation');
    }
}
