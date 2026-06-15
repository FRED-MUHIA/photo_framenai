<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class RichTextSanitizer
{
    private const ALLOWED_TAGS = [
        'a',
        'blockquote',
        'br',
        'em',
        'h2',
        'h3',
        'h4',
        'i',
        'li',
        'ol',
        'p',
        'strong',
        'u',
        'ul',
    ];

    private const REMOVED_WITH_CONTENT = [
        'embed',
        'iframe',
        'link',
        'meta',
        'object',
        'script',
        'style',
    ];

    public static function clean(?string $html): ?string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return null;
        }

        if (! preg_match('/<[a-z][\s\S]*>/i', $html)) {
            $plainText = htmlspecialchars_decode($html, ENT_QUOTES | ENT_HTML5);

            return nl2br(e($plainText), false);
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?><div id="rich-text-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $dom->getElementById('rich-text-root');

        if (! $root) {
            return null;
        }

        self::sanitizeChildren($root);

        $cleanHtml = '';
        foreach ($root->childNodes as $child) {
            $cleanHtml .= $dom->saveHTML($child);
        }

        $cleanHtml = trim($cleanHtml);

        if ($cleanHtml === '' || trim(strip_tags($cleanHtml)) === '') {
            return null;
        }

        return $cleanHtml;
    }

    private static function sanitizeChildren(DOMNode $node): void
    {
        for ($child = $node->firstChild; $child !== null; $child = $next) {
            $next = $child->nextSibling;

            if ($child instanceof DOMElement) {
                self::sanitizeElement($child);
            } elseif ($child->nodeType === XML_COMMENT_NODE) {
                $child->parentNode?->removeChild($child);
            }
        }
    }

    private static function sanitizeElement(DOMElement $element): void
    {
        $tagName = strtolower($element->tagName);

        if (in_array($tagName, self::REMOVED_WITH_CONTENT, true)) {
            $element->parentNode?->removeChild($element);

            return;
        }

        self::sanitizeChildren($element);

        if (! in_array($tagName, self::ALLOWED_TAGS, true)) {
            self::unwrap($element);

            return;
        }

        self::sanitizeAttributes($element);
    }

    private static function sanitizeAttributes(DOMElement $element): void
    {
        $tagName = strtolower($element->tagName);
        $href = $tagName === 'a' ? trim((string) $element->getAttribute('href')) : '';

        foreach (iterator_to_array($element->attributes ?? []) as $attribute) {
            $element->removeAttribute($attribute->name);
        }

        if ($tagName !== 'a') {
            return;
        }

        if ($href === '' || ! self::isSafeHref($href)) {
            self::unwrap($element);

            return;
        }

        $element->setAttribute('href', $href);
        $element->setAttribute('target', '_blank');
        $element->setAttribute('rel', 'noopener noreferrer');
    }

    private static function isSafeHref(string $href): bool
    {
        if (str_starts_with($href, '#') || str_starts_with($href, '/') || str_starts_with($href, './') || str_starts_with($href, '../')) {
            return true;
        }

        $scheme = parse_url($href, PHP_URL_SCHEME);

        return in_array(strtolower((string) $scheme), ['http', 'https', 'mailto', 'tel'], true);
    }

    private static function unwrap(DOMElement $element): void
    {
        $parent = $element->parentNode;

        if (! $parent) {
            return;
        }

        while ($element->firstChild) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }
}
