<?php

namespace Tests\Feature;

use App\Models\Frame;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FrameCustomizerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customizer_is_reachable_from_the_storefront_and_shows_active_frames(): void
    {
        Frame::query()->create([
            'name' => 'Oak Classic',
            'slug' => 'oak-classic',
            'asset_type' => 'overlay',
            'base_price' => 1200,
            'min_width_cm' => 2,
            'max_width_cm' => 8,
            'orientations' => ['portrait', 'landscape'],
            'is_active' => true,
        ]);
        Frame::query()->create([
            'name' => 'Hidden Frame',
            'slug' => 'hidden-frame',
            'asset_type' => 'overlay',
            'base_price' => 900,
            'min_width_cm' => 2,
            'max_width_cm' => 8,
            'orientations' => ['portrait'],
            'is_active' => false,
        ]);

        $this->get(route('frames.customize'))
            ->assertOk()
            ->assertSee('Customize Your Picture Frame')
            ->assertSee('Customize a Frame')
            ->assertSee('Oak Classic')
            ->assertDontSee('Hidden Frame');
    }

    public function test_server_prices_custom_frame_and_keeps_customer_upload_private_in_cart(): void
    {
        Storage::fake('local');
        $frame = Frame::query()->create([
            'name' => 'Oak Classic',
            'slug' => 'oak-classic-order',
            'sku' => 'FRAME-OAK-1',
            'asset_type' => 'overlay',
            'base_price' => 1000,
            'min_width_cm' => 2,
            'max_width_cm' => 8,
            'width_price_per_cm' => 50,
            'orientations' => ['portrait', 'landscape'],
            'sizes' => [['name' => '10 x 12 cm', 'width_cm' => 10, 'height_cm' => 12, 'price' => 200]],
            'mats' => [['name' => 'Ivory', 'color' => '#f2eee5', 'price' => 150]],
            'accessories' => [['id' => 'glass', 'name' => 'Clear glass', 'price' => 300]],
            'inventory' => 10,
            'is_active' => true,
        ]);

        $this->post(route('frames.customize.add'), [
            'frame_id' => $frame->id,
            'orientation' => 'portrait',
            'frame_width_cm' => 4,
            'frame_rotation' => 0,
            'image_rotation' => 0,
            'zoom' => 1,
            'pan_x' => 0,
            'pan_y' => 0,
            'size_name' => '10 x 12 cm',
            'unit' => 'cm',
            'width_cm' => 10,
            'height_cm' => 12,
            'mat_color' => '#f2eee5',
            'quantity' => 2,
            'accessories' => ['glass'],
            'photo' => UploadedFile::fake()->image('customer-photo.jpg', 640, 800),
            // Browser-submitted prices are deliberately ignored by the server.
            'unit_price' => 0.01,
        ])->assertRedirect(route('cart.index'));

        $item = session('print_cart')[0];
        $this->assertSame(1750.0, $item['unit_price']);
        $this->assertSame(3500.0, $item['line_total']);
        $this->assertSame('Oak Classic', $item['frame_customization']['frame_name']);
        $this->assertSame(10.0, $item['frame_customization']['width_cm']);
        $this->assertTrue(Storage::disk('local')->exists($item['frame_customization']['original_image_path']));
        $this->assertStringStartsWith('private/frame-orders/', $item['frame_customization']['original_image_path']);

        $lineKey = $item['line_key'];
        $this->get(route('frames.customize.edit', $lineKey))
            ->assertOk()
            ->assertSee('Oak Classic');
        $this->get(route('frames.customize.photo', $lineKey))->assertOk();
        $this->post(route('frames.customize.add'), [
            'frame_id' => $frame->id,
            'existing_line_key' => $lineKey,
            'orientation' => 'portrait',
            'frame_width_cm' => 4,
            'frame_rotation' => 0,
            'image_rotation' => 0,
            'zoom' => 1,
            'pan_x' => 0,
            'pan_y' => 0,
            'size_name' => '10 x 12 cm',
            'unit' => 'cm',
            'width_cm' => 10,
            'height_cm' => 12,
            'mat_color' => '#f2eee5',
            'quantity' => 1,
            'accessories' => ['glass'],
        ])->assertRedirect(route('cart.index'));

        $updated = session('print_cart');
        $this->assertCount(1, $updated);
        $this->assertSame($lineKey, $updated[0]['line_key']);
        $this->assertSame($item['frame_customization']['original_image_path'], $updated[0]['frame_customization']['original_image_path']);
        $this->assertSame(1, $updated[0]['quantity']);

        Mail::fake();
        $this->post(route('checkout.store'), [
            'name' => 'Frame Customer',
            'email' => 'frame-customer@example.com',
            'phone' => '0700000000',
            'shipping_address_line1' => 'Nairobi',
            'shipping_country' => 'Kenya',
            'shipping_option' => 'not_necessary',
            'payment_method' => 'mpesa',
        ])->assertRedirect();

        $orderedFrame = OrderItem::query()->firstOrFail();
        $this->assertSame('Oak Classic', data_get($orderedFrame->configuration, 'frame_customization.frame_name'));
        $this->assertSame($item['frame_customization']['original_image_path'], data_get($orderedFrame->configuration, 'frame_customization.original_image_path'));
        $this->assertSame('cm', data_get($orderedFrame->configuration, 'frame_customization.unit'));
        $this->assertTrue(data_get($orderedFrame->configuration, 'frame_customization.inventory_reserved'));
        $this->assertDatabaseHas('frames', ['id' => $frame->id, 'inventory' => 9]);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->patch(route('admin.orders.status', $orderedFrame->order), [
            'status' => 'cancelled',
            'payment_status' => 'unpaid',
        ])->assertRedirect();
        $orderedFrame->refresh();
        $this->assertFalse((bool) data_get($orderedFrame->configuration, 'frame_customization.inventory_reserved'));
        $this->assertDatabaseHas('frames', ['id' => $frame->id, 'inventory' => 10]);
    }
}
