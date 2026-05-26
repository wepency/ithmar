<?php

namespace Tests\Unit;

use App\Services\Payment\MoyasarService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MoyasarServiceTest extends TestCase
{
    public function test_it_can_fetch_payment()
    {
        Http::fake([
            'api.moyasar.com/v1/payments/*' => Http::response([
                'id' => 'pay_123',
                'status' => 'paid',
                'amount' => 1000,
                'currency' => 'SAR',
            ], 200),
        ]);

        $service = new MoyasarService('sk_test_123');
        $payment = $service->fetchPayment('pay_123');

        $this->assertEquals('pay_123', $payment['id']);
        $this->assertEquals('paid', $payment['status']);
    }

    public function test_it_can_create_invoice()
    {
        Http::fake([
            'api.moyasar.com/v1/invoices' => Http::response([
                'id' => 'inv_123',
                'url' => 'https://api.moyasar.com/v1/invoices/inv_123/pay',
                'amount' => 1000,
            ], 201),
        ]);

        $service = new MoyasarService('sk_test_123');
        $invoice = $service->createInvoice(1000, 'SAR', 'Test Invoice', 'https://example.com/callback');

        $this->assertEquals('inv_123', $invoice['id']);
        $this->assertEquals('https://api.moyasar.com/v1/invoices/inv_123/pay', $invoice['url']);
    }

    public function test_it_can_verify_payment()
    {
        $service = new MoyasarService('sk_test_123');
        
        $payment = [
            'status' => 'paid',
            'amount' => 1000,
            'currency' => 'SAR',
        ];

        $verification = $service->verify($payment, 1000, 'SAR');

        $this->assertTrue($verification['ok']);
        $this->assertEquals('paid', $verification['status']);
    }

    public function test_it_fails_verification_on_wrong_amount()
    {
        $service = new MoyasarService('sk_test_123');
        
        $payment = [
            'status' => 'paid',
            'amount' => 1000,
            'currency' => 'SAR',
        ];

        $verification = $service->verify($payment, 2000, 'SAR');

        $this->assertFalse($verification['ok']);
    }
}
