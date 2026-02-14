@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/transaction_chat.css') }}">

@section('header')
<header class="header header--simple">
    <div class="header__left">
        <a href="{{ route('home') }}">
            <img src="{{ asset('logo.svg') }}" alt="COACHTECHロゴ" class="header__logo">
        </a>
    </div>
</header>
@endsection

@endsection

@section('main_class', 'main--transaction-chat')

@section('content')

<main class="transaction-chat transaction-chat--full">

    {{-- 左サイド：その他の取引 --}}
    <aside class="transaction-chat__sidebar">
        <div class="transaction-chat__sidebar-title">その他の取引</div>

        <div class="transaction-chat__sidebar-list">
            @foreach($transactions as $transactionItem)
                @continue($transactionItem->id === $item->id)
                <a class="transaction-chat__sidebar-item" href="{{ route('transactions.chat', $transactionItem) }}">
                    <div class="transaction-chat__sidebar-item-name">
                    {{ $transactionItem->name }}
                    </div>

                    {{-- 未読バッジ（後でFN005やる前提で枠だけ用意） --}}
                        @php
                            $currentUserId = auth()->id();

                            $isCurrentUserBuyer = ($transactionItem->buyer_id === $currentUserId);

                            $currentUserLastReadAt = $isCurrentUserBuyer
                            ? $transactionItem->buyer_last_read_at
                            : $transactionItem->seller_last_read_at;

                        $unreadMessageCount = $transactionItem->transactionMessages()
                            ->when(
                                $currentUserLastReadAt,
                                fn ($messageQuery) => $messageQuery->where('created_at', '>', $currentUserLastReadAt)
                            )
                        ->where('sender_id', '!=', $currentUserId)
                        ->count();
                    @endphp

                    @if($transactionItem->id !== $item->id && $unreadMessageCount > 0)
                        <span class="transaction-chat__badge">{{ $unreadMessageCount }}</span>
                    @endif
                </a>
            @endforeach
        </div>

    </aside>

    {{-- 右：メイン --}}
    <section class="transaction-chat__main">

        {{-- 上部ヘッダー --}}
        <header class="transaction-chat__header">
            @php
                $meId = auth()->id();
                $partnerName = ($item->buyer_id === $meId)
                    ? optional($item->seller)->name
                    : optional($item->buyer)->name;
            @endphp

            <div class="transaction-chat__header-left">
                <div class="transaction-chat__avatar">
                    @if($partner && $partner->profile_image)
                        <img src="{{ asset('storage/' . $partner->profile_image) }}" alt="{{ $partner->name }}">
                    @endif
                </div>

                <div class="transaction-chat__header-title">
                    「{{ $partner->name ?? '' }}」さんとの取引画面
                </div>
            </div>

            @php
                $currentUserId = auth()->id();
                $isBuyer = ($currentUserId === $item->buyer_id);
            @endphp

            @if($isBuyer && empty($myRating))
                <a href="{{ route('transactions.chat', ['item' => $item, 'showRating' => 1]) }}"
                class="transaction-chat__complete-button">
                    取引を完了する
                </a>
            @endif

        </header>

        {{-- 商品情報 --}}
        <div class="transaction-chat__item-card">
            <div class="transaction-chat__item-image">
                <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}">
            </div>

            <div class="transaction-chat__item-info">
                <div class="transaction-chat__item-name">{{ $item->name }}</div>
                <div class="transaction-chat__item-price">¥{{ number_format($item->price) }}</div>
            </div>
        </div>

        <hr class="transaction-chat__divider">

        {{-- メッセージ一覧 --}}
        <div class="transaction-chat__messages">
            @foreach($messages as $message)
                @php $isMine = ($message->sender_id === auth()->id()); @endphp

                <div class="transaction-chat__message-row {{ $isMine ? 'is-mine' : 'is-other' }}">
                    {{-- 相手側：左にアイコン＋名前 --}}
                    @if(!$isMine)
                        <div class="transaction-chat__message-meta">
                            <div class="transaction-chat__avatar-sm">
                                @if($message->sender && $message->sender->profile_image)
                                    <img src="{{ asset('storage/' . $message->sender->profile_image) }}" alt="{{ $message->sender->name }}">
                                @endif
                            </div>

                            <div class="transaction-chat__username">{{ $message->sender->name ?? 'ユーザー名' }}</div>
                        </div>
                    @endif

                    <div class="transaction-chat__bubble-area">
                        {{-- 自分側：右に名前＋アイコン --}}
                        @if($isMine)
                            <div class="transaction-chat__message-meta is-right">
                                <div class="transaction-chat__username">{{ $message->sender->name ?? 'ユーザー名' }}</div>
                                <div class="transaction-chat__avatar-sm">
                                    @if($message->sender && $message->sender->profile_image)
                                        <img src="{{ asset('storage/' . $message->sender->profile_image) }}" alt="{{ $message->sender->name }}">
                                    @endif
                                </div>

                            </div>
                        @endif

                        <div class="transaction-chat__bubble">
                            @if($message->body)
                                <div class="transaction-chat__text">
                                    {{ $message->body }}
                                </div>
                            @endif

                            @if($message->image_path)
                                <div class="transaction-chat__image">
                                    <img src="{{ asset('storage/' . $message->image_path) }}" alt="送信画像">
                                </div>
                            @endif
                        </div>

                        {{-- 編集/削除 --}}
                        @if($isMine)
                            <div class="transaction-chat__actions">
                                <details class="transaction-chat__edit">
                                    <summary class="transaction-chat__action">編集</summary>

                                    <form method="POST" action="{{ route('transactions.messages.update', [$item, $message]) }}" class="transaction-chat__edit-form">
                                        @csrf
                                        @method('PATCH')

                                        <input type="text" name="body" class="transaction-chat__input" value="{{ old('body', $message->body) }}">

                                        <button type="submit" class="transaction-chat__edit-submit">更新</button>
                                    </form>
                                </details>

                                {{-- 削除 --}}
                                <form method="POST" action="{{ route('transactions.messages.destroy', [$item, $message]) }}" class="transaction-chat__delete-form" onsubmit="return confirm('削除しますか？');">
                                    @csrf
                                    @method('DELETE')

                                    <button class="transaction-chat__action transaction-chat__action--danger">削除</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 入力欄 --}}
        <form class="transaction-chat__form" method="POST" action="{{ route('transactions.messages.store', $item) }}" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
                <div class="transaction-chat__form-errors">
                    @foreach($errors->all() as $error)
                        <p class="transaction-chat_form-error">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <input class="transaction-chat__input" id="transaction-chat-body" type="text" name="body" placeholder="取引メッセージを記入してください" value="{{ old('body') }}">

            <label class="transaction-chat__add-image">
                画像を追加
                <input type="file" name="image" hidden>
            </label>

            <button class="transaction-chat__send" type="submit" aria-label="送信">
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="22"
                    height="22"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M22 2L11 13"></path>
                    <path d="M22 2L15 22L11 13L2 9L22 2Z"></path>
                </svg>
            </button>
        </form>

    </section>
    </main>

    @if($isRatingModalOpen)
        <div class="transaction-chat__modal-overlay">
            <div class="transaction-chat__modal">
                <div class="transaction-chat__modal-title">取引が完了しました。</div>

                <div class="transaction-chat__modal-sub">
                    今回の取引相手はどうでしたか？
                </div>

                <form method="POST" action="{{ route('transactions.rating.store', $item) }}">
                    @csrf

                    @if($errors->any())
                        <div class="transaction-chat__form-errors">
                            @foreach($errors->all() as $error)
                                <p class="transaction-chat__form-error">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- 星（radio） --}}
                    <div class="transaction-chat__stars">
                        @for($star = 5; $star >= 1; $star--)
                            <input class="transaction-chat__star-input" type="radio" name="rating" id="star{{ $star }}" value="{{ $star }}" {{ (int)old('rating', $myRating) === $star ? 'checked' : '' }} >
                            <label class="transaction-chat__star-label" for="star{{ $star }}">★</label>
                        @endfor
                    </div>

                    <div class="transaction-chat__modal-actions">
                        <a href="{{ route('transactions.chat', $item) }}" class="transaction-chat__modal-cancel">
                            閉じる
                        </a>

                        <button type="submit" class="transaction-chat__modal-submit">
                            送信する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('transaction-chat-body');
        if (!input) return;

        // 取引ごとに保存キーを分ける（item_idで分ける）
        const key = `transaction_chat_body_item_{{ $item->id }}`;

        // 復元
        const saved = localStorage.getItem(key);
        if (saved && !input.value) {
            input.value = saved;
        }

        // 入力のたびに保存（本文のみ）
        input.addEventListener('input', () => {
            localStorage.setItem(key, input.value);
        });

        // 送信したらクリア（送信ボタン押した時）
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', () => {
                localStorage.removeItem(key);
            });
        }
    });
    </script>

@endsection
