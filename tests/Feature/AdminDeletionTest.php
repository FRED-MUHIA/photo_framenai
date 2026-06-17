<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_an_order_and_its_line_items(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->createOrder();
        $item = OrderItem::query()->create([
            'order_id' => $order->id,
            'product_name' => 'Business Cards',
            'sku' => 'BC-001',
            'quantity' => 1,
            'unit_price' => 1200,
            'line_total' => 1200,
        ]);
        $artwork = Artwork::query()->create([
            'order_item_id' => $item->id,
            'original_name' => 'cards.pdf',
            'file_path' => 'artworks/cards.pdf',
            'mime_type' => 'application/pdf',
            'extension' => 'pdf',
            'size_bytes' => 1000,
            'status' => 'pending',
        ]);

        $this->actingAs($admin);

        $this->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('Delete');

        $this->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee('Delete Order');

        $this
            ->from(route('admin.orders.show', $order))
            ->delete(route('admin.orders.destroy', $order))
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('status', 'Order NP-TEST-DELETE deleted.');

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('order_items', ['id' => $item->id]);
        $this->assertDatabaseHas('artworks', [
            'id' => $artwork->id,
            'order_item_id' => null,
        ]);
    }

    public function test_admin_can_delete_artwork_attached_to_an_order_item(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->createOrder('NP-TEST-ARTWORK');
        $artwork = Artwork::query()->create([
            'original_name' => 'flyer.pdf',
            'file_path' => 'artworks/flyer.pdf',
            'mime_type' => 'application/pdf',
            'extension' => 'pdf',
            'size_bytes' => 1000,
            'status' => 'pending',
        ]);
        Storage::disk('public')->put($artwork->file_path, 'test artwork');

        $item = OrderItem::query()->create([
            'order_id' => $order->id,
            'artwork_id' => $artwork->id,
            'product_name' => 'Flyers',
            'sku' => 'FLY-001',
            'quantity' => 1,
            'unit_price' => 1500,
            'line_total' => 1500,
        ]);

        $this->actingAs($admin);

        $this->get(route('admin.artworks.index'))
            ->assertOk()
            ->assertSee('Delete');

        $this->get(route('admin.artworks.show', $artwork))
            ->assertOk()
            ->assertSee('Delete Artwork');

        $this
            ->from(route('admin.artworks.show', $artwork))
            ->delete(route('admin.artworks.destroy', $artwork))
            ->assertRedirect(route('admin.artworks.index'))
            ->assertSessionHas('status', 'Artwork deleted.');

        $this->assertDatabaseMissing('artworks', ['id' => $artwork->id]);
        $this->assertDatabaseHas('order_items', [
            'id' => $item->id,
            'artwork_id' => null,
        ]);
        Storage::disk('public')->assertMissing('artworks/flyer.pdf');
    }

    private function createOrder(string $orderNumber = 'NP-TEST-DELETE'): Order
    {
        return Order::query()->create([
            'order_number' => $orderNumber,
            'customer_name' => 'Test Customer',
            'customer_email' => 'customer@example.com',
            'status' => 'pending',
            'payment_method' => 'mpesa',
            'payment_status' => 'unpaid',
            'shipping_option' => 'standard',
            'shipping_address_line1' => 'Nairobi',
            'shipping_city' => 'Nairobi',
            'shipping_country' => 'Kenya',
            'subtotal' => 1200,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => 1200,
            'currency' => 'KES',
        ]);
    }
}
