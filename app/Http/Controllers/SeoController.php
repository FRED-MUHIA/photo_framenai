<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CmsPage;
use App\Models\Product;
use App\Support\DesignServicesContentDefaults;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([
            $this->entry('home'),
            $this->entry('products.index'),
            $this->entry('pages.about'),
            $this->entry('pages.contact'),
            $this->entry('pages.blog'),
            $this->entry('pages.help'),
            $this->entry('pages.flyers-posters'),
            $this->entry('pages.design-services'),
            $this->entry('pages.custom-receipts'),
            $this->entry('pages.branded-folders'),
            $this->entry('pages.certificates'),
            $this->entry('pages.envelopes'),
            $this->entry('pages.token-business'),
        ]);

        $designPage = CmsPage::query()
            ->active()
            ->where('slug', 'design-services')
            ->first(['sections', 'updated_at']);

        $projectUrls = collect(DesignServicesContentDefaults::projects($designPage?->sections))
            ->map(fn (array $project) => $this->entry(
                'pages.design-projects.show',
                ['project' => $project['slug']],
                $designPage?->updated_at?->toAtomString()
            ));

        $productUrls = Product::query()
            ->active()
            ->orderBy('id')
            ->get(['slug', 'updated_at'])
            ->map(fn (Product $product) => $this->entry(
                'products.show',
                ['product' => $product->slug],
                $product->updated_at?->toAtomString()
            ));

        $postUrls = BlogPost::query()
            ->published()
            ->orderBy('id')
            ->get(['slug', 'updated_at', 'published_at'])
            ->map(fn (BlogPost $post) => $this->entry(
                'pages.blog.show',
                ['post' => $post->slug],
                ($post->updated_at ?: $post->published_at)?->toAtomString()
            ));

        $urls = $urls
            ->concat($projectUrls)
            ->concat($productUrls)
            ->concat($postUrls)
            ->unique('loc')
            ->values();

        return response()
            ->view('seo.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin/',
            'Disallow: /api/',
            'Disallow: /cart',
            'Disallow: /checkout',
            'Disallow: /dashboard',
            'Disallow: /profile',
            'Disallow: /upload-artwork',
            'Disallow: /invoices/',
            'Disallow: /locale/',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /forgot-password',
            'Disallow: /reset-password',
            'Disallow: /verify-email',
            'Disallow: /confirm-password',
            '',
            'Sitemap: ' . $this->absoluteRoute('sitemap'),
            '',
        ]);

        return response($content)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    private function entry(string $route, array $parameters = [], ?string $lastModified = null): array
    {
        return array_filter([
            'loc' => $this->absoluteRoute($route, $parameters),
            'lastmod' => $lastModified,
        ]);
    }

    private function absoluteRoute(string $route, array $parameters = []): string
    {
        $root = rtrim((string) config('app.url'), '/');
        $path = route($route, $parameters, false);

        return $root . '/' . ltrim($path, '/');
    }
}
