<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\View\View;

class SeoHubController extends Controller
{
    public function index(): View
    {
        $canonicalBase = rtrim((string) (app()->environment('local')
            ? url('/')
            : (config('regions.regions.uk.base_url') ?: url('/'))), '/');

        $pillarSlug = 'uk-seo-growth-system-2026-aeo-geo-eeat-guide';
        $clusterSlugs = [
            'how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
            'technical-seo-checklist-for-uk-websites-before-launch',
            'landing-page-cro-for-uk-campaigns-7-fixes-that-increase-enquiries',
            'wordpress-vs-shopify-for-uk-businesses-which-platform-fits-your-growth-stage',
            'why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm',
        ];

        $pillarPost = BlogPost::query()
            ->where('is_published', true)
            ->where('slug', $pillarSlug)
            ->first();

        $supportPosts = BlogPost::query()
            ->where('is_published', true)
            ->whereIn('slug', $clusterSlugs)
            ->when($pillarPost, fn ($q) => $q->where('id', '!=', $pillarPost->id))
            ->get()
            ->sortBy(function (BlogPost $post) use ($clusterSlugs) {
                $position = array_search($post->slug, $clusterSlugs, true);
                return $position === false ? 999 : $position;
            })
            ->values();

        if ($supportPosts->count() < 5) {
            $fallbackPosts = BlogPost::query()
                ->where('is_published', true)
                ->when($pillarPost, fn ($q) => $q->where('id', '!=', $pillarPost->id))
                ->whereNotIn('slug', $supportPosts->pluck('slug')->all())
                ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->limit(6 - $supportPosts->count())
                ->get();

            $supportPosts = $supportPosts->concat($fallbackPosts)->values();
        }

        $seoOverride = [
            'title' => 'UK SEO Growth Hub: AEO, GEO and EEAT Strategy',
            'description' => 'Professional UK SEO pillar guide for AEO, GEO, EEAT, schema, Core Web Vitals, and buyer-intent content clusters.',
            'keywords' => 'uk seo growth hub, aeo strategy uk, geo optimization uk, eeat seo guide uk, ai overview seo',
            'type' => 'CollectionPage',
            'canonical' => $canonicalBase . '/uk-growth-hub',
            'related_links' => [
                '/services',
                '/search-engine-optimization',
                '/pricing',
                '/contact',
                '/portfolio',
            ],
            'faq_items' => [
                [
                    'question' => 'What does AEO mean for UK business websites?',
                    'answer' => 'AEO means structuring content with direct question-based answers so Google and AI engines can extract and cite the right response quickly.',
                ],
                [
                    'question' => 'How is GEO different from traditional SEO?',
                    'answer' => 'GEO focuses on making your pages easy for generative search systems to interpret, summarize, and recommend using clear entities, context, and trust signals.',
                ],
                [
                    'question' => 'Why does EEAT matter for lead-focused UK services?',
                    'answer' => 'EEAT improves trust by showing practical experience, real proof, editorial ownership, and transparent company details, which supports rankings and conversion quality.',
                ],
                [
                    'question' => 'What is a strong topic cluster structure?',
                    'answer' => 'A strong cluster uses one pillar page for the core topic and supporting articles targeting specific buyer questions, all linked together with clear anchor text.',
                ],
            ],
        ];

        return view('pages.seo-hub', compact('seoOverride', 'pillarPost', 'supportPosts'));
    }
}
