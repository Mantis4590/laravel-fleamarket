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
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StripeWebhookController;

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
    // 購入画面
    Route::get('/purchase/{item_id}',[PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

});

// 出品画面
Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
Route::post('/sell', [ItemController::class, 'store'])->name('items.store');


// 送り先住所変更画面
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');

Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);