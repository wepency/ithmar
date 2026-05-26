<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class MoyasarService
{
    public function __construct(
        private readonly string $secretKey,
        private readonly string $baseUrl = 'https://api.moyasar.com/v1',
    ) {}

    public static function make(): self
    {
        $key = (string) config('services.moyasar.secret_key', env('MOYASAR_SECRET_KEY'));
        if ($key === '') {
            throw new \RuntimeException('MOYASAR_SECRET_KEY is missing.');
        }

        return new self($key);
    }

    /**
     * Fetch payment details by id (server-to-server).
     * Uses HTTP Basic Auth: username=secretKey, password=empty.  [oai_citation:2‡Moyasar](https://docs.moyasar.com/api/authentication)
     */
    public function fetchPayment(string $paymentId): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->acceptJson()
            ->get($this->baseUrl . '/payments/' . $paymentId);

        if (!$response->successful()) {
            throw new \RuntimeException('Moyasar fetchPayment failed: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Create an invoice (server-to-server) to get a payment URL.
     */
    public function createInvoice(int $amountMinor, string $currency, string $description, string $callbackUrl, array $metadata = []): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->acceptJson()
            ->post($this->baseUrl . '/invoices', [
                'amount' => $amountMinor,
                'currency' => $currency,
                'description' => $description,
                'success_url' => $callbackUrl,
                'back_url' => $callbackUrl,
                'callback_url' => $callbackUrl,
                'metadata' => $metadata,
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException('Moyasar createInvoice failed: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Fetch invoice details by id.
     */
    public function fetchInvoice(string $invoiceId): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->acceptJson()
            ->get($this->baseUrl . '/invoices/' . $invoiceId);

        if (!$response->successful()) {
            throw new \RuntimeException('Moyasar fetchInvoice failed: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Strict verification recommended by Moyasar:
     * verify status + amount + currency before fulfilling order.  [oai_citation:3‡Moyasar](https://docs.moyasar.com/guides/card-payments/basic-integration)
     */
    public function verify(array $payment, int $expectedAmountMinor, string $expectedCurrency): array
    {
        $status = (string) Arr::get($payment, 'status');
        $amount = (int) Arr::get($payment, 'amount');
        $currency = (string) Arr::get($payment, 'currency');

        $okStatus = in_array($status, ['paid'], true); // keep strict for card payments
        // If you use manual capture you may treat 'authorized' differently.

        return [
            'ok' => $okStatus && $amount === $expectedAmountMinor && strtoupper($currency) === strtoupper($expectedCurrency),
            'status' => $status,
            'amount' => $amount,
            'currency' => $currency,
        ];
    }
}
