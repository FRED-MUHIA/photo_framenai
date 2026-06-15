<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_customer_sees_their_linked_order_on_the_dashboard(): void
    {
        Order::query()->create([
            'order_number' => 'NP-20260615-DASH01',
            'customer_name' => 'Test User',
            'customer_email' => 'test@example.com',
            'status' => 'pending',
            'payment_method' => 'mpesa',
            'payment_status' => 'unpaid',
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

        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('NP-20260615-DASH01')
            ->assertSee('KES 1,000.00');
    }
}
