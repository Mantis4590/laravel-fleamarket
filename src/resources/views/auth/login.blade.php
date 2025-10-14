<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <header class="header">
        <img src="{{ asset('logo.svg') }}" alt="COACHTECHロゴ" class="header__logo">
    </header>

    <main class="login">
        <h1 class="login__title">ログイン</h1>
        <form method="POST" action="{{ route('login') }}" class="login__form">
            @csrf

            <div class="login__group">
                <label for="email" class="login__label">メールアドレス</label>
                <input type="text" name="email" id="email" class="login__input">
                @error('email')
                <p class="login__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="login__group">
                <label for="password" class="login__label">パスワード</label>
                <input type="password" name="password" id="password" class="login__input">
                @error('password')
                <p class="login__error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="login__button">ログインする</button>
        </form>

        <p class="login__link">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </p>
    </main>
</body>
</html>