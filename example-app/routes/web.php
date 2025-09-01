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
Route::get('/assessor',[AuthController::class,'assessorPage'])->name('assessor');
Route::get('/faculty',[AuthController::class,'facultyPage'])->name('faculty');
Route::get('/university',[AuthController::class,'universityPage'])->name('university');
Route::get('/listname',[AuthController::class,'listnamePage'])->name('listname');
Route::get('/record',[AuthController::class,'recordPage'])->name('record');
Route::get('/results',[AuthController::class,'resultsPage'])->name('results');
Route::get('/save',[AuthController::class,'savePage'])->name('save');
Route::get('/adduser',[AuthController::class,'adduserPage'])->name('adduser');

Route::get('/userfill',[AuthController::class,'userfillPage'])->name('userfill');
Route::post('/userfill', [AuthController::class,'store'])->name('userfill.submit');