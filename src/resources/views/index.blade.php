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
        <div class="header__inner">
            <div class="header__logo">
                <img src="/logo.svg" alt="COACHTECHロゴ">
            </div>

            <div class="header__search">
                <input type="text" placeholder="なにをお探しですか？">
            </div>

            <nav class="header__nav">
                <form action="{{ route('logout') }}" method="POST" class="header__logout-form">
                    @csrf
                    <button type="submit" class="header__link">ログアウト</button>
                </form>
                <a href="/mypage" class="header__link">マイページ</a>
                <a href="#" class="header__button">出品</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="product-tabs">
            <a href="/?tab=recommend" class="product-tabs__link product-tabs__link--active">おすすめ</a>
            <a href="/?tab=mylist" class="product-tabs__link">マイリスト</a>
        </div>

        {{-- 商品一覧 --}}
        <section class="product-grid">
            {{-- ここは仮で表示させてるだけ --}}
            <div class="product-card">
                <div class="product-card__image">商品画像</div>
                <p class="product-card__name">商品名</p>
            </div>
            <div class="product-card__image">商品画像</div>
                <p class="product-card__name">商品名</p>
            </div>
            <div class="product-card__image">商品画像</div>
                <p class="product-card__name">商品名</p>
            </div>
        </section>
    </main>
</body>
</html>