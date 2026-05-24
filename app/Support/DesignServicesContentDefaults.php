<?php

namespace App\Support;

class DesignServicesContentDefaults
{
    public static function sections(): array
    {
        return [
            [
                'component' => 'design_hero',
                'title' => 'Print Designs That',
                'subtitle' => 'Look Ready',
                'content' => 'We design polished mockups, book covers, magazines, catalogues, company profiles, and print-ready layouts for brands that need work customers can see and trust.',
                'button_label' => 'Get Started',
                'button_url' => '#design-request',
                'bg_color' => '#070304',
                'text_color' => '#ffffff',
                'items' => [
                    ['title' => 'Book, brochure and catalogue layouts', 'subtitle' => 'Print', 'content' => 'stat'],
                    ['title' => '', 'subtitle' => '', 'content' => 'image', 'image_url' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=700&q=80'],
                    ['title' => '', 'subtitle' => '', 'content' => 'image', 'image_url' => 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?auto=format&fit=crop&w=700&q=80'],
                    ['title' => 'Mockup • Design • Print', 'subtitle' => '', 'content' => 'dark'],
                    ['title' => '', 'subtitle' => '', 'content' => 'image', 'image_url' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=700&q=80'],
                    ['title' => 'Mockups for approval before production', 'subtitle' => 'PDF', 'content' => 'stat'],
                ],
            ],
            [
                'component' => 'design_gallery',
                'title' => 'Unleashing Your Brand’s Potential',
                'subtitle' => 'Service',
                'content' => '',
                'items' => [
                    ['title' => 'Brand card mockup', 'subtitle' => 'custom-logo-design', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Logo stationery mockup', 'subtitle' => 'custom-logo-design', 'content' => 'center', 'image_url' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Brand identity presentation', 'subtitle' => 'custom-logo-design', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Website layout mockup', 'subtitle' => 'website-design-services', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Mobile website interface', 'subtitle' => 'website-design-services', 'content' => 'center', 'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Website code and design desk', 'subtitle' => 'website-design-services', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Signage mockup', 'subtitle' => 'custom-graphic-design', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1564061170517-d3907caa96ea?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Graphic design print mockup', 'subtitle' => 'custom-graphic-design', 'content' => 'center', 'image_url' => 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Campaign graphic workspace', 'subtitle' => 'custom-graphic-design', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1542744094-24638eff58bb?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Magazine design mockup', 'subtitle' => 'brochure-magazine-design', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1476357471311-43c0db9fb2b4?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Brochure page layout', 'subtitle' => 'brochure-magazine-design', 'content' => 'center', 'image_url' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Publication design layout', 'subtitle' => 'brochure-magazine-design', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1523726491678-bf852e717f6a?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            [
                'component' => 'design_services',
                'title' => 'Unleashing Your Brand’s Potential',
                'subtitle' => 'Service',
                'content' => 'Unleashing Your Brand’s Potential by crafting innovative strategies, impactful designs, and tailored solutions that resonate with your audience and elevate your brand to new heights.',
                'items' => [
                    ['title' => 'Custom Logo Design', 'subtitle' => 'custom-logo-design', 'content' => 'A distinctive logo system built for print, digital use, signage, uniforms, and everyday brand recognition.', 'image_url' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Website Design Services', 'subtitle' => 'website-design-services', 'content' => 'Responsive website layouts that present your business clearly, guide visitors, and support enquiries or sales.', 'image_url' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Custom Graphic Design', 'subtitle' => 'custom-graphic-design', 'content' => 'Purpose-built visual designs for campaigns, company profiles, product promotions, events, and everyday marketing.', 'image_url' => 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Brochure & Magazine Design', 'subtitle' => 'brochure-magazine-design', 'content' => 'Structured multi-page layouts for brochures, magazines, catalogues, programmes, and company profiles.', 'image_url' => 'https://images.unsplash.com/photo-1476357471311-43c0db9fb2b4?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            [
                'component' => 'design_process',
                'title' => 'A Methodical Process For Maximum Results.',
                'subtitle' => 'How We Work',
                'content' => 'A structured method for efficiency, ensuring precise results and consistent success.',
                'items' => [
                    ['title' => 'Discovery', 'content' => 'We clarify your goals, audience, content, references, and expected outputs so the project starts with a focused creative direction.'],
                    ['title' => 'Research', 'content' => 'We study your market, competitors, visual references, and production needs to define a practical design path before artwork begins.'],
                    ['title' => 'Design', 'content' => 'We create the visual direction, layouts, and polished artwork, balancing creative impact with files that are ready for print and digital use.'],
                    ['title' => 'Iteration', 'content' => 'We refine the selected direction through feedback, improving composition, typography, color, imagery, and final usability.'],
                    ['title' => 'Result', 'content' => 'We prepare final deliverables for launch, handoff, printing, publishing, or campaign rollout with clear production-ready outputs.'],
                ],
            ],
            [
                'component' => 'design_projects',
                'title' => 'Showcasing My Most Recent Projects',
                'subtitle' => 'Work',
                'content' => '',
                'items' => array_values(DesignServiceProjects::all()),
            ],
            [
                'component' => 'design_experience',
                'title' => '8 years of experience and have worked with',
                'subtitle' => '07',
                'content' => '',
                'items' => [
                    ['title' => 'Google'],
                    ['title' => 'goodreads'],
                    ['title' => 'amazon'],
                    ['title' => 'Forbes'],
                    ['title' => 'PayPal'],
                ],
            ],
            [
                'component' => 'design_contact',
                'title' => 'Let’s Start Your Design Project',
                'subtitle' => 'Contact',
                'content' => 'Tell us what you need designed and our team will review your request, confirm the best next step, and get back to you.',
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

    public static function projects(?array $sections): array
    {
        $projectSection = collect(static::mergedSections($sections))->firstWhere('component', 'design_projects');

        return collect(data_get($projectSection, 'items', []))
            ->filter(fn ($item) => is_array($item) && data_get($item, 'title'))
            ->map(fn ($project) => static::normalizeProject($project))
            ->keyBy('slug')
            ->all();
    }

    public static function findProject(string $slug, ?array $sections): ?array
    {
        return static::projects($sections)[$slug] ?? null;
    }

    public static function normalizeProject(array $project): array
    {
        $slug = data_get($project, 'slug') ?: data_get($project, 'link_url') ?: str(data_get($project, 'title', 'project'))->slug()->toString();
        $images = data_get($project, 'images', []);
        if (is_string($images)) {
            $images = preg_split('/\r\n|\r|\n/', $images);
        }

        $deliverables = data_get($project, 'deliverables', []);
        if (is_string($deliverables)) {
            $deliverables = preg_split('/\r\n|\r|\n|,/', $deliverables);
        }

        $heroImage = data_get($project, 'image_url');
        $images = collect($images)->filter()->values()->all();
        if ($heroImage && ! in_array($heroImage, $images, true)) {
            array_unshift($images, $heroImage);
        }

        return [
            'slug' => (string) $slug,
            'title' => (string) data_get($project, 'title', ''),
            'year' => (string) (data_get($project, 'year') ?: data_get($project, 'subtitle', '')),
            'service' => (string) data_get($project, 'service', ''),
            'summary' => (string) (data_get($project, 'summary') ?: data_get($project, 'content', '')),
            'details' => (string) data_get($project, 'details', ''),
            'images' => $images,
            'deliverables' => collect($deliverables)->filter()->values()->all(),
        ];
    }
}
