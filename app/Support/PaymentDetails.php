<?php

namespace App\Support;

use App\Models\Order;

class PaymentDetails
{
    public static function forOrder(Order $order): array
    {
        $method = strtolower((string) $order->payment_method);
        $methodLabel = match ($method) {
            'mpesa' => 'M-Pesa',
            'cod' => 'Pay on Delivery',
            'stripe' => 'Card / Stripe',
            'paypal' => 'PayPal',
            default => str($method ?: 'payment')->replace('_', ' ')->title()->toString(),
        };

        $instructions = match ($method) {
            'mpesa' => [
                'Paybill: ' . config('shop.mpesa_paybill', 'Pending setup'),
                'Account: ' . $order->order_number,
                'Amount: ' . ($order->currency ?: config('shop.currency', 'KES')) . ' ' . number_format((float) $order->total_amount, 2),
            ],
            'cod' => [
                'Payment will be collected on delivery or pickup.',
                'Please quote your order number: ' . $order->order_number,
            ],
            'stripe' => [
                'Pay securely by card using the payment link or checkout instructions provided by our team.',
                'Reference: ' . $order->order_number,
            ],
            'paypal' => [
                'Pay with PayPal using the payment instructions provided by our team.',
                'Reference: ' . $order->order_number,
            ],
            default => [
                'Reference: ' . $order->order_number,
            ],
        };

        return [
            'method' => $methodLabel,
            'status' => str($order->payment_status ?: 'unpaid')->replace('_', ' ')->title()->toString(),
            'instructions' => $instructions,
        ];
    }
}
