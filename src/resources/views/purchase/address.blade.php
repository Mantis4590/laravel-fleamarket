@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address">
    <h2 class="address__title">住所の変更</h2>

    <form action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" method="POST" class="address__form">
        @csrf
        <div class="address__group">
            <label for="postcode" class="address__label">郵便番号</label>
            <input type="text" name="postcode" id="postcode" class="address__input" value="{{ old('postcode', $user->postcode) }}">
            @error('postcode')
                <p class="address__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="address__group">
            <label for="address" class="address__label">住所</label>
            <input type="text" name="address" id="address" class="address__input" value="{{ old('address', $user->address) }}">
            @error('address')
                <p class="address__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="address__group">
            <label for="building" class="address__label">建物名</label>
            <input type="text" name="building" id="building" class="address__input" value="{{ old('building', $user->building) }}">
        </div>
        <button type="submit" class="address__button">更新する</button>

    </form>
</div>
@endsection
