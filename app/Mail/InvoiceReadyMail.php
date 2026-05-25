<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class InvoiceReadyMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly Order $order)
    {
    }

    public function build(): self
    {
        $this->order->loadMissing('items');
        $invoiceUrl = URL::temporarySignedRoute(
            'invoices.show',
            now()->addDays(30),
            ['order' => $this->order]
        );

        return $this
            ->subject('Your Nai Prints order has been received')
            ->view('emails.invoice-ready')
            ->with([
                'invoiceUrl' => $invoiceUrl,
            ])
            ->attachData(
                view('emails.attachments.invoice', ['order' => $this->order])->render(),
                'invoice-' . $this->order->order_number . '.html',
                ['mime' => 'text/html']
            );
    }
}
