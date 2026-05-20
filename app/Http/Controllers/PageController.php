<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CmsPage;
use App\Support\DesignServiceProjects;

class PageController extends Controller
{
    public function about()
    {
        return $this->renderManagedPage('about', 'pages.about');
    }

    public function contact()
    {
        return $this->renderManagedPage('contact', 'pages.contact');
    }

    public function blog()
    {
        $cmsPage = CmsPage::query()->active()->where('slug', 'blog')->first();
        $posts = BlogPost::query()->published()->latest('published_at')->latest('id')->paginate(10);

        return view('pages.blog', compact('posts', 'cmsPage'));
    }

    public function blogShow(BlogPost $post)
    {
        abort_if(
            ! $post->is_published || ($post->published_at && $post->published_at->isFuture()),
            404
        );

        return view('pages.blog-show', compact('post'));
    }

    public function help()
    {
        $faqs = [
            [
                'question' => 'What file formats do you accept?',
                'answer' => 'PDF, PNG, JPG, WEBP and AI files are supported for artwork uploads.',
            ],
            [
                'question' => 'Can I place an order as a guest?',
                'answer' => 'Yes. You can checkout without creating an account, then track via email updates.',
            ],
            [
                'question' => 'How fast can I get my order?',
                'answer' => 'Choose Express delivery at checkout for the quickest turnaround.',
            ],
        ];

        return $this->renderManagedPage('help', 'pages.help', compact('faqs'));
    }

    public function flyersPosters()
    {
        $productOptions = [
            'product_formats' => [
                ['label' => 'Flyers & Leaflets, A8, printed on one side', 'extra' => 0],
                ['label' => 'Flyers & Leaflets, A6, printed on one side', 'extra' => 550],
                ['label' => 'Flyers & Leaflets, A5, printed on one side', 'extra' => 950],
                ['label' => 'Posters, A3, printed on one side', 'extra' => 1800],
                ['label' => 'Posters, A2, printed on one side', 'extra' => 3200],
            ],
            'paper_types' => [
                ['label' => '170 gsm coated paper', 'badge' => 'FSC', 'extra' => 0],
                ['label' => '250 gsm silk art card', 'badge' => 'Premium', 'extra' => 750],
                ['label' => '300 gsm matte art card', 'badge' => 'Rigid', 'extra' => 1200],
            ],
            'quantities' => [
                ['label' => '25', 'extra' => 0],
                ['label' => '50', 'extra' => 650],
                ['label' => '100', 'extra' => 1150],
                ['label' => '250', 'extra' => 2600],
                ['label' => '500', 'extra' => 4300],
            ],
            'paper_finishes' => [
                ['label' => 'silk coated', 'extra' => 0],
                ['label' => 'matte coated', 'extra' => 250],
                ['label' => 'uncoated natural', 'extra' => 350],
            ],
            'finishings' => [
                ['label' => 'single-sided glossy UV varnish', 'note' => '+2 working days', 'extra' => 520],
                ['label' => 'matte lamination', 'note' => '+2 working days', 'extra' => 780],
                ['label' => 'no additional finishing', 'note' => '', 'extra' => 0],
            ],
            'corner_rounding' => [
                ['label' => 'no corner rounding', 'extra' => 0],
                ['label' => 'rounded corners', 'extra' => 450],
            ],
            'printing_methods' => [
                ['label' => 'best value for money', 'extra' => 0],
                ['label' => 'premium colour match', 'extra' => 900],
                ['label' => 'express production', 'extra' => 1200],
            ],
            'data_checks' => [
                ['label' => 'without data check', 'extra' => 0],
                ['label' => 'basic artwork check', 'extra' => 350],
                ['label' => 'professional artwork check', 'extra' => 900],
            ],
            'delivery_options' => [
                ['label' => 'Thu 7th May', 'extra' => 0],
                ['label' => 'Wed 6th May', 'extra' => 490],
                ['label' => 'Tue 5th May', 'extra' => 1390],
            ],
        ];

        return $this->renderManagedPage('flyers-posters', 'pages.flyers-posters', compact('productOptions'));
    }

    public function designServices()
    {
        $services = [
            [
                'id' => 'custom-logo-design',
                'title' => 'Custom Logo Design',
                'intro' => 'A distinctive logo system built for print, digital use, signage, uniforms, and everyday brand recognition.',
                'details' => [
                    'Discovery of your business name, audience, positioning, and preferred visual direction.',
                    'Original logo concepts with clean typography, balanced icon marks, and practical color use.',
                    'Final logo files prepared for print, web, social media, embroidery, and large-format output.',
                    'Optional brand basics including color palette, font pairing, and simple usage guidance.',
                ],
                'deliverables' => ['Primary logo', 'Alternative layout', 'Black and white version', 'Print-ready PDF', 'PNG/JPG exports'],
                'portfolio' => [
                    ['title' => 'Brand mark exploration', 'image' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Stationery logo rollout', 'image' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Identity presentation', 'image' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            [
                'id' => 'website-design-services',
                'title' => 'Website Design Services',
                'intro' => 'Responsive website layouts that present your business clearly, guide visitors, and support enquiries or sales.',
                'details' => [
                    'Planning for page structure, content flow, navigation, and key calls to action.',
                    'Modern interface design for home, service, product, contact, and landing pages.',
                    'Mobile-friendly layouts that keep text, buttons, images, and forms easy to use.',
                    'Design support for launch assets such as banners, icons, contact forms, and web-ready imagery.',
                ],
                'deliverables' => ['Responsive page design', 'Content layout plan', 'Homepage concept', 'Inner page templates', 'Launch graphics'],
                'portfolio' => [
                    ['title' => 'Responsive storefront concept', 'image' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Landing page interface', 'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Content layout system', 'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            [
                'id' => 'custom-graphic-design',
                'title' => 'Custom Graphic Design',
                'intro' => 'Purpose-built visual designs for campaigns, company profiles, product promotions, events, and everyday marketing.',
                'details' => [
                    'Creative artwork for flyers, posters, social posts, roll-up banners, adverts, and presentations.',
                    'Layout cleanup for supplied text, images, logos, and brand assets.',
                    'Print-safe setup with suitable margins, bleed, image resolution, and color preparation.',
                    'Design variations for different sizes and channels so your campaign stays consistent.',
                ],
                'deliverables' => ['Editable design source where applicable', 'Print-ready PDF', 'Web/social exports', 'Size adaptations', 'Artwork check'],
                'portfolio' => [
                    ['title' => 'Campaign poster artwork', 'image' => 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Social and flyer set', 'image' => 'https://images.unsplash.com/photo-1542744094-24638eff58bb?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Event display graphics', 'image' => 'https://images.unsplash.com/photo-1564061170517-d3907caa96ea?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            [
                'id' => 'brochure-magazine-design',
                'title' => 'Brochure & Magazine Design',
                'intro' => 'Structured multi-page layouts for brochures, magazines, catalogues, programmes, and company profiles.',
                'details' => [
                    'Page planning for covers, tables of contents, product pages, articles, adverts, and back pages.',
                    'Professional typography, image placement, section hierarchy, and page rhythm.',
                    'Print production setup for booklet pagination, bleed, margins, folds, and binding requirements.',
                    'Final artwork prepared for digital proofing and production handoff.',
                ],
                'deliverables' => ['Cover design', 'Inside page layouts', 'Print-ready PDF', 'Digital preview PDF', 'Production-ready file setup'],
                'portfolio' => [
                    ['title' => 'Magazine spread layout', 'image' => 'https://images.unsplash.com/photo-1476357471311-43c0db9fb2b4?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Product brochure pages', 'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Catalogue production proof', 'image' => 'https://images.unsplash.com/photo-1523726491678-bf852e717f6a?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
        ];

        return $this->renderManagedPage('design-services', 'pages.design-services', compact('services'));
    }

    public function designProject(string $project)
    {
        $project = DesignServiceProjects::find($project);

        abort_unless($project, 404);

        return view('pages.design-project-show', compact('project'));
    }

    public function customReceipts()
    {
        $sizes = [
            ['code' => 'A4', 'label' => 'A4 Size', 'dimensions' => '210 x 297mm', 'base' => 3960],
            ['code' => 'A5', 'label' => 'A5 Size', 'dimensions' => '148 x 210mm', 'base' => 3280],
            ['code' => 'A6', 'label' => 'A6 Size', 'dimensions' => '105 x 148mm', 'base' => 2890],
            ['code' => 'DL', 'label' => 'DL Size', 'dimensions' => '210 x 99mm', 'base' => 3050],
        ];

        return $this->renderManagedPage('custom-branded-receipts-invoices-delivery-books', 'pages.custom-receipts', compact('sizes'));
    }

    public function brandedFolders()
    {
        $folderTypes = [
            ['label' => 'Normal Single Pocket', 'base' => 24500],
            ['label' => 'Double Pocket', 'base' => 29500],
            ['label' => 'Premium Pocket with Business Card Slot', 'base' => 33500],
        ];

        $finishes = [
            ['label' => 'Matte (Recommended)', 'extra' => 0],
            ['label' => 'Gloss', 'extra' => 1500],
            ['label' => 'Soft Touch Lamination', 'extra' => 2800],
        ];

        $designOptions = [
            ['label' => 'Choose an option', 'extra' => 0],
            ['label' => 'I have print-ready artwork', 'extra' => 0],
            ['label' => 'Need basic design setup', 'extra' => 1200],
            ['label' => 'Need full branded design', 'extra' => 2400],
        ];

        return $this->renderManagedPage('branded-folders', 'pages.branded-folders', compact('folderTypes', 'finishes', 'designOptions'));
    }

    public function certificates()
    {
        $certificateSizes = [
            ['label' => 'A4 Size', 'dimensions' => '210 x 297mm', 'base' => 1800],
            ['label' => 'US Letter Size', 'dimensions' => '215.9 x 279.4mm', 'base' => 2100],
        ];

        $paperTypes = [
            ['label' => 'Standard - 300 GSM', 'extra' => 0],
            ['label' => 'Premium - 350 GSM', 'extra' => 450],
            ['label' => 'Textured Ivory - 300 GSM', 'extra' => 650],
        ];

        $finishes = [
            ['label' => 'Choose an option', 'extra' => 0],
            ['label' => 'Matte', 'extra' => 0],
            ['label' => 'Gloss', 'extra' => 300],
            ['label' => 'Soft Touch Lamination', 'extra' => 550],
        ];

        $orientationOptions = [
            ['label' => 'Landscape', 'multiplier' => 1],
            ['label' => 'Portrait', 'multiplier' => 1],
        ];

        $yesNoOptions = [
            ['label' => 'Choose an option', 'extra' => 0],
            ['label' => 'No', 'extra' => 0],
            ['label' => 'Yes', 'extra' => 250],
        ];

        $designOptions = [
            ['label' => 'Choose an option', 'extra' => 0],
            ['label' => 'I have print-ready artwork', 'extra' => 0],
            ['label' => 'Need basic design setup', 'extra' => 1200],
            ['label' => 'Need full branded design', 'extra' => 2400],
        ];

        $deliveryOptions = [
            ['label' => 'Choose an option', 'extra' => 0],
            ['label' => 'Normal delivery (5-7 days)', 'extra' => 0],
            ['label' => 'Express delivery (2-3 days)', 'extra' => 950],
        ];

        return $this->renderManagedPage('certificates', 'pages.certificates', compact(
            'certificateSizes',
            'paperTypes',
            'finishes',
            'orientationOptions',
            'yesNoOptions',
            'designOptions',
            'deliveryOptions'
        ));
    }

    public function envelopes()
    {
        $envelopeTypes = [
            ['label' => 'With Peel & Seal', 'extra' => 0],
            ['label' => 'With Gum Flap', 'extra' => 120],
            ['label' => 'Window Envelopes', 'extra' => 180],
        ];

        $envelopeSizes = [
            ['code' => 'DL', 'label' => 'DL Size', 'subtitle' => 'Good for DL cards and folded A4', 'dimensions' => '220 x 110mm', 'base' => 1200],
            ['code' => 'C5', 'label' => 'C5 Size', 'subtitle' => 'Good for A5 cards or folded A4', 'dimensions' => '162 x 229mm', 'base' => 1350],
            ['code' => 'C4', 'label' => 'C4 Size', 'subtitle' => 'Standard A4 envelope', 'dimensions' => '229 x 324mm', 'base' => 1600],
            ['code' => 'B4', 'label' => 'B4 Size', 'subtitle' => 'Large envelope for A4 documents', 'dimensions' => '250 x 353mm', 'base' => 1850],
            ['code' => 'C3', 'label' => 'C3 Size', 'subtitle' => 'Good for large documents', 'dimensions' => '324 x 458mm', 'base' => 2200],
        ];

        $simpleOptions = [
            ['label' => 'Choose an option', 'extra' => 0],
            ['label' => 'No', 'extra' => 0],
            ['label' => 'Yes', 'extra' => 250],
        ];

        $designOptions = [
            ['label' => 'Choose an option', 'extra' => 0],
            ['label' => 'I have print-ready artwork', 'extra' => 0],
            ['label' => 'Need basic design setup', 'extra' => 1200],
            ['label' => 'Need full branded design', 'extra' => 2400],
        ];

        $deliveryOptions = [
            ['label' => 'Choose an option', 'extra' => 0],
            ['label' => 'Normal delivery (5-7 days)', 'extra' => 0],
            ['label' => 'Express delivery (2-3 days)', 'extra' => 900],
        ];

        return $this->renderManagedPage('envelopes', 'pages.envelopes', compact(
            'envelopeTypes',
            'envelopeSizes',
            'simpleOptions',
            'designOptions',
            'deliveryOptions'
        ));
    }

    public function tokenBusinessPage()
    {
        $paperTypes = [
            ['label' => 'Standard - 80 GSM', 'base' => 8.5],
            ['label' => 'Premium - 100 GSM', 'base' => 11.0],
            ['label' => 'Conqueror - 120 GSM', 'base' => 14.0],
        ];

        $finishes = [
            ['label' => 'Laid (ConquerorTM - Recommended)', 'extra' => 0],
            ['label' => 'Smooth Matte', 'extra' => 1.5],
            ['label' => 'Silk Finish', 'extra' => 2.5],
        ];

        $slipColours = [
            ['label' => 'White', 'extra' => 0],
            ['label' => 'Cream', 'extra' => 1.2],
            ['label' => 'Light Blue', 'extra' => 1.2],
        ];

        $slipStyles = [
            ['label' => 'Loose Leaves', 'extra' => 0],
            ['label' => 'Glued Pad', 'extra' => 2.0],
            ['label' => 'Numbered Sets', 'extra' => 3.0],
        ];

        $includeEnvelope = [
            ['label' => 'No', 'extra' => 0],
            ['label' => 'Yes', 'extra' => 4.0],
        ];

        $designOptions = [
            ['label' => 'Choose an option', 'extra' => 0],
            ['label' => 'I have print-ready artwork', 'extra' => 0],
            ['label' => 'Need basic design setup', 'extra' => 1200],
            ['label' => 'Need full branded design', 'extra' => 2400],
        ];

        return $this->renderManagedPage('19hdEPSFQ4iUhtWoXHqg2E1kPCpUmaEgP8', 'pages.token-business', compact(
            'paperTypes',
            'finishes',
            'slipColours',
            'slipStyles',
            'includeEnvelope',
            'designOptions'
        ));
    }

    private function renderManagedPage(string $slug, string $fallbackView, array $data = [])
    {
        $cmsPage = CmsPage::query()->active()->where('slug', $slug)->first();

        return view($fallbackView, [
            ...$data,
            'cmsPage' => $cmsPage,
        ]);
    }
}
