@extends('layouts.app')

@section('title', '商品詳細（ゲスト）')
@section('css')
<link rel="stylesheet" href="{{ asset('css/style_guest.css') }}">
@endsection

@section('content')
    <main class="item-detail">
        <div class="item-detail__container">
            <div class="item-detail__image-area">
                <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}" class="item-detail__image">
            </div>

            <div class="item-detail__info">
                <h2 class="item-detail__name">{{ $item->name }}</h2>
                <p>ブランド: {{ $item->brand ?: '無し' }}</p>
                <p class="item-detail__price">¥{{ number_format($item->price) }} <span class="item-detail__tax">(税込)</span></p>

                <div class="item-detail__actions">
                    <a href="{{ route('login') }}" class="item-detail__icon">☆ <span>{{ $item->likes->count() }}</span></a>
                    <a href="{{ route('login') }}" class="item-detail__icon">💬 <span>{{ $item->comments->count() }}</span></a>
                </div>

                <button class="item-detail__purchase-btn">購入手続きへ</button>

                <section class="item-detail__section">
                    <h3 class="item-detail__subtitle">商品説明</h3>
                    <p class="item-detail__description">{{ $item->description }}</p>
                </section>

                <section class="item-detail__section">
                    <h3 class="item-detail__subtitle">商品の情報</h3>
                    @if ($item->categories->isNotEmpty())
                        <p>
                            カテゴリー: 
                            @foreach ($item->categories as $category)
                            {{ $category->name }}@if (!$loop->last), @endif
                            @endforeach
                        </p>
                    @else
                        <p>カテゴリー: 未設定</p>
                    @endif

                    <p>商品の状態: {{ $item->condition ?? '良好' }}</p>
                </section>

                <section class="item-detail__section">
                    <h3 class="item-detail__subtitle">コメント({{ $item->comments->count() }})</h3>

                    @foreach($item->comments as $comment)
                        <div class="item-detail__comment">
                            <div class="item-detail__comment-user">
                                <img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="user" class="item-detail__comment-icon">
                                <span>{{ $comment->user->name }}</span>
                            </div>
                            <p class="item-detail__comment-text">{{ $comment->content }}</p>
                        </div>
                    @endforeach

                    {{-- ゲストはコメントできない --}}
                    <div class="item-detail__comment-form">
                        <label class="item-detail__textarea-message">商品へのコメント</label>
                        <textarea class="item-detail__textarea" placeholder="コメントするにはログインしてください" disabled></textarea>
                        <a href="{{ route('login') }}" class="item-detail__button item-detail__button--disabled">コメントを送信する</a>
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
