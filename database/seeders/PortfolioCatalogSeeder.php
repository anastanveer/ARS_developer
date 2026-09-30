<?php

/**
 * The portfolio catalogue.
 *
 * This list used to set `client_name` to whatever host a URL happened to have, which
 * put "Client: kyliecosmetics.com" on a live page — along with gymshark.com,
 * colourpop.com and allbirds.com. Those are companies with their own in-house teams
 * and agencies; presenting them as clients is a claim that cannot be supported, and
 * it is a trademark and misrepresentation problem well before it is an AdSense one.
 *
 * Three entries were not client projects at all: a canva.com/design/…/edit link,
 * which is an editor URL rather than a website; risdcareers.wixsite.com carrying
 * ?utm_source=chatgpt.com, which is how a URL looks when it has been pasted out of a
 * chatbot answer; and two 2017 .webflow.io subdomains. The Wix and Webflow categories
 * consisted only of those, so both are gone.
 *
 * What remains is the small-business and startup work that a freelance developer
 * plausibly delivers. If any entry here is not genuinely yours, remove it — a
 * portfolio is only worth what its weakest claim can survive.
 */

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
            ['https://www.tlcplumbing.com/', '1.avif'],
            ['https://cardiffpest.co.uk/', '2.avif'],
            ['https://www.abathhouse.com/', '3.avif'],
            ['https://allpawsvet.com/', '4.avif'],
            ['https://beaverbrookah.com/', '5.avif'],
            ['https://thecolourloungesalon.com/', '6.avif'],
            ['https://foxmarin.ca/', '7.avif'],
            ['https://www.halcyonhealth.us/', '8.avif'],
            ['https://goblueox.com/', '9.avif'],
            ['https://www.goldmedalservice.com/', '10.avif'],
            ['https://prestigedetailingco.com/', '11.avif'],
            ['https://bathpestcontrollers.co.uk/', '12.avif'],
        ], $sort));
        $sort += 100;

        $entries = array_merge($entries, $this->buildEntries('Shopify', 'Shopify', [
            ['https://skincarestore.com.co/', '4.avif'],
            ['https://graflantz.com/', '5.avif'],
            ['https://nerdynuts.com/', '6.avif'],
        ], $sort));
        $sort += 100;

        $entries = array_merge($entries, $this->buildEntries('Custom Coding', 'Custom', [
            ['https://www.getonce.com/', '1.avif'],
            ['https://www.getthursday.com/', '2.avif'],
            ['https://finotivefunding.com/', '4.avif'],
            ['https://4proptrader.com/', '5.avif'],
            ['https://traivend.com/', '6.avif'],
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

        foreach ($urls as $index => $entry) {
            // The screenshot is named for the project, not for its position in this
            // list. When entries were removed from the middle, position-based naming
            // silently handed each surviving project the previous one's screenshot —
            // Kylie Cosmetics' homepage ended up labelled as another client's work,
            // which is a worse version of the claim the removals were meant to undo.
            [$url, $image] = $entry;
            $num = $index + 1;
            $host = preg_replace('/^www\./', '', (string) (parse_url($url, PHP_URL_HOST) ?: 'project'));
            $rows[] = [
                'title' => $category . ' Project ' . $num,
                'slug' => Str::slug($category . '-project-' . $num),
                'category' => $category,
                'client_name' => $host,
                'excerpt' => $this->excerptByCategory($category, $host, $num),
                'description' => $this->descriptionByCategory($category, $host, $num),
                'image_path' => 'assets/Portfolio/' . $folder . '/' . $image,
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
