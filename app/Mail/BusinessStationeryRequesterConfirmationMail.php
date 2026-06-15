<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessStationeryRequesterConfirmationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $pageTitle,
        public readonly array $fields,
        public readonly ?string $requesterName,
    ) {
    }

    public function build(): self
    {
        return $this
            ->from(config('shop.notification_email'), config('shop.notification_name'))
            ->replyTo(config('shop.notification_email'), config('shop.notification_name'))
            ->subject('We received your NaiPrinters request')
            ->view('emails.business-stationery-request-confirmation');
    }
}
