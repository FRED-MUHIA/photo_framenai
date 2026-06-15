<?php

namespace App\Http\Controllers;

use App\Mail\BusinessStationeryRequestMail;
use App\Mail\BusinessStationeryRequesterConfirmationMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class BusinessStationeryRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'page_title' => ['required', 'string', 'max:190'],
            'page_slug' => ['required', 'string', 'max:190'],
            'request_url' => ['nullable', 'string', 'max:500'],
            'full_name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $fields = collect($request->except([
            '_token',
            'page_title',
            'page_slug',
            'request_url',
        ]))
            ->map(function ($value) {
                if (is_array($value)) {
                    return implode(', ', array_filter($value, fn ($item) => $item !== null && $item !== ''));
                }

                return is_string($value) ? trim($value) : $value;
            })
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->mapWithKeys(fn ($value, $key) => [$this->prettyLabel($key) => $value])
            ->all();

        if (empty($fields)) {
            return back()
                ->withInput()
                ->withErrors(['request' => 'Please fill at least one field before sending your request.']);
        }

        try {
            Mail::to('info@naiprinters.co.ke')->send(new BusinessStationeryRequestMail(
                pageTitle: $validated['page_title'],
                pageSlug: $validated['page_slug'],
                requestUrl: $validated['request_url'] ?? $request->fullUrl(),
                fields: $fields,
                requesterName: $validated['full_name'] ?? null,
                requesterEmail: $validated['email'] ?? null,
                requesterPhone: $validated['phone'] ?? null,
                userId: auth()->id(),
            ));

            if (! empty($validated['email'])) {
                Mail::to($validated['email'])->send(new BusinessStationeryRequesterConfirmationMail(
                    pageTitle: $validated['page_title'],
                    fields: $fields,
                    requesterName: $validated['full_name'] ?? null,
                ));
            }
        } catch (Throwable $exception) {
            Log::error('Failed to send business stationery request email.', [
                'page_slug' => $validated['page_slug'],
                'page_title' => $validated['page_title'],
                'requester_email' => $validated['email'] ?? null,
                'exception' => $exception->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['request' => 'We could not send your request right now. Please try again in a moment.']);
        }

        return back()->with('request_success', 'Your request has been sent successfully. Our team will contact you soon.');
    }

    private function prettyLabel(string $key): string
    {
        return ucwords(str_replace('_', ' ', $key));
    }
}
