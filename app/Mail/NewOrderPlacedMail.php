<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderPlacedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly Order $order)
    {
    }

    public function build(): self
    {
        $this->order->loadMissing('items');

        return $this
            ->from(config('shop.notification_email'), config('shop.notification_name'))
            ->replyTo(config('shop.notification_email'), config('shop.notification_name'))
            ->subject('New order placed: ' . $this->order->order_number)
            ->view('emails.new-order-placed')
            ->with([
                'adminOrderUrl' => route('admin.orders.show', $this->order),
            ]);
    }
}
