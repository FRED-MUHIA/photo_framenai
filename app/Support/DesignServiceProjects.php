<?php

namespace App\Support;

class DesignServiceProjects
{
    public static function all(): array
    {
        return [
            'coinjac' => [
                'slug' => 'coinjac',
                'title' => 'COINJAC',
                'year' => '2020',
                'service' => 'Custom Logo Design',
                'summary' => 'Coinjac is a leading cryptocurrency trading app that offers a secure, user-friendly platform for buying, selling, and trading cryptocurrencies.',
                'details' => 'The project focused on building a confident visual identity for a fintech product. The work included brand direction, logo refinement, digital card styling, and a clean visual system that could scale across app screens, investor materials, and printed collateral.',
                'images' => [
                    'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1611224923853-80b023f02d71?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1400&q=80',
                ],
                'deliverables' => ['Brand identity', 'Logo system', 'Business card design', 'Digital presentation assets'],
            ],
            'coplo' => [
                'slug' => 'coplo',
                'title' => 'COPLO',
                'year' => '2021',
                'service' => 'Website Design Services',
                'summary' => 'We are dedicated to bringing the world of music to your fingertips. Our brand is a harmonious blend of innovation, creativity, and passion for melodies.',
                'details' => 'The design direction brought together clean interface patterns, strong content hierarchy, and flexible brand elements for a music-focused digital experience. The result was a polished system for landing pages, feature highlights, and conversion-focused content blocks.',
                'images' => [
                    'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1400&q=80',
                ],
                'deliverables' => ['Website concept', 'Responsive layout', 'Brand card system', 'Launch graphics'],
            ],
            'hectory' => [
                'slug' => 'hectory',
                'title' => 'HECTORY',
                'year' => '2022',
                'service' => 'Custom Graphic Design',
                'summary' => 'Our brand identity embodies a seamless fusion of sophistication, modernity, and authenticity, catering to the discerning tastes of the contemporary man.',
                'details' => 'Hectory needed a premium visual language that could carry across packaging tags, promotional visuals, and campaign material. The project emphasized restraint, typography, contrast, and product-focused presentation.',
                'images' => [
                    'https://images.unsplash.com/photo-1611224923853-80b023f02d71?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1542744094-24638eff58bb?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1586953208448-b95a79798f07?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1564061170517-d3907caa96ea?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=1400&q=80',
                ],
                'deliverables' => ['Campaign graphics', 'Tag design', 'Print-ready artwork', 'Social media assets'],
            ],
            'vityk' => [
                'slug' => 'vityk',
                'title' => 'VITYK',
                'year' => '2024',
                'service' => 'Brochure & Magazine Design',
                'summary' => 'Vityk is the best, most fun, and most educational way to learn guitar with guided, step-by-step videos for beginners and advanced guitar players.',
                'details' => 'The Vityk project translated a learning product into a crisp editorial system. It included brochure-style layouts, card-based product storytelling, instructional structure, and promotional print assets for campaign use.',
                'images' => [
                    'https://images.unsplash.com/photo-1600880292089-90a7e086ee0c?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1476357471311-43c0db9fb2b4?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1523726491678-bf852e717f6a?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1586952518485-11b180e92764?auto=format&fit=crop&w=1400&q=80',
                ],
                'deliverables' => ['Brochure layout', 'Editorial structure', 'Print production files', 'Digital preview PDF'],
            ],
        ];
    }

    public static function find(string $slug): ?array
    {
        return static::all()[$slug] ?? null;
    }
}
