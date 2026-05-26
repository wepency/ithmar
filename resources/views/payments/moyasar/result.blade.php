<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Result</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial; padding: 24px; }
        .box { max-width: 900px; margin: 0 auto; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; }
        .ok { color: #166534; }
        .bad { color: #b91c1c; }
        pre { background: #0b1020; color: #d1d5db; padding: 12px; border-radius: 10px; overflow:auto; }
    </style>
</head>
<body>
<div class="box">
    <h1 class="{{ $ok ? 'ok' : 'bad' }}">{{ $title }}</h1>
    <p>{{ $message }}</p>

    @if($payment)
        <h3>Payment (Fetched from API)</h3>
        <pre>{{ json_encode($payment, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
    @endif
</div>
</body>
</html>