<?php

namespace Tests\Feature;

use App\Mail\BusinessStationeryRequestMail;
use App\Mail\BusinessStationeryRequesterConfirmationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BusinessStationeryRequestEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_form_emails_the_shop_and_the_client(): void
    {
        Mail::fake();

        $this->post(route('pages.business-stationery-request'), [
            'page_title' => 'Custom Receipts',
            'page_slug' => 'custom-receipts',
            'request_url' => 'https://naiprinters.co.ke/custom-receipts',
            'full_name' => 'Test Client',
            'email' => 'client@example.com',
            'phone' => '0700000000',
            'quantity' => '100 books',
        ])->assertSessionHas('request_success');

        Mail::assertSent(BusinessStationeryRequestMail::class, function ($mail) {
            $mail->build();

            return $mail->hasTo('info@naiprinters.co.ke')
                && $mail->hasFrom('info@naiprinters.co.ke')
                && $mail->hasReplyTo('client@example.com');
        });

        Mail::assertSent(BusinessStationeryRequesterConfirmationMail::class, function ($mail) {
            $mail->build();

            return $mail->hasTo('client@example.com')
                && $mail->hasFrom('info@naiprinters.co.ke')
                && $mail->hasReplyTo('info@naiprinters.co.ke');
        });
    }

    public function test_request_form_does_not_send_client_confirmation_without_email(): void
    {
        Mail::fake();

        $this->post(route('pages.business-stationery-request'), [
            'page_title' => 'Custom Receipts',
            'page_slug' => 'custom-receipts',
            'full_name' => 'Test Client',
            'phone' => '0700000000',
            'quantity' => '100 books',
        ])->assertSessionHas('request_success');

        Mail::assertSent(BusinessStationeryRequestMail::class);
        Mail::assertNotSent(BusinessStationeryRequesterConfirmationMail::class);
    }
}
