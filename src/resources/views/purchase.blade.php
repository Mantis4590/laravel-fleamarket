@extends('layouts.app')

@section('title', '商品購入')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<main class="purchase">
    <div class="purchase__container">
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
            <form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="POST">
                @csrf
                <div class="purchase__section">
                    <h3 class="purchase__title">お支払い方法</h3>
                    <select name="payment_method" class="purchase__select">
                        <option value="選択してください"></option>
                        <option value="コンビニ払い" {{ old('payment_method') === 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                        <option value="カード払い" {{ old('payment_method') === 'カード払い' ? 'selected' : '' }}>カード払い</option>
                    </select>
                    @error('payment_method')
                        <p class="purchase__error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 配送先 --}}
                <div class="purchase__section">
                    <h3 class="purchase__title">配送先</h3>
                    <div class="purchase__address">
                        <p>〒 {{ $user->postcode }}</p>
                        <p>{{ $user->address }}</p>
                        <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="purchase__change-like">変更する</a>
                    </div>
                    @error('address')
                        <p class="purchase__error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 右側:購入画面 --}}
                <div class="purchase__summary">
                    <table class="purchase__table">
                        <tr>
                            <th>商品代金</th>
                            <td>¥{{ number_format($item->price) }}</td>
                        </tr>
                        <tr>
                            <th>お支払い方法</th>
                            <td>{{ request('payment_method') ?: '未設定' }}</td>
                        </tr>
                    </table>
                    <button type="submit" class="purchase__button">購入する</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection