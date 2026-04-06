<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class MultiUserProvider extends EloquentUserProvider
{
    // Override ฟังก์ชัน retrieveById เพื่อให้หาจาก 2 ตาราง
    public function retrieveById($identifier)
    {
        // 1. หาจากตาราง users ก่อน
        $user = \App\Models\User::find($identifier);
        if ($user) return $user;

        // 2. ถ้าไม่เจอ ให้หาจาก users_assessor
        return \App\Models\UserAssessor::find($identifier);
    }

    // (Optional) ถ้ามีการใช้ฟังก์ชัน "Remember Me" ต้อง Override retrieveByToken ด้วยวิธีเดียวกัน
    public function retrieveByToken($identifier, $token)
    {
        $user = \App\Models\User::where($identifier, $token)->first();
        if ($user) return $user;
        return \App\Models\UserAssessor::where($identifier, $token)->first();
    }
}