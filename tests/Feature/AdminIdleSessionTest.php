<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminIdleSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_admin_session_is_logged_out_on_admin_routes(): void
    {
        config(['session.admin_idle_timeout' => 1]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->withSession(['admin_last_activity_at' => time() - 61])
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Admin session expired due to inactivity. Please log in again.');

        $this->assertGuest();
    }

    public function test_expired_admin_session_is_logged_out_on_public_web_requests(): void
    {
        config(['session.admin_idle_timeout' => 1]);

        Route::middleware('web')->get('/idle-admin-probe', function () {
            return auth()->check() ? 'authenticated' : 'guest';
        });

        $admin = User::factory()->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->withSession(['admin_last_activity_at' => time() - 61])
            ->get('/idle-admin-probe')
            ->assertOk()
            ->assertSee('guest');

        $this->assertGuest();
    }

    public function test_admin_layout_includes_browser_idle_logout(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('admin-idle-logout-form', false)
            ->assertSee('np_admin_last_activity_at', false);
    }

    public function test_expired_admin_logout_post_does_not_fail_csrf(): void
    {
        config(['session.admin_idle_timeout' => 1]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->withSession(['admin_last_activity_at' => time() - 61])
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
