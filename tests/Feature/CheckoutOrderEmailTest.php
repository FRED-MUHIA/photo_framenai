<?php

namespace Tests\Feature;

use App\Mail\InvoiceReadyMail;
use App\Mail\NewOrderPlacedMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutOrderEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_orders_are_emailed_to_the_shop_inbox(): void
    {
        Mail::fake();

        $this->withSession([
            'print_cart' => [
                [
                    'product_id' => null,
                    'product_name' => 'Business Cards',
                    'quantity' => 1,
                    'unit_price' => 1000,
                    'line_total' => 1000,
                    'delivery_days' => 3,
                ],
            ],
        ])->post(route('checkout.store'), [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'phone' => '0700000000',
            'shipping_address_line1' => 'Nairobi',
            'shipping_country' => 'Kenya',
            'shipping_option' => 'not_necessary',
            'payment_method' => 'mpesa',
        ])->assertRedirect();

        Mail::assertSent(InvoiceReadyMail::class, function ($mail) {
            $mail->build();

            return $mail->hasTo('customer@example.com')
                && $mail->hasFrom('info@photoframes.co.ke')
                && $mail->hasReplyTo('info@photoframes.co.ke')
                && empty($mail->attachments)
                && empty($mail->rawAttachments)
                && empty($mail->diskAttachments);
        });

        Mail::assertSent(NewOrderPlacedMail::class, function ($mail) {
            $mail->build();

            return $mail->hasTo('info@photoframes.co.ke')
                && $mail->hasFrom('info@photoframes.co.ke')
                && $mail->hasReplyTo('info@photoframes.co.ke');
        });
    }

    public function test_order_status_emails_use_the_shop_inbox_identity(): void
    {
        $order = Order::query()->create([
            'order_number' => 'NP-20260615-MAIL01',
            'customer_name' => 'Test Customer',
            'customer_email' => 'customer@example.com',
            'status' => 'processing',
            'payment_method' => 'mpesa',
            'payment_status' => 'paid',
            'shipping_option' => 'standard',
            'shipping_address_line1' => 'Nairobi',
            'shipping_city' => 'Nairobi',
            'shipping_country' => 'Kenya',
            'subtotal' => 1000,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => 1000,
            'currency' => 'KES',
        ]);

        $mail = new OrderStatusUpdatedMail($order, [
            ['label' => 'Order Status', 'from' => 'Pending', 'to' => 'Processing'],
        ]);

        $mail->build();

        $this->assertTrue($mail->hasFrom('info@photoframes.co.ke'));
        $this->assertTrue($mail->hasReplyTo('info@photoframes.co.ke'));
    }
}
