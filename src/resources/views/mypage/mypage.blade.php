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
    </div>

    <div class="mypage__item-list">
        @foreach($items as $item)
            <div class="mypage__item-card">
                <div class="mypage__item-img">
                    @if (!empty($item->img_url))
                        <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" class="item-card__image">
                    @else
                        <div class="item-card__noimage">商品画像</div>
                    @endif

                </div>
                <p class="mypage__item-name">{{ $item->name }}</p>
            </div>
        @endforeach
    </div>

</div>
@endsection