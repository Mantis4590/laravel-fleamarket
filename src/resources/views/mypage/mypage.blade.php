@extends('layouts.app')

@section('title', 'マイページ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage">
    <div class="mypage__header">
        <div class="mypage__profile">
            <div class="mypage__icon-wrap">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="mypage__icon">
                @else
                    <div class="mypage__icon--default"></div>
                @endif
            </div>
            <h2 class="mypage__name">{{ $user->name }}</h2>
        </div>
        <a href="{{ route('profile.edit') }}" class="mypage__edit-btn">プロフィールを編集</a>
    </div>

    <div class="mypage__tabs">
        <a href="{{ route('mypage.index', ['page' => 'sell']) }}" class="mypage__tab {{ $page === 'sell' ? 'mypage__tab--active' : '' }}">出品した商品</a>
        <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="mypage__tab {{ $page === 'buy' ? 'mypage__tab--active' : '' }}">購入した商品</a>
        <a href="{{ route('mypage.index', ['page' => 'transaction']) }}"
        class="mypage__tab {{ $page === 'transaction' ? 'mypage__tab--active' : '' }}">
        取引中の商品
        </a>
    </div>
    
    <div class="mypage__item-list">
        @foreach($items as $item)

            @php
                $link = ($page === 'transaction') ? route('transactions.chat', $item) : null;
            @endphp

            @if($link)
                <a href="{{ $link }}" class="mypage__item-card mypage__item-card--link">
            @else
                <div class="mypage__item-card">
            @endif

                <div class="mypage__item-img">
                    @if (!empty($item->img_url))
                        {{-- ここ、今のseedは "images/..." で storage じゃないから asset() が正しい --}}
                        <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}" class="item-card__image">
                    @else
                        <div class="item-card__noimage">商品画像</div>
                    @endif
                </div>

                <p class="mypage__item-name">{{ $item->name }}</p>

            @if($link)
                </a>
            @else
                </div>
            @endif

        @endforeach
    </div>

</div>
@endsection