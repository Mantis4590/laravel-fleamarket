@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="exhibition">
    <h2 class="exhibition_title">商品の出品</h2>

    {{-- エラーメッセージ --}}
    @if ($errors->any())
        <div class="exhibition__errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="exhibition__form">
        @csrf

        {{-- 商品画像 --}}
        <section class="exhibition__section">
            <h3 class="exhibition__item-title">商品画像</h3>
            <div class="exhibition__image-border">
                <label for="img_url" class="exhibition__image-label">画像を選択する</label>
            <input type="file" id="img_url" name="img_url" accept="image/jpeg, image/png" hidden>
            @error('img_url')
                <p class="exhibition__error">{{ $message }}</p>
            @enderror
            </div>
        </section>

        {{-- 商品の詳細 --}}
        <section class="exhibition__section">
            <div class="exhibition__section-border">
                <div class="exhibition__subtitle">商品の詳細</div>
            </div>
            

            {{-- カテゴリー --}}
            <div class="exhibition__group">
                <p class="exhibition__label">カテゴリー</p>
                <div class="exhibition__categories">
                    @foreach ($categories as $category)
                        <label for="category_{{ $category->id }}" class="exhibition__category">
                            <input type="checkbox" id="category_{{ $category->id }}" name="category_ids[]" value="{{ $category->id }}"
                                {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach

                </div>
                @error('category/ids')
                    <p class="exhibition__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 商品の状態 --}}
            <div class="exhibition__group">
                <label for="condition" class="exhibition__box">商品の状態</label>
                <select name="condition" id="condition" class="exhibition__select">
                    <option value="">選択してください</option>
                    <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
                    <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                </select>
                @error('condition')
                    <p class="exhibition__error">{{ $message }}</p>
                @enderror
                
            </div>
        </section>

        {{-- 商品名と説明 --}}
        <section class="exhibition__section">
            <div class="exhibition__section-border">
                <div class="exhibition__subtitle">商品名と説明</div>
            </div>
            

            <div class="exhibition__group">
                <label for="name" class="exhibition__box">商品名</label>
                <input type="text" name="name" id="name" class="exhibition__input" value="{{ old('name') }}">
                @error('name')
                    <p class="exhibition__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="exhibition__group">
                <label for="brand" class="exhibition__box">ブランド名</label>
                <input type="text" name="brand" id="brand" class="exhibition__input" value="{{ old('brand') }}">
            </div>

            <div class="exhibition__group">
                <label for="description" class="exhibition__box">商品の説明</label>
                <textarea name="description" id="description" class="exhibition__textarea">{{ old('descripition') }}</textarea>
                @error('description')
                    <p class="exhibition__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="exhibition__group">
                <label for="price" class="exhibition__box">販売価格</label>
                <div class="exhibition__price-box">
                    <span>¥</span>
                    <input type="number" name="price" id="price" class="exhibition__input-price" value="{{ old('price') }}">
                </div>
                @error('price')
                    <p class="exhibition__error">{{ $message }}</p>
                @enderror
            </div>
        </section>

        {{-- 出品ボタン --}}
        <div class="exhibition__submit">
            <button type="submit" class="exhibition__button">出品する</button>
        </div>
    </form>
</div>
@endsection