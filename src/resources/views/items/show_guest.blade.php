@extends('layouts.app')

@section('title', '商品詳細（ゲスト）')
@section('css')
<link rel="stylesheet" href="{{ asset('css/style_guest.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
@endsection

@section('content')
    <main class="item-detail">
        <div class="item-detail__container">
            <div class="item-detail__image-area">
                @if (!empty($item->img_url))
                    <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" class="item-detail__image">
                @else
                    <div class="item-detail__noimage">商品画像</div>
                @endif

                {{-- SOLD表示だけ（未購入なら何も表示しない） --}}
                @if ($item->buyer_id)
                    <p class="item-detail__sold">Sold</p>
                @endif
            </div>


            <div class="item-detail__right">
                <h2 class="item-detail__name">{{ $item->name }}</h2>
                <p>ブランド: {{ $item->brand ?: '無し' }}</p>
                <p class="item-detail__price">¥{{ number_format($item->price) }} <span class="item-detail__tax">(税込)</span></p>

                <div class="item-detail__actions">
                    {{-- いいね --}}
                    <form action="{{ route('login') }}" method="GET" class="item-detail__action item-detail__action--like">
                        <button type="submit" class="item-detail__icon">☆</button>
                        <span class="item-detail__count">{{ $item->likes->count() }}</span>
                    </form>
                    
                    {{-- コメント --}}
                    <div class="item-detail__action">
                        <span class="material-icons-outlined item-detail__icon">chat_bubble_outline
                        </span>
                        <span class="item-detail__count">{{ $item->comments->count() }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="item-detail__purchase-btn">購入手続きへ</a>

                <section class="item-detail__section">
                    <h3 class="item-detail__subtitle">商品説明</h3>
                    <p class="item-detail__description">{{ $item->description }}</p>
                </section>

                <section class="item-detail__sub">
                    <div class="item-detail__info-group">
                        <h3 class="item-detail__info">商品の情報</h3>
                        @if ($item->categories->isNotEmpty())
                            <p class="item-detail--category">
                                カテゴリー: 
                                @foreach ($item->categories as $category)
                                <span class="item-detail--category-name">{{ $category->name }}</span>
                                @if (!$loop->last), @endif
                                @endforeach
                            </p>
                        @else
                            <p>カテゴリー: 未設定</p>
                        @endif

                        <p class="item-detail--category">商品の状態: {{ $item->condition ?? '良好' }}</p>
                    </div>
                    
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
