@extends('layouts.app')

@section('title', '商品購入')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<main class="purchase">
    {{-- formをここで1つだけ開く --}}
    <form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="GET" class="purchase__container">
        @csrf
        {{-- 左側:商品情報 --}}
        <div class="purchase__left">
            <div class="purchase__item">
                @if (!empty($item->img_url))
                    <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" class="purchase__item-image">
                @else
                    <div class="purchase__item-noimage">商品画像</div>
                @endif
                <div class="purchase__item-info">
                    <p class="purchase__item-name">{{ $item->name }}</p>
                    <p class="purchase__item-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            {{-- 支払い方法 --}}
            <div class="purchase__section">
                <h3 class="purchase__title">お支払い方法</h3>
                <select name="payment_method" class="purchase__select" onchange="this.form.submit()">
                    <option value="">選択してください</option>
                    <option value="コンビニ払い" {{ request('payment_method') === 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                    <option value="カード払い" {{ request('payment_method') === 'カード払い' ? 'selected' : '' }}>カード払い</option>
                </select>
                @error('payment_method')
                    <p class="purchase__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 配送先 --}}
            <div class="purchase__section">
                <div class="purchase__section-group">
                    <h3 class="purchase__title">配送先
                        <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="purchase__change-like">変更する</a>
                    </h3>
                </div>
                <div class="purchase__address">
                    <p>〒 {{ $user->postcode }}</p>
                    <p>{{ $user->address }}</p>
                    <p>{{ $user->building }}</p>
                </div>
            </div>
        </div>

        {{-- 右側:購入画面 --}}
        <div class="purchase__summary">
            <div class="purchase__card">
                <table class="purchase__table">
                    <tr class="table__top-border">
                        <th>商品代金</th>
                        <td>¥{{ number_format($item->price) }}</td>
                    </tr>
                    <tr class="table__bottom-border">
                        <th>お支払い方法</th>
                        <td>{{ request('payment_method') ?: '未設定' }}</td>
                    </tr>
                </table>
            </div>

            <div class="purchase__btn">
                <button type="submit" formaction="{{ route('purchase.store', ['item_id' => $item->id]) }}" formmethod="POST" class="purchase__button">購入する
                </button>
            </div>
        </div>
        
    </form>
</main>
@endsection
