<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>
    <header class="header">
        <img src="{{ asset('logo.svg') }}" alt="COACHTECHロゴ" class="header__logo">

        <div class="header__center">
            <input type="text" class="header__search" placeholder="なにをお探しですか？">
        </div>

        <nav class="header__nav">
        <form action="{{ route('logout') }}" method="POST" class="header__logout-form">
            @csrf
            <button type="submit" class="header__link header__link--logout">ログアウト</button>
        </form>

        <a href="/mypage" class="header__link">マイページ</a>
        <a href="#" class="header__button">出品</a>
        </nav>
    </header>

    <main class="main">
        <div class="tab">
            <a href="{{ route('home', ['tab' => 'recommend']) }}" class="tab__item {{ request('tab', 'recommend') === 'recommend' ? 'tab__item--active' : '' }}">おすすめ</a>
            <a href="{{ route('home', ['tab' => 'mylist']) }}" class="tab__item {{ request('tab') === 'mylist' ? 'tab__item--active' : '' }}">マイリスト</a>
        </div>

        {{-- 商品一覧 --}}
        <section class="item-list">
            @foreach ($items as $item)
            <div class="item-card">
                {{-- 商品画像をクリックで詳細へ --}}
                <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-card__link">
                    <div class="item-card__img">
                        @if (!empty($item->img_url))
                            <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}" class="item-card__image">
                        @else
                    <div class="item-card__noimage">商品画像</div>
                    @endif
                    </div>
                </a>
                <p class="item-card__name">{{ $item->name }}</p>
            </div>
        @endforeach
        </section>
    </main>
</body>
</html>