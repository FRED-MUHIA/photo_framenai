<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Http\Request;
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

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,printing,shipped,completed,cancelled'],
            'payment_status' => ['nullable', 'in:unpaid,paid,failed,refunded'],
        ]);

        $newPaymentStatus = $validated['payment_status'] ?? $order->payment_status;
        $changes = $this->statusChanges($order, $validated['status'], $newPaymentStatus);

        $order->update([
            'status' => $validated['status'],
            'payment_status' => $newPaymentStatus,
        ]);

        if (! empty($changes)) {
            $this->sendStatusUpdateEmails($order->fresh(), $changes);
        }

        return back()->with('status', 'Order status updated.');
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

    private function sendStatusUpdateEmails(Order $order, array $changes): void
    {
        if ($order->customer_email) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order, $changes));
            } catch (Throwable $exception) {
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
            return;
        }

        try {
            Mail::to($storeEmail)->send(new OrderStatusUpdatedMail($order, $changes));
        } catch (Throwable $exception) {
            Log::error('Failed to send internal order status update email.', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'recipient' => $storeEmail,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function storeNotificationEmail(): ?string
    {
        return config('mail.mailers.smtp.username') ?: config('mail.from.address');
    }
}
