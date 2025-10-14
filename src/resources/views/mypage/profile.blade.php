<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール設定</title>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>
    <header class="header">
        <img src="{{ asset('logo.svg') }}" alt="COACHTECHロゴ" class="header__logo">
        <nav class="header__nav">
            <input type="text" class="header__search" placeholder="なにをお探しですか？">
            <form action="{{ route('logout') }}" method="POST" class="header__logout-form">
                @csrf
                <button type="submit" class="header__link">ログアウト</button>
            </form>
            <a href="/mypage" class="header__link">マイページ</a>
            <a href="#" class="header__button">出品</a>
        </nav>
    </header>

    <main class="profile">
        <h1 class="profile__title">プロフィール設定</h1>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile__form">
        @csrf

        <div class="profile__image-area">
            @if(!empty($user->profile_image))
                <img src="{{ asset('storage/'.$user->profile_image) }}" alt="プロフィール画像" class="profile__image">
            @else
                <div class="profile__image-circle"></div>
            @endif

            <label for="image" class="profile__image-button">画像を選択する</label>
            <input type="file" name="image" id="image" class="profile__input-file" hidden>
            @error('image') <p class="profile__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile__group">
            <label for="name" class="profile__label">ユーザー名</label>
            <input type="text" name="name" id="name" class="profile__input" value="{{ old('name', $user->name) }}">
            @error('name')
            <p class="profile__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile__group">
            <label for="postcode" class="profile__label">郵便番号</label>
            <input type="text" name="postcode" id="postcode" class="profile__input" value="{{ old('postcode', $user->postcode) }}">
            @error('postcode')
            <p class="profile__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile__group">
            <label for="address" class="profile__label">住所</label>
            <input type="text" name="address" id="address" class="profile__input" value="{{ old('address', $user->address) }}">
            @error('address')
            <p class="profile__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile__group">
            <label for="building" class="profile__label">建物名</label>
            <input type="text" name="building" id="building" class="profile__input" value="{{ old('building', $user->building) }}">
        </div>

        <button class="profile__button" type="submit">更新する</button>
        </form>
    </main>
</body>
</html>