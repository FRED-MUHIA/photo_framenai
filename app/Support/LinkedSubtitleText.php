<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class LinkedSubtitleText
{
    public static function render(?string $value): HtmlString
    {
        $text = (string) $value;

        if ($text === '') {
            return new HtmlString('');
        }

        $html = '';
        $offset = 0;

        preg_match_all('/\[([^\]]+)\]\(([^)\r\n]+)\)/', $text, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[0] as $index => $match) {
            [$rawMatch, $position] = $match;
            $html .= e(substr($text, $offset, $position - $offset));

            $label = $matches[1][$index][0];
            $url = trim($matches[2][$index][0]);

            if (self::isAllowedUrl($url)) {
                $html .= sprintf(
                    '<a href="%s" class="np-overlay-subtitle-link">%s</a>',
                    e($url),
                    e($label)
                );
            } else {
                $html .= e($rawMatch);
            }

            $offset = $position + strlen($rawMatch);
        }

        $html .= e(substr($text, $offset));

        return new HtmlString($html);
    }

    private static function isAllowedUrl(string $url): bool
    {
        if ($url === '') {
            return false;
        }

        return str_starts_with($url, '/')
            || str_starts_with($url, '#')
            || preg_match('/^(https?:|mailto:|tel:)/i', $url) === 1;
    }
}
