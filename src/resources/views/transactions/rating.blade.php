@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/transaction_rating.css') }}">
@endsection

@section('main_class', 'main--transaction-chat')

@section('content')
<main class="transaction-rating">

    <div class="transaction-rating__panel">
        <div class="transaction-rating__title">取引が完了しました。</div>
        <div class="transaction-rating__subtitle">
            今回の取引相手「{{ $transactionPartner->name }}」さんはどうでしたか？
        </div>

        <form method="POST" action="{{ route('transactions.rating.store', $item) }}">
            @csrf

            @if($errors->any())
                <div class="transaction-rating__errors">
                    @foreach($errors->all() as $error)
                        <p class="transaction-rating__error">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="star-rating" aria-label="評価">
                @for($starValue = 5; $starValue >= 1; $starValue--)
                    @php $inputId = 'rating-' . $starValue; @endphp

                    <input
                        type="radio"
                        name="rating"
                        value="{{ $starValue }}"
                        id="{{ $inputId }}"
                        class="star-rating__input"
                        {{ (old('rating', $existingRating) == $starValue) ? 'checked' : '' }}
                    >
                    <label for="{{ $inputId }}" class="star-rating__label" aria-label="{{ $starValue }}つ星">★</label>
                @endfor
            </div>

            <div class="transaction-rating__actions">
                <button type="submit" class="transaction-rating__submit">送信する</button>
            </div>
        </form>
    </div>

</main>
@endsection
