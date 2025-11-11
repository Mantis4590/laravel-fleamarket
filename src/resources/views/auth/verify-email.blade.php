@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('header')
    <header class="header">
        <a href="{{ route('register') }}" class="header__logo">
            <img src="{{ asset('logo.svg') }}" alt="COACHTECH">
        </a>
    </header>
@endsection

@section('content')
<div class="verify">
    <div class="verify__message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </div>

    <a href="http://localhost:8025" target="_blank" rel="noopener noreferrer" class="verify__button">認証はこちらから
    </a>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="verify__resend">
            認証メールを再送する
        </button>
    </form>
</div>
@endsection