@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/transaction_chat.css') }}">
@endsection

@section('main_class', 'main--transaction-chat')

@section('content')

<main class="transaction-chat transaction-chat--full">

    {{-- 左サイド：その他の取引 --}}
    <aside class="transaction-chat__sidebar">
        <div class="transaction-chat__sidebar-title">その他の取引</div>

        <div class="transaction-chat__sidebar-list">
            @foreach($transactions as $t)
                <a class="transaction-chat__sidebar-item {{ $t->id === $item->id ? 'is-active' : '' }}"
                   href="{{ route('transactions.chat', $t) }}">
                    <div class="transaction-chat__sidebar-item-name">{{ $t->name }}</div>

                    {{-- 未読バッジ（後でFN005やる前提で枠だけ用意） --}}
                    @php
                        $meId = auth()->id();
                        $isBuyer = ($t->buyer_id === $meId);
                        $lastReadAt = $isBuyer ? $t->buyer_last_read_at : $t->seller_last_read_at;
                        $unreadCount = $t->transactionMessages()
                            ->when($lastReadAt, fn($q) => $q->where('created_at', '>', $lastReadAt))
                            ->where('sender_id', '!=', $meId)
                            ->count();
                    @endphp

                    @if($unreadCount > 0)
                        <span class="transaction-chat__badge">{{ $unreadCount }}</span>
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
                <div class="transaction-chat__avatar"></div>
                <div class="transaction-chat__header-title">
                    「{{ $partner->name ?? '' }}」さんとの取引画面
                </div>
            </div>

            <button type="button" class="transaction-chat__complete-button">
                取引を完了する
            </button>
        </header>

        {{-- 商品情報 --}}
        <div class="transaction-chat__item-card">
            <div class="transaction-chat__item-image">
                <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}">
            </div>

            <div class="transaction-chat__item-info">
                <div class="transaction-chat__item-name">{{ $item->name }}</div>
                <div class="transaction-chat__item-price">¥{{ number_format($item->price) }}</div>
            </div>
        </div>

        <hr class="transaction-chat__divider">

        {{-- メッセージ一覧 --}}
        <div class="transaction-chat__messages">
            @foreach($messages as $m)
                @php $isMine = ($m->sender_id === auth()->id()); @endphp

                <div class="transaction-chat__message-row {{ $isMine ? 'is-mine' : 'is-other' }}">
                    {{-- 相手側：左にアイコン＋名前 --}}
                    @if(!$isMine)
                        <div class="transaction-chat__message-meta">
                            <div class="transaction-chat__avatar-sm"></div>
                            <div class="transaction-chat__username">{{ $m->sender->name ?? 'ユーザー名' }}</div>
                        </div>
                    @endif

                    <div class="transaction-chat__bubble-area">
                        {{-- 自分側：右に名前＋アイコン --}}
                        @if($isMine)
                            <div class="transaction-chat__message-meta is-right">
                                <div class="transaction-chat__username">{{ $m->sender->name ?? 'ユーザー名' }}</div>
                                <div class="transaction-chat__avatar-sm"></div>
                            </div>
                        @endif

                        <div class="transaction-chat__bubble">
                            @if($m->body)
                                {{ $m->body }}
                            @endif
                        </div>

                        {{-- 編集/削除（今は表示だけ。後でルート作る） --}}
                        @if($isMine)
                            <div class="transaction-chat__actions">
                                <a href="#" class="transaction-chat__action">編集</a>
                                <a href="#" class="transaction-chat__action">削除</a>
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

            <input class="transaction-chat__input"
                   type="text"
                   name="body"
                   placeholder="取引メッセージを記入してください"
                   value="{{ old('body') }}">

            <label class="transaction-chat__add-image">
                画像を追加
                <input type="file" name="image" hidden>
            </label>

            <button class="transaction-chat__send" type="submit" aria-label="送信">
                ▶
            </button>
        </form>

    </section>
</main>
@endsection
