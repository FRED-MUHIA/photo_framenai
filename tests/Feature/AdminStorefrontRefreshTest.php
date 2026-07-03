<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminStorefrontRefreshTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_write_requests_refresh_storefront_caches(): void
    {
        Route::middleware(['web', 'auth', 'admin', 'admin.refresh_storefront'])
            ->post('/admin/storefront-refresh-probe', fn () => redirect('/admin'));

        $admin = User::factory()->create(['role' => 'admin']);

        Artisan::spy();

        $this
            ->actingAs($admin)
            ->post('/admin/storefront-refresh-probe')
            ->assertRedirect('/admin');

        Artisan::shouldHaveReceived('call')->with('view:clear')->once();
        Artisan::shouldHaveReceived('call')->with('cache:clear')->once();
    }

    public function test_admin_read_requests_do_not_refresh_storefront_caches(): void
    {
        Route::middleware(['web', 'auth', 'admin', 'admin.refresh_storefront'])
            ->get('/admin/storefront-refresh-probe', fn () => response('ok'));

        $admin = User::factory()->create(['role' => 'admin']);

        Artisan::spy();

        $this
            ->actingAs($admin)
            ->get('/admin/storefront-refresh-probe')
            ->assertOk();

        Artisan::shouldNotHaveReceived('call');
    }
}
