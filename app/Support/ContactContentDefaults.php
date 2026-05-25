<?php

namespace App\Support;

class ContactContentDefaults
{
    public static function sections(): array
    {
        return [
            [
                'component' => 'contact_intro',
                'title' => 'Contact Us',
                'content' => 'Need support, a bulk quote, or custom print consultation? Reach out below.',
                'items' => [],
            ],
            [
                'component' => 'contact_channels',
                'title' => 'Direct Channels',
                'content' => "Email: hello@naiprinters.test\nPhone: +254 700 000 000",
                'button_label' => 'Chat on WhatsApp',
                'button_url' => config('shop.whatsapp_link', '#'),
                'items' => [],
            ],
            [
                'component' => 'contact_form',
                'button_label' => 'Send Message',
                'items' => [
                    ['title' => 'Name', 'content' => 'text'],
                    ['title' => 'Email', 'content' => 'email'],
                    ['title' => 'How can we help?', 'content' => 'textarea'],
                ],
            ],
        ];
    }

    public static function mergedSections(?array $sections): array
    {
        $defaults = collect(static::sections())->keyBy('component');

        if (! is_array($sections) || empty($sections)) {
            return $defaults->values()->all();
        }

        $resolvedSections = collect($sections)
            ->filter(fn ($section) => is_array($section) && ! empty($section['component']))
            ->map(function (array $section) use ($defaults) {
                $default = (array) $defaults->get($section['component'], []);
                $resolved = array_merge($default, $section);

                if (array_key_exists('items', $section)) {
                    $resolved['items'] = is_array($section['items']) ? $section['items'] : [];
                }

                return $resolved;
            });

        $missingDefaults = $defaults->reject(fn ($section, $component) => $resolvedSections->contains('component', $component));

        return $resolvedSections->concat($missingDefaults)->values()->all();
    }
}
