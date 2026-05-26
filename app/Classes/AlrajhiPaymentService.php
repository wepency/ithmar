<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;

class AlrajhiPaymentService
{
    protected string $id;
    protected string $password;
    protected string $resourceKey;
    protected string $endpoint;
    protected string $currencyCode;
    protected string $langId;

    public function __construct()
    {
        $this->id           = config('services.alrajhi.id');
        $this->password     = config('services.alrajhi.password');
        $this->resourceKey  = config('services.alrajhi.resource_key');
        $this->endpoint     = config('services.alrajhi.endpoint');
        $this->currencyCode = config('services.alrajhi.currency_code', '682');
        $this->langId       = config('services.alrajhi.langid', 'ar');
    }

    /**
     * إنشاء Payment Token وإرجاع Payment URL
     */
    public function createPayment(
        float $amount,
        string $trackId,
        string $responseUrl,
        string $errorUrl,
        string $customerIp,
        array $udfs = []
    ) {
        // plain trandata طبقًا للـ docs  [oai_citation:4‡ARB Payment Gateway REST API Integration Doc_V1.31.pdf](sediment://file_00000000df70724387b2ec6cd7e4ffbe)
        $plain = [
            'amt'          => number_format($amount, 2, '.', ''),
            'action'       => '1', // Purchase = 1  [oai_citation:5‡ARB Payment Gateway REST API Integration Doc_V1.31.pdf](sediment://file_00000000df70724387b2ec6cd7e4ffbe)
            'password'     => $this->password,
            'id'           => $this->id,
            'currencyCode' => $this->currencyCode,
            'trackId'      => $trackId,
            'responseURL'  => $responseUrl,
            'errorURL'     => $errorUrl,
            'langid'       => $this->langId,
        ];

        // UDFs لو عاوز تبعت بيانات إضافية (اختياري)
        $plain = array_merge($plain, $udfs);

        // الدوكيومنت بتقول إن trandata بيكون JSON Array واحد  [oai_citation:6‡ARB Payment Gateway REST API Integration Doc_V1.31.pdf](sediment://file_00000000df70724387b2ec6cd7e4ffbe)
        $plainJson = json_encode([$plain], JSON_UNESCAPED_SLASHES);

        // لازم نعمل URL-Encode قبل التشفير (مذكور في الـ docs)  [oai_citation:7‡ARB Payment Gateway REST API Integration Doc_V1.31.pdf](sediment://file_00000000df70724387b2ec6cd7e4ffbe)
        $encoded = urlencode($plainJson);

        $encryptedTrandata = $this->encryptAES($encoded, $this->resourceKey);

        // body الخارجي اللي هنبعته للـ Payment Token API
        $body = [[
            'id'         => $this->id,
            'trandata'   => $encryptedTrandata,
            'responseURL'=> $responseUrl,
            'errorURL'   => $errorUrl,
        ]];

        $response = Http::withHeaders([
                'Content-Type'   => 'application/json',
                // لازم أول IP يكون IP العميل الحقيقي  [oai_citation:8‡ARB Payment Gateway REST API Integration Doc_V1.31.pdf](sediment://file_00000000df70724387b2ec6cd7e4ffbe)
                'X-FORWARDED-FOR'=> $customerIp,
            ])
            ->post($this->endpoint, $body);

        $json = $response->json();

        if (!is_array($json) || !isset($json[0]['status'])) {
            throw new \RuntimeException('Invalid response from Alrajhi PG');
        }

        $status = (string) $json[0]['status'];

        if ($status !== '1') {
            $error     = $json[0]['error'] ?? 'UNKNOWN';
            $errorText = $json[0]['errorText'] ?? 'Unknown error';
            throw new \RuntimeException("Alrajhi PG error {$error}: {$errorText}");
        }

        // result = "paymentId:paymentPageUrl"  [oai_citation:9‡ARB Payment Gateway REST API Integration Doc_V1.31.pdf](sediment://file_00000000df70724387b2ec6cd7e4ffbe)
        [$paymentId, $paymentUrl] = explode(':', $json[0]['result'], 2);

        return [
            'payment_id'  => $paymentId,
            'payment_url' => $paymentUrl, // غالبًا فيه الـ query جاهزة أو هتزود ?PaymentID=
        ];
    }

    /**
     * فك تشفير trandata المرسل في الـ callback
     */
    public function decryptTrandata(string $encryptedTrandata): array
    {
        // بعد فك التشفير لازم نعمل URL-decode حسب الـ docs  [oai_citation:10‡ARB Payment Gateway REST API Integration Doc_V1.31.pdf](sediment://file_00000000df70724387b2ec6cd7e4ffbe)
        $decrypted = $this->decryptAES(urldecode($encryptedTrandata), $this->resourceKey);

        $decrypted = urldecode($decrypted);

        // عادةً بيبقى JSON Array فيه عنصر واحد، نفس شكل الـ plain trandata و response  [oai_citation:11‡ARB Payment Gateway REST API Integration Doc_V1.31.pdf](sediment://file_00000000df70724387b2ec6cd7e4ffbe)
        $data = json_decode($decrypted, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Cannot decode PG trandata JSON: '.json_last_error_msg());
        }

        return $data;
    }

    /* ================= Encryption helpers من الـ docs ================= */

    // Encryption sample من الملف  [oai_citation:12‡ARB Payment Gateway REST API Integration Doc_V1.31.pdf](sediment://file_00000000df70724387b2ec6cd7e4ffbe)
    protected function encryptAES(string $str, string $key): string
    {
        $str = $this->pkcs5_pad($str);
        $iv  = "PGKEYENCDECIVSPC";

        $encrypted = openssl_encrypt(
            $str,
            'aes-256-cbc',
            $key,
            OPENSSL_ZERO_PADDING,
            $iv
        );

        $encrypted = base64_decode($encrypted);
        $encrypted = unpack('C*', ($encrypted));
        $encrypted = $this->byteArray2Hex($encrypted);
        $encrypted = urlencode($encrypted);

        return $encrypted;
    }

    // Decryption sample من الملف  [oai_citation:13‡ARB Payment Gateway REST API Integration Doc_V1.31.pdf](sediment://file_00000000df70724387b2ec6cd7e4ffbe)
    protected function decryptAES(string $code, string $key): string
    {
        $code = $this->hex2ByteArray(trim($code));
        $code = $this->byteArray2String($code);
        $iv   = "PGKEYENCDECIVSPC";

        $code      = base64_encode($code);
        $decrypted = openssl_decrypt(
            $code,
            'AES-256-CBC',
            $key,
            OPENSSL_ZERO_PADDING,
            $iv
        );

        return $this->pkcs5_unpad($decrypted);
    }

    /* ================= Low-level helpers ================= */

    protected function pkcs5_pad(string $text, int $blocksize = 16): string
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    protected function pkcs5_unpad(string $text): string
    {
        $pad = ord($text[strlen($text) - 1]);
        if ($pad > strlen($text)) {
            return $text;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return $text;
        }
        return substr($text, 0, -1 * $pad);
    }

    protected function byteArray2Hex(array $bytes): string
    {
        $ret = '';
        foreach ($bytes as $b) {
            $ret .= sprintf('%02X', $b);
        }
        return $ret;
    }

    protected function hex2ByteArray(string $hex): array
    {
        $hex = preg_replace('/[^0-9A-Fa-f]/', '', $hex);
        $bytes = [];
        for ($i = 0; $i < strlen($hex); $i += 2) {
            $bytes[] = hexdec(substr($hex, $i, 2));
        }
        return $bytes;
    }

    protected function byteArray2String(array $bytes): string
    {
        $str = '';
        foreach ($bytes as $b) {
            $str .= chr($b);
        }
        return $str;
    }
}