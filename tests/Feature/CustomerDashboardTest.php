<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
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

    public function test_customer_dashboard_shows_order_details_and_account_actions(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $order = Order::query()->create([
            'order_number' => 'NP-20260615-DASH02',
            'user_id' => $user->id,
            'customer_name' => 'Test User',
            'customer_email' => 'test@example.com',
            'status' => 'processing',
            'payment_method' => 'mpesa',
            'payment_status' => 'paid',
            'shipping_option' => 'standard',
            'shipping_address_line1' => 'Nairobi CBD',
            'shipping_city' => 'Nairobi',
            'shipping_country' => 'Kenya',
            'subtotal' => 2500,
            'shipping_amount' => 400,
            'discount_amount' => 0,
            'total_amount' => 2900,
            'currency' => 'KES',
            'notes' => 'Call before delivery.',
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_name' => 'A3 Posters',
            'sku' => 'POSTER-A3',
            'configuration' => [
                'selected_values' => [
                    ['group_code' => 'paper', 'label' => 'Gloss'],
                ],
                'custom_fields' => [
                    ['label' => 'Headline', 'value' => 'Grand Opening'],
                ],
            ],
            'quantity' => 2,
            'unit_price' => 1250,
            'line_total' => 2500,
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('NP-20260615-DASH02')
            ->assertSee('Order details')
            ->assertSee('A3 Posters')
            ->assertSee('Quantity: 2')
            ->assertSee('PAPER: Gloss')
            ->assertSee('Headline: Grand Opening')
            ->assertSee('Nairobi CBD')
            ->assertSee('Call before delivery.')
            ->assertSee('Log out')
            ->assertSee('Delete account');
    }

    public function test_customer_can_log_out_from_the_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/dashboard')
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_customer_can_delete_their_account_from_the_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/dashboard')
            ->delete('/profile', [
                'password' => 'password',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }
}
