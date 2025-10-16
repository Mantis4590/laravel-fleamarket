<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧(ゲスト)</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index_guest.css') }}">

</head>
<body>
    <header class="header">
        <img src="{{ asset('logo.svg') }}" alt="COACHTECHロゴ" class="header__logo">

        <div class="header__center">
            <input type="text" class="header__search" placeholder="なにをお探しですか？">
        </div>

        <nav class="header__nav">
        <a href="{{ route('login') }}" class="header__link header__link--login">ログイン</a>
        <a href="/mypage" class="header__link">マイページ</a>
            <a href="#" class="header__button">出品</a>
        </nav>

    </header>

    <div class="tab">
        <a href="{{ route('items.guest') }}" class="tab__item {{ request('tab', 'recommend') === 'recommend' ? 'tab__item--active' : '' }}">おすすめ</a>
        <a href="#" class="tab__item {{ request('tab') === 'mylist' ? 'tab__item--active' : '' }}">マイリスト</a>
    </div>

    {{-- 商品一覧 --}}
    <main class="product-list">
        {{-- 商品一覧 --}}
        <section class="item-list">
            @foreach ($items as $item)
            <div class="item-card">
                {{-- 画像ラッパとimgクラスは任意（見やすく） --}}
                <div class="item-card__img">
                    <a href="{{ route('item.show', ['item_id' => $item->id]) }}">
                        @if (!empty($item->img_url))
                        <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}" class="item-card__image">
                        @else
                        <div class="item-card__noimage">商品画像</div>
                        @endif
                    </a>
                        
                </div>
            <p class="item-card__name">{{ $item->name }}</p>
        </div>
        @endforeach
        </section>
    </main>
</body>
</html>