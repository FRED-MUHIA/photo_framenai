<?php

namespace Tests\Feature;

use Tests\TestCase;

class CanonicalHostSecurityTest extends TestCase
{
    public function test_duplicate_hosts_redirect_permanently_to_canonical_domain(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config()->set('seo.site_url', 'https://naiprinters.co.ke');

        $response = $this
            ->get('https://aurixbranding.co.ke/products?category=business-cards');

        $response->assertStatus(301);
        $response->assertHeader('Location', 'https://naiprinters.co.ke/products?category=business-cards');
    }

    public function test_responses_include_security_headers(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertStringContainsString("frame-ancestors 'none'", $response->headers->get('Content-Security-Policy'));
    }

    public function test_public_pages_include_browser_canonical_host_guard(): void
    {
        config()->set('seo.site_url', 'https://naiprinters.co.ke');

        $response = $this->get('/products');

        $response->assertOk();
        $response->assertSee('https://naiprinters.co.ke', false);
        $response->assertSee('window.location.replace', false);
    }
}
