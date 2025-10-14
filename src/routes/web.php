<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [CustomAuthenticatedSessionController::class, 'destroy'])
->name('logout');
Route::post('/login', [CustomAuthenticatedSessionController::class, 'store'])->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// 共通（ゲスト表示）
Route::get('/guest', [ItemController::class, 'guestIndex'])->name('items.guest');

// 商品一覧（ログイン時のみ）
Route::middleware('auth')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('items.index');
});

// トップへのアクセスを自動振り分け（オプション）
Route::get('/', function (Request $request) {
    if (Auth::check()) {
        return redirect()->route('items.index');
    } else {
        return redirect()->route('items.guest');
    }
});