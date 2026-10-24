<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly array $changes,
    ) {
    }

    public function build(): self
    {
        return $this
            ->from(config('shop.notification_email'), config('shop.notification_name'))
            ->replyTo(config('shop.notification_email'), config('shop.notification_name'))
            ->subject('Update on your Photo Frames order ' . $this->order->order_number)
            ->view('emails.order-status-updated');
    }
}
