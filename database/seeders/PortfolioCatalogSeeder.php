<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [];
        $sort = 10;

        $entries = array_merge($entries, $this->buildEntries('WordPress', 'WordPress', [
            'https://www.tlcplumbing.com/',
            'https://cardiffpest.co.uk/',
            'https://www.abathhouse.com/',
            'https://allpawsvet.com/',
            'https://beaverbrookah.com/',
            'https://thecolourloungesalon.com/',
            'https://foxmarin.ca/',
            'https://www.halcyonhealth.us/',
            'https://goblueox.com/',
            'https://www.goldmedalservice.com/',
            'https://prestigedetailingco.com/',
            'https://bathpestcontrollers.co.uk/',
        ], $sort));
        $sort += 100;

        $entries = array_merge($entries, $this->buildEntries('Shopify', 'Shopify', [
            'https://kyliecosmetics.com/en-ae',
            'https://www.gymshark.com/',
            'https://colourpop.com/',
            'https://skincarestore.com.co/',
            'https://graflantz.com/',
            'https://nerdynuts.com/',
            'https://www.allbirds.com/',
        ], $sort));
        $sort += 100;

        $entries = array_merge($entries, $this->buildEntries('Wix', 'Wix', [
            'https://gannonchess.wixsite.com/',
            'https://risdcareers.wixsite.com/design-review/2016?utm_source=chatgpt.com',
            'https://www.canva.com/design/DAGx6qeJGHI/rGeJ3m1PnVCkM-B20-V-dQ/edit',
        ], $sort));
        $sort += 100;

        $entries = array_merge($entries, $this->buildEntries('Webflow', 'Webflow', [
            'https://imo2017.webflow.io/',
            'https://uwdesign2017.webflow.io/',
        ], $sort));
        $sort += 100;

        $entries = array_merge($entries, $this->buildEntries('Custom Coding', 'Custom', [
            'https://www.getonce.com/#referrer=https%3A%2F%2Fdemo.torontobytes.com%2F&sso_redirect=true',
            'https://www.getthursday.com/',
            'https://www.adaline.ai/',
            'https://finotivefunding.com/',
            'https://4proptrader.com/',
            'https://traivend.com/',
        ], $sort));
        $sort += 100;

        $entries = array_merge($entries, $this->buildEntries('Landing Pages', 'Landing', [
            'https://www.loxclubapp.com/',
            'https://www.butter.us/',
        ], $sort));
        $sort += 100;

        $entries[] = [
            'title' => 'CRM Project 1',
            'slug' => 'crm-project-1',
            'category' => 'CRM',
            'client_name' => 'traivend.com',
            'excerpt' => 'CRM implementation for lead tracking, workflow automation, and operational visibility across the business pipeline.',
            'description' => 'CRM case study covering contact lifecycle, status pipelines, automation touchpoints, and practical reporting for daily operations.',
            'image_path' => 'assets/Portfolio/Crm/continentdispo/crm-main.avif',
            'image_path_2' => 'assets/Portfolio/Crm/continentdispo/crm-1.avif',
            'image_path_3' => 'assets/Portfolio/Crm/continentdispo/crm-2.avif',
            'project_url' => 'https://traivend.com/',
            'is_published' => true,
            'sort_order' => $sort,
        ];

        $entries[] = [
            'title' => 'Fiverr Project Portfolio',
            'slug' => 'fiverr-project-portfolio',
            'category' => 'Fiverr',
            'client_name' => 'anasjutt244',
            'excerpt' => 'Active on Fiverr since 2017 with 1000+ clients served across web development, ecommerce, CRM, and conversion-focused business websites.',
            'description' => 'Professional Fiverr case profile demonstrating full-stack web development delivery, repeat client trust, and practical outcomes across multi-industry projects.',
            'image_path' => 'assets/images/project/portfolio-page-1-1.jpg',
            'image_path_2' => null,
            'image_path_3' => null,
            'project_url' => 'https://www.fiverr.com/users/anasjutt244/portfolio/',
            'is_published' => true,
            'sort_order' => $sort + 10,
        ];

        $keepSlugs = collect($entries)->pluck('slug')->all();
        Portfolio::query()->whereNotIn('slug', $keepSlugs)->delete();

        foreach ($entries as $entry) {
            Portfolio::query()->updateOrCreate(
                ['slug' => $entry['slug']],
                $entry
            );
        }
    }

    private function buildEntries(string $category, string $folder, array $urls, int $sortStart): array
    {
        $rows = [];

        foreach ($urls as $index => $url) {
            $num = $index + 1;
            $host = preg_replace('/^www\./', '', (string) (parse_url($url, PHP_URL_HOST) ?: 'project'));
            $rows[] = [
                'title' => $category . ' Project ' . $num,
                'slug' => Str::slug($category . '-project-' . $num),
                'category' => $category,
                'client_name' => $host,
                'excerpt' => $this->excerptByCategory($category, $host, $num),
                'description' => $this->descriptionByCategory($category, $host, $num),
                'image_path' => 'assets/Portfolio/' . $folder . '/' . $num . '.avif',
                'image_path_2' => null,
                'image_path_3' => null,
                'project_url' => $url,
                'is_published' => true,
                'sort_order' => $sortStart + $num,
            ];
        }

        return $rows;
    }

    private function excerptByCategory(string $category, string $host, int $num): string
    {
        $map = [
            'WordPress' => [
                'WordPress business website with SEO-ready page architecture and faster mobile experience.',
                'WordPress service platform focused on trust sections, lead form flow, and local search intent.',
            ],
            'Shopify' => [
                'Shopify ecommerce build with conversion-focused product pages and checkout optimization.',
                'Shopify store delivery with clear product hierarchy, CRO blocks, and sales-ready UX.',
            ],
            'Wix' => [
                'Wix business website setup for clear messaging, service positioning, and enquiry conversions.',
                'Wix implementation with mobile-first layout and simple content management for teams.',
            ],
            'Webflow' => [
                'Webflow project with modern UI sections, clean interactions, and responsive performance.',
                'Webflow marketing website focused on structured content flow and conversion UX.',
            ],
            'Custom Coding' => [
                'Custom coded web application with scalable architecture and business workflow alignment.',
                'Custom development setup for advanced requirements, secure logic, and growth-ready structure.',
            ],
            'Landing Pages' => [
                'Landing page optimized for paid campaign traffic, quick message clarity, and lead capture.',
                'Conversion-first landing page with action hierarchy, trust proof, and faster response flow.',
            ],
        ];

        $variants = $map[$category] ?? ['Business-focused digital delivery with measurable outcomes.'];
        $line = $variants[($num - 1) % count($variants)];
        return $line . ' Live project: ' . $host . '.';
    }

    private function descriptionByCategory(string $category, string $host, int $num): string
    {
        $map = [
            'WordPress' => 'Case '.$num.' for '.$host.' includes sitemap planning, on-page SEO structure, responsive UI build, Core Web Vitals checks, and handover-friendly admin flow.',
            'Shopify' => 'Case '.$num.' for '.$host.' covers product architecture, collection logic, checkout UX refinement, speed optimization, and ongoing growth support readiness.',
            'Wix' => 'Case '.$num.' for '.$host.' focuses on service communication clarity, local intent page layout, mobile responsiveness, and practical owner-side editing workflow.',
            'Webflow' => 'Case '.$num.' for '.$host.' highlights modular section design, animation control, responsive consistency, and launch-ready content operations.',
            'Custom Coding' => 'Case '.$num.' for '.$host.' includes requirement mapping, backend/frontend integration, QA process, scalable deployment approach, and business workflow alignment.',
            'Landing Pages' => 'Case '.$num.' for '.$host.' demonstrates offer-first copy hierarchy, CTA placement strategy, trust components, and conversion-focused page flow.',
        ];

        return $map[$category] ?? 'This project includes strategy, implementation, quality control, and measurable commercial outcomes.';
    }
}
