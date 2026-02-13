{{ $seller->name }} さん<br>
<br>
「{{ $item->name }}」の取引が完了しました。<br>
購入者：{{ $buyer->name }}<br>
<br>
取引画面はこちら：<br>
{{ route('transactions.chat', $item) }}<br>
<br>
-----------------------<br>
<br>
このメールは自動送信です。
