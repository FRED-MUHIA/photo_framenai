<?php

namespace Tests\Unit;

use App\Support\LinkedSubtitleText;
use PHPUnit\Framework\TestCase;

class LinkedSubtitleTextTest extends TestCase
{
    public function test_it_renders_safe_subtitle_links(): void
    {
        $html = LinkedSubtitleText::render("Drawstring bags\n[Premium travel items](/products?category=bags)")->toHtml();

        $this->assertStringContainsString("Drawstring bags\n", $html);
        $this->assertStringContainsString(
            '<a href="/products?category=bags" class="np-overlay-subtitle-link">Premium travel items</a>',
            $html
        );
    }

    public function test_it_keeps_unsafe_subtitle_links_as_text(): void
    {
        $html = LinkedSubtitleText::render('[Bad link](javascript:alert(1))')->toHtml();

        $this->assertSame('[Bad link](javascript:alert(1))', $html);
    }

    public function test_it_renders_multiline_link_labels(): void
    {
        $html = LinkedSubtitleText::render("[Drawstring bags\nPremium travel items](/products)")->toHtml();

        $this->assertStringContainsString(
            "<a href=\"/products\" class=\"np-overlay-subtitle-link\">Drawstring bags\nPremium travel items</a>",
            $html
        );
    }
}
