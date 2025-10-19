<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'フリマアプリ')</title>

    {{-- 共通CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- ページ固有CSS --}}
    @yield('css')
</head>
<body>
    @section('header')
        <header class="header">
    {{-- 左：ロゴ --}}
    <div class="header__left">
        <a href="{{ auth()->check() ? route('home') : route('items.guest') }}">
            <img src="{{ asset('logo.svg') }}" alt="COACHTECHロゴ" class="header__logo">
        </a>
    </div>

    {{-- 中央：検索欄 --}}
    <form action="{{ route('home') }}" method="GET" class="header__search">
        <div class="header__center">
            <input type="text" name="keyword" class="header__search-input" placeholder="なにをお探しですか？">
        </div>
    </form>

    {{-- 右：ナビゲーション --}}
    <div class="header__right">
        <nav class="header__nav">
            @auth
                <form action="{{ route('logout') }}" method="POST" class="header__logout-form">
                    @csrf
                    <button type="submit" class="header__link header__link--logout">ログアウト</button>
                </form>
                <a href="{{ route('mypage.index') }}" class="header__link">マイページ</a>
                <a href="#" class="header__button">出品</a>
            @else
                <a href="{{ route('login') }}" class="header__link header__link--login">ログイン</a>
                <a href="/mypage" class="header__link">マイページ</a>
                <a href="#" class="header__button">出品</a>
            @endauth
        </nav>
    </div>
</header>

    @show

    {{-- ===============================
        メインコンテンツ
    =============================== --}}
    <main class="main">
        @yield('content')
    </main>
</body>
</html>
