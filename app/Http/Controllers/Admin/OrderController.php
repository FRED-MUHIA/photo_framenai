<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Artwork;
use App\Models\Frame;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $orders = Order::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.artwork', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    public function downloadCustomizationFile(Order $order, int $orderItem, string $type)
    {
        abort_unless(in_array($type, ['original', 'preview'], true), 404);
        $item = $order->items()->findOrFail($orderItem);
        $path = data_get($item->configuration, 'frame_customization.' . ($type === 'original' ? 'original_image_path' : 'preview_path'));
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path, $type === 'original'
            ? (data_get($item->configuration, 'frame_customization.original_image_name') ?: 'customer-photo')
            : 'framed-preview.png');
    }

    public function viewCustomizationFile(Order $order, int $orderItem, string $type)
    {
        abort_unless(in_array($type, ['original', 'preview'], true), 404);
        $item = $order->items()->findOrFail($orderItem);
        $customization = data_get($item->configuration, 'frame_customization');
        $path = data_get($customization, $type === 'original' ? 'original_image_path' : 'preview_path');
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => $type === 'original' ? (data_get($customization, 'original_image_mime') ?: 'application/octet-stream') : 'image/png',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,printing,shipped,completed,cancelled'],
            'payment_status' => ['nullable', 'in:unpaid,paid,failed,refunded'],
        ]);

        $newPaymentStatus = $validated['payment_status'] ?? $order->payment_status;
        $changes = $this->statusChanges($order, $validated['status'], $newPaymentStatus);

        DB::transaction(function () use ($order, $validated, $newPaymentStatus) {
            if ($order->status !== 'cancelled' && $validated['status'] === 'cancelled') {
                $this->releaseFrameInventory($order);
            } elseif ($order->status === 'cancelled' && $validated['status'] !== 'cancelled') {
                $this->reserveFrameInventory($order);
            }
            $order->update([
                'status' => $validated['status'],
                'payment_status' => $newPaymentStatus,
            ]);
        });

        $notificationResult = ['customer_sent' => false, 'customer_failed' => false];

        if (! empty($changes)) {
            $notificationResult = $this->sendStatusUpdateEmails($order->fresh(), $changes);
        }

        $message = 'Order status updated.';

        if (! empty($changes)) {
            $message .= $notificationResult['customer_sent']
                ? ' Client has been notified.'
                : ' Client email could not be sent. Check SMTP settings.';
        }

        return back()->with('status', $message);
    }

    public function destroy(Order $order)
    {
        $orderNumber = $order->order_number;

        DB::transaction(function () use ($order) {
            $this->releaseFrameInventory($order);
            $itemIds = $order->items()->pluck('id');

            if ($itemIds->isNotEmpty()) {
                Artwork::query()
                    ->whereIn('order_item_id', $itemIds)
                    ->update(['order_item_id' => null]);
            }

            $order->items()->delete();
            $order->delete();
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('status', "Order {$orderNumber} deleted.");
    }

    private function releaseFrameInventory(Order $order): void
    {
        foreach ($order->items as $item) {
            $configuration = $item->configuration ?? [];
            $customization = data_get($configuration, 'frame_customization');
            if (! is_array($customization) || empty($customization['inventory_reserved'])) continue;

            $frame = Frame::query()->whereKey($customization['frame_id'] ?? null)->lockForUpdate()->first();
            if ($frame && $frame->inventory !== null) {
                $frame->increment('inventory', (int) ($customization['inventory_reserved_quantity'] ?? $item->quantity));
            }
            $customization['inventory_reserved'] = false;
            data_set($configuration, 'frame_customization', $customization);
            $item->configuration = $configuration;
            $item->save();
        }
    }

    private function reserveFrameInventory(Order $order): void
    {
        foreach ($order->items as $item) {
            $configuration = $item->configuration ?? [];
            $customization = data_get($configuration, 'frame_customization');
            if (! is_array($customization) || ! empty($customization['inventory_reserved'])) continue;

            $frame = Frame::query()->whereKey($customization['frame_id'] ?? null)->lockForUpdate()->first();
            if (! $frame || $frame->inventory === null) continue;
            if ($frame->inventory < (int) $item->quantity) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'status' => 'The frame no longer has enough stock to reopen this cancelled order.',
                ]);
            }
            $frame->decrement('inventory', (int) $item->quantity);
            $customization['inventory_reserved'] = true;
            $customization['inventory_reserved_quantity'] = (int) $item->quantity;
            data_set($configuration, 'frame_customization', $customization);
            $item->configuration = $configuration;
            $item->save();
        }
    }

    private function statusChanges(Order $order, string $newStatus, string $newPaymentStatus): array
    {
        $changes = [];

        if ($order->status !== $newStatus) {
            $changes[] = [
                'label' => 'Order Status',
                'from' => str($order->status)->replace('_', ' ')->title()->toString(),
                'to' => str($newStatus)->replace('_', ' ')->title()->toString(),
            ];
        }

        if ($order->payment_status !== $newPaymentStatus) {
            $changes[] = [
                'label' => 'Payment Status',
                'from' => str($order->payment_status)->replace('_', ' ')->title()->toString(),
                'to' => str($newPaymentStatus)->replace('_', ' ')->title()->toString(),
            ];
        }

        return $changes;
    }

    private function sendStatusUpdateEmails(Order $order, array $changes): array
    {
        $result = [
            'customer_sent' => false,
            'customer_failed' => false,
            'internal_sent' => false,
            'internal_failed' => false,
        ];

        if ($order->customer_email) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order, $changes));
                $result['customer_sent'] = true;
            } catch (Throwable $exception) {
                $result['customer_failed'] = true;

                Log::error('Failed to send customer order status update email.', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'recipient' => $order->customer_email,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        $storeEmail = $this->storeNotificationEmail();

        if (! $storeEmail || strtolower($storeEmail) === strtolower((string) $order->customer_email)) {
            return $result;
        }

        try {
            Mail::to($storeEmail)->send(new OrderStatusUpdatedMail($order, $changes));
            $result['internal_sent'] = true;
        } catch (Throwable $exception) {
            $result['internal_failed'] = true;

            Log::error('Failed to send internal order status update email.', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'recipient' => $storeEmail,
                'error' => $exception->getMessage(),
            ]);
        }

        return $result;
    }

    private function storeNotificationEmail(): ?string
    {
        return config('shop.notification_email');
    }
}
