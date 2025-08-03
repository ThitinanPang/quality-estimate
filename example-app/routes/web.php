<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[AuthController::class,'loginPage'])->name('login');
Route::post('/login',[AuthController::class,'checkLogin'])->name('login.submit');
Route::get('/user',[AuthController::class,'userPage'])->name('user');
Route::get('/home',[AuthController::class,'homePage'])->name('home');
Route::get('/assessment',[AuthController::class,'assessmentPage'])->name('assessment');
Route::get('/evaluation',[AuthController::class,'evaluationPage'])->name('evaluation');