@extends('layouts.app')

@section('title', '会員登録画面')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('header')
    <header class="header">
        <img src="{{ asset('logo.svg') }}" alt="COACHTECHロゴ" class="header__logo">
    </header>
@endsection

@section('content')
    <section class="register">
        <h1 class="register__title">会員登録</h1>
        <form action="{{ route('register') }}" method="POST" class="register__form">
            @csrf
            <div class="register__group">
                <label for="name" class="register__label">ユーザー名</label>
                <input type="text" name="name" id="name" class="register__input" value="{{ old('name') }}">
                @error('name')
                    <p class="register__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="register__group">
                <label for="email" class="register__label">メールアドレス</label>
                <input type="text" name="email" id="email" class="register__input" value="{{ old('email') }}">
                @error('email')
                    <p class="register__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="register__group">
                <label for="password" class="register__label">パスワード</label>
                <input type="password" name="password" id="password" class="register__input">
                @error('password')
                    @if ($message !== 'パスワードと一致しません')
                        <p class="register__error">{{ $message }}</p>
                    @endif
                @enderror
            </div>
            <div class="register__group">
                <label for="password_confirmation" class="register__label">確認用パスワード</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="register__input">
                @error('password')
                    @if ($message === 'パスワードと一致しません')
                        <p class="register__error">{{ $message }}</p>
                    @endif
                @enderror
            </div>

            <button type="submit" class="register__button">登録する</button>
        </form>

        <p class="register__link">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </p>
    </section>
@endsection