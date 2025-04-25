<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AdamsPayService
{
    protected $merchantId;
    protected $apiKey;
    protected $secretKey;

    public function __construct()
    {
        $this->merchantId = config('services.adamspay.merchant_id');
        $this->apiKey = config('services.adamspay.api_key');
        $this->secretKey = config('services.adamspay.secret_key');
    }

    public function generarLinkDePago($orden)
    {
        $referencia = strtoupper(Str::random(10));

        $payload = [
            'amount' => $orden->total,
            'currency' => 'PYG',
            'description' => 'Pago de orden #' . $orden->id,
            'reference' => $referencia,
            'return_url' => url('/pagos/confirmacion'),
        ];

        $signature = hash_hmac('sha256', json_encode($payload), $this->secretKey);

        $response = Http::withHeaders([
            'X-AdamsPay-Signature' => $signature,
            'X-AdamsPay-API-Key' => $this->apiKey,
        ])->post('https://sandbox.adamspay.com/api/v1/charge', $payload);

        if ($response->failed()) {
            throw new \Exception('Error al generar link de pago: ' . $response->body());
        }

        return [
            'referencia_pago' => $referencia,
            'link_pago' => $response->json()['payment_url'] ?? null,
            'respuesta' => $response->json(),
        ];
    }
}
