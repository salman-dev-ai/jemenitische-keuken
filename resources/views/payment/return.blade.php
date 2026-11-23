@php
    $t = [
        'paid' => ['nl' => 'Betaling gelukt! Bedankt voor uw bestelling.', 'en' => 'Payment received! Thank you for your order.', 'ar' => 'تم الدفع بنجاح! شكراً لطلبك.'],
        'pending' => ['nl' => 'Wij verwerken uw betaling… Even geduld.', 'en' => 'We are confirming your payment… please wait.', 'ar' => 'نتحقق من الدفع… يرجى الانتظار.'],
        'failed' => ['nl' => 'De betaling is niet gelukt of geannuleerd.', 'en' => 'The payment failed or was cancelled.', 'ar' => 'فشل الدفع أو أُلغي.'],
        'order' => ['nl' => 'Bestelnummer', 'en' => 'Order number', 'ar' => 'رقم الطلب'],
        'back' => ['nl' => 'Terug naar de website', 'en' => 'Back to the website', 'ar' => 'العودة إلى الموقع'],
    ];
    $l = in_array(app()->getLocale(), ['nl', 'en', 'ar'], true) ? app()->getLocale() : 'en';
@endphp
<!doctype html>
<html lang="{{ $l }}" dir="{{ $l === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $t['order'][$l] }} {{ $order->order_number }}</title>
    @if ($state === 'pending')
        <meta http-equiv="refresh" content="4">
    @endif
    <style>
        body{font-family:system-ui,sans-serif;background:#faf4ed;margin:0;display:grid;place-items:center;min-height:100vh;color:#2c0d0a}
        .card{background:#fff;border-radius:16px;padding:32px;max-width:420px;text-align:center;box-shadow:0 8px 30px rgba(0,0,0,.08)}
        .ok{color:#059669}.bad{color:#e11d48}.wait{color:#e07513}
        a{display:inline-block;margin-top:20px;padding:12px 20px;border-radius:12px;background:#e07513;color:#fff;text-decoration:none;font-weight:700}
    </style>
</head>
<body>
    <div class="card">
        <h2 class="{{ $state === 'paid' ? 'ok' : ($state === 'failed' ? 'bad' : 'wait') }}">{{ $t[$state][$l] }}</h2>
        <p>{{ $t['order'][$l] }}: <strong dir="ltr">{{ $order->order_number }}</strong></p>
        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ $t['back'][$l] }}</a>
    </div>
</body>
</html>