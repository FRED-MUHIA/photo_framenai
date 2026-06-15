<?php

namespace Tests\Feature\Auth;

use App\Models\Order;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_authenticate_using_the_login_field(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'login' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_login_links_existing_guest_orders_by_email(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $order = Order::query()->create([
            'order_number' => 'NP-20260615-GUEST2',
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

        $this->post('/login', [
            'login' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $this->assertSame($user->id, $order->fresh()->user_id);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
