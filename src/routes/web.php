<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MyPageController;
use Illuminate\Http\Request;

// ==============================
// トップアクセス時（ログイン状態で振り分け）
// ==============================
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('home')
        : redirect()->route('items.guest');
});

// ==============================
// 商品関連
// ==============================
Route::get('/home', [ItemController::class, 'index'])->name('home');
Route::get('/guest', [ItemController::class, 'guestIndex'])->name('items.guest');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// コメント・いいね
Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comment.store');
Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('like.store');
Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy'])->name('like.destroy');

// ==============================
// 認証関連
// ==============================
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [CustomAuthenticatedSessionController::class, 'destroy'])->name('logout');

// ==============================
// マイページ & プロフィール
// ==============================
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [ProfileController::class, 'show'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});
