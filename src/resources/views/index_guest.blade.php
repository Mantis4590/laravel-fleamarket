<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧(ゲスト)</title>
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
                <a href="/login" class="header__link">ログイン</a>
                <a href="{{ route('login') }}" class="header__link">マイページ</a>
                <a href="{{ route('login') }}" class="header__button">出品</a>
            </nav>
        </div>
    </header>

    <div class="product-tubs">
        <a href="#" class="product-tabs__link product-tabs__link--active">おすすめ</a>
        <a href="#" class="product-tabs__link product-tabs__link--disabled">マイリスト</a>
    </div>

    {{-- 商品一覧 --}}
    <main class="product-list">
        <section class="product-grid">
            {{-- ダミー商品 --}}
            @for ($i = 0; $i < 6; $i++)
            <div class="product-card">
                <div class="product-card__image">商品画像</div>
                <p class="product-card__name">商品名</p>
            </div>
            @endfor
        </section>
    </main>
</body>
</html>