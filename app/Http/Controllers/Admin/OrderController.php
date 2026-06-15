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
