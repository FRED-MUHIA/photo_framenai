<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessStationeryRequestMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $pageTitle,
        public readonly string $pageSlug,
        public readonly ?string $requestUrl,
        public readonly array $fields,
        public readonly ?string $requesterName,
        public readonly ?string $requesterEmail,
        public readonly ?string $requesterPhone,
        public readonly ?int $userId,
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('New Request: ' . $this->pageTitle)
            ->view('emails.business-stationery-request');
    }
}

