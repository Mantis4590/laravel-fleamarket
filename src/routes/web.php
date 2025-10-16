<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [CustomAuthenticatedSessionController::class, 'destroy'])
->name('logout');
Route::post('/login', [CustomAuthenticatedSessionController::class, 'store'])->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('like.store');
    Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy'])->name('like.destroy');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// ==============================
// ゲスト表示
// ==============================
Route::get('/guest', [ItemController::class, 'guestIndex'])->name('items.guest');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// ==============================
// ログイン後の商品一覧
// ==============================
Route::get('/home', [ItemController::class, 'index'])->name('home');
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// ==============================
// トップアクセス時にログイン状態で振り分け
// ==============================
Route::get('/', function (Request $request) {
    if (Auth::check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('items.guest');
    }
});

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])
->middleware('auth')
->name('comment.store');
Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('like.store');
Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy'])->name('like.destroy');