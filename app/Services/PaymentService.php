<?php

namespace App\Services;

use Illuminate\Support\Str;

class PaymentService
{
    public function initiate(string $method, float $amount, array $payload = []): array
    {
        return [
            'method' => $method,
            'amount' => $amount,
            'currency' => config('shop.currency', 'KES'),
            'status' => 'pending',
            'transaction_ref' => strtoupper($method) . '-' . Str::upper(Str::random(10)),
            'payload' => $payload,
            // Placeholder integration point: Stripe/PayPal/M-Pesa API calls.
        ];
    }
}
