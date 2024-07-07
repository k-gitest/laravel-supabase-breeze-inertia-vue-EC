<!DOCTYPE html>
<html>
<head>
    <title>注文確認</title>
</head>
<body>
    <h1>注文が完了しました</h1>
    <p>ご注文ありがとうございます！</p>
    <p>注文ID: {{ $order->id }}</p>
    <p>合計金額: {{ $order->total_amount / 100 }} {{ $order->currency }}</p>
</body>
</html>
