<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }
}
