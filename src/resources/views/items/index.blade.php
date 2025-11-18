@extends('layouts.app')

@section('title', '商品一覧')
@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<main class="main">
    <div class="tab">
        <a href="{{ route('home', ['tab' => 'recommend', 'keyword' => request('keyword')]) }}" class="tab__item {{ request('tab', 'recommend') === 'recommend' ? 'tab__item--active' : '' }}">おすすめ</a>
        <a href="{{ route('home', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}" class="tab__item {{ request('tab') === 'mylist' ? 'tab__item--active' : '' }}">マイリスト</a>
    </div>

    {{-- 商品一覧 --}}
    <section class="item-list">
        @foreach ($items as $item)
            <div class="item-card">
                {{-- 商品画像をクリックで詳細へ --}}
                <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-card__link">
                    <div class="item-card__img">
                        @if (!empty($item->img_url))
                            <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" class="item-card__image">
                        @else
                            <div class="item-card__noimage">商品画像</div>
                            @endif

                        {{-- 購入済みでSOLD表示 --}}
                        @if (!empty($item->buyer_id))
                            <span class="item-card__sold">Sold</span>
                        @endif
                    </div>
                </a>
                <p class="item-card__name">{{ $item->name }}</p>
            </div>
        @endforeach
    </section>
</main>
@endsection
