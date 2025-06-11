<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[AuthController::class,'loginPage'])->name('login');
Route::post('/login',[AuthController::class,'checkLogin'])->name('login.submit');

Route::get('/home',[AuthController::class,'homePage'])->name('home');