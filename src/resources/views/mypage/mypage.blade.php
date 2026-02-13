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
            {{-- 名前＋評価をまとめる --}}
            <div class="mypage__name-area">
                <h2 class="mypage__name">{{ $user->name }}</h2>

                @if(!is_null($avgRatingRounded))
                <div class="mypage__rating">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="mypage__star {{ $i <= $avgRatingRounded ? 'is-filled' : '' }}">★</span>
                    @endfor
                </div>
            @endif
        </div>

        </div>
        <a href="{{ route('profile.edit') }}" class="mypage__edit-btn">プロフィールを編集</a>
    </div>

    <div class="mypage__tabs">
        <a href="{{ route('mypage.index', ['page' => 'sell']) }}" class="mypage__tab {{ $page === 'sell' ? 'mypage__tab--active' : '' }}">出品した商品</a>
        <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="mypage__tab {{ $page === 'buy' ? 'mypage__tab--active' : '' }}">購入した商品</a>
        <a href="{{ route('mypage.index', ['page' => 'transaction']) }}"
        class="mypage__tab {{ $page === 'transaction' ? 'mypage__tab--active' : '' }}">
        取引中の商品
        @if($unreadTransactionCount > 0)
            <span class="mypage__tab-badge">{{ $unreadTransactionCount }}</span>
        @endif
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

            @php
                $imgPath = $item->img_url;

                $isInPublic = $imgPath && file_exists(public_path($imgPath));
                $isInStorage = $imgPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imgPath);

                if ($isInPublic) {
                    $imgSrc = asset($imgPath);
                } elseif ($isInStorage) {
                    $imgSrc = asset('storage/' . $imgPath);
                } else {
                    $imgSrc = null;
                }
            @endphp

            <div class="mypage__item-img">
                @if($page === 'transaction' && !empty($item->unread_count) && $item->unread_count > 0)
                    <span class="mypage__item-badge">{{ $item->unread_count }}</span>
                @endif

                @if($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $item->name }}" class="item-card__image">
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