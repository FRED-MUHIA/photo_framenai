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
            ->subject('Update on your Nai Prints order ' . $this->order->order_number)
            ->view('emails.order-status-updated');
    }
}
