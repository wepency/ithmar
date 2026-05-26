<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Moyasar Checkout</title>

    <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.7.3/moyasar.css" />
    <script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
    <script src="https://cdn.moyasar.com/mpf/1.7.3/moyasar.js"></script>

    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial;
            margin: 0;
            padding: 24px;
        }

        .wrap {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 24px;
            align-items: start;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            background: #fff;
        }

        .title {
            font-size: 18px;
            margin: 0 0 12px;
        }

        .muted {
            color: #6b7280;
            font-size: 13px;
        }

        @media (max-width: 980px) {
            .wrap {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <h1 class="title">Order Summary</h1>
            <div class="muted">Reference: {{ $order['reference'] }}</div>
            <div style="margin-top:10px;font-size:22px;">
                {{ number_format($order['amount_major'], 2) }} {{ $order['currency'] }}
            </div>

            @if (session('error'))
                <div style="margin-top:12px;color:#b91c1c;">{{ session('error') }}</div>
            @endif
        </div>

        <div class="card">
            <h2 class="title">Pay securely</h2>

            <div class="mysr-form"></div>

            <div class="muted" style="margin-top:10px;">
                You’ll be redirected to 3D Secure if required, then returned to this site.
            </div>
        </div>
    </div>

    <script>
        // Convert to minor units (halalas) as Moyasar requires amount in smallest unit.  [oai_citation:8‡Moyasar](https://docs.moyasar.com/guides/card-payments/basic-integration)
        const amountMinor = Math.round({{ (float) $order['amount_major'] }} * 100);

        function postJSON(url, payload) {
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            }).then(async (r) => {
                const data = await r.json().catch(() => ({}));
                if (!r.ok) throw new Error(data.message || 'Request failed');
                return data;
            });
        }

        Moyasar.init({
            element: '.mysr-form',
            amount: amountMinor,
            currency: '{{ $order['currency'] }}',
            description: @json($order['description']),
            publishable_api_key: '{{ $publishableKey }}',
            callback_url: '{{ $callbackUrl }}',
            methods: ['creditcard'],
            supported_networks: ['visa', 'mastercard', 'mada'],

            on_completed: async function(payment) {
                await postJSON('{{ route('payments.moyasar.save') }}', {
                    payment_id: payment.id,
                    order_id: '{{ $order['id'] }}',
                    amount_minor: amountMinor,
                    currency: '{{ $order['currency'] }}',
                });
            },

            on_failure: function(error) {
                console.error('Moyasar error:', error);
            },
        });
    </script>
</body>

</html>
