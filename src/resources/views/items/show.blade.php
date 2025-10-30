@extends('layouts.app')

@section('title', '商品詳細')
@section('css')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">


@endsection

@section('content')
    <main class="item-detail">
        <div class="item-detail__container">
            {{-- 左カラム : 商品画像 --}}
            <div class="item-detail__image-area">
                @if (!empty($item->img_url))
                    <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" class="item-detail__image">
                @else
                    <div class="item-detail__noimage">商品画像</div>
                @endif

                {{-- SOLD表示だけ（未購入なら何も表示しない） --}}
                @if ($item->buyer_id)
                    <p class="item-detail__sold">SOLD</p>
                @endif
            </div>


            {{-- 右カラム : 商品情報 --}}
            <div class="item-detail__info">
                <div class="item-detail__name">{{ $item->name }}</div>
                <div class="item-detail__brand">
                    <p>ブランド: {{ $item->brand ?: '無し' }}</p>
                </div>
                
                <p class="item-detail__price">¥{{ number_format($item->price) }} <span class="item-detail__tax">(税込)</span></p>

                @php
                    $liked = auth()->check() && $item->likes->contains('user_id', auth()->id());
                @endphp

                <div class="item-detail__actions">
    {{-- いいね --}}
    <form action="{{ $liked ? route('like.destroy', $item->id) : route('like.store', $item->id) }}" method="POST" class="item-detail__action item-detail__action--like">
        @csrf
        @if($liked)
            @method('DELETE')
            <button type="submit" class="item-detail__icon item-detail__icon--liked">
                ★
            </button>
        @else
            <button type="submit" class="item-detail__icon">☆</button>
        @endif
        <span class="item-detail__count">{{ $item->likes->count() }}</span>
    </form>

    {{-- コメント --}}
    <div class="item-detail__action">
        <span class="material-icons-outlined item-detail__icon">chat_bubble_outline</span>
        <span class="item-detail__count">{{ $item->comments->count() }}</span>
    </div>
</div>

                <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="item-detail__purchase-btn">
                購入手続きへ
                </a>


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

                    @auth
                        <form action="{{ route('comment.store', $item->id) }}" method="POST" class="item-detail__comment-form">
                            @csrf
                            <div class="item-detail__textarea-message">商品へのコメント</div>
                            <textarea name="comment" class="item-detail__textarea"></textarea>
                            @error('comment')
                                <p class="item-detail__error">{{ $message }}</p>
                            @enderror
                            <button type="submit" class="item-detail__button">コメントを送信する</button>
                        </form>
                    @endauth
                </section>
            </div>
        </div>
    </main>
@endsection
