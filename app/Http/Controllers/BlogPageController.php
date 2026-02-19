<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPageController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $querySeo = Str::limit($query, 32, '');
        $canonicalBase = rtrim((string) (app()->environment('local')
            ? url('/')
            : (config('regions.regions.uk.base_url') ?: url('/'))), '/');

        $posts = BlogPost::query()
            ->where('is_published', true)
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($nested) use ($query) {
                    $nested->where('title', 'like', '%' . $query . '%')
                        ->orWhere('excerpt', 'like', '%' . $query . '%')
                        ->orWhere('content', 'like', '%' . $query . '%')
                        ->orWhere('meta_keywords', 'like', '%' . $query . '%')
                        ->orWhere('category', 'like', '%' . $query . '%');
                });
            })
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $seoOverride = [
            'title' => $query !== '' ? ('Search: ' . $querySeo . ' - Blog') : 'Blog - UK Web Development and SEO Insights',
            'description' => $query !== ''
                ? ('Search results for "' . $query . '" in ARSDeveloper blog articles about web development, CRM, and SEO.')
                : 'Practical insights on software development, WordPress, SEO, CRM systems, and digital growth in the UK.',
            'keywords' => $query !== ''
                ? ($query . ', uk software blog, web development insights, seo tips')
                : 'web development blog UK, SEO blog UK, CRM blog UK, WordPress tips UK',
            'type' => 'Blog',
            'robots' => $query !== '' ? 'noindex, follow' : 'index, follow',
            'canonical' => $canonicalBase . '/blog',
        ];

        return view('pages.blog', compact('posts', 'query', 'seoOverride'));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $recentPosts = BlogPost::query()
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        $relatedPosts = BlogPost::query()
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->when($post->category, fn ($q) => $q->where('category', $post->category))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
        $canonicalBase = rtrim((string) (app()->environment('local')
            ? url('/')
            : (config('regions.regions.uk.base_url') ?: url('/'))), '/');

        $metaDescription = Str::limit(
            strip_tags((string) ($post->meta_description ?: $post->excerpt ?: $post->content)),
            155,
            ''
        );
        $metaTitle = $this->normalizeSeoTitle($post->meta_title ?: $post->title);

        $seoOverride = [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $post->meta_keywords ?: 'uk software blog, web development insights, seo tips',
            'type' => 'Article',
            'robots' => $post->meta_robots ?: 'index, follow',
            'canonical' => $post->canonical_url ?: ($canonicalBase . '/blog/' . $post->slug),
            'og_title' => $post->og_title ?: $metaTitle,
            'og_description' => $post->og_description ?: $metaDescription,
            'og_image' => $post->og_image ?: ($post->featured_image ? url('/' . ltrim($post->featured_image, '/')) : null),
            'twitter_title' => $post->twitter_title ?: $metaTitle,
            'twitter_description' => $post->twitter_description ?: $metaDescription,
            'twitter_image' => $post->twitter_image ?: ($post->featured_image ? url('/' . ltrim($post->featured_image, '/')) : null),
        ];

        return view('pages.blog-details', compact('post', 'recentPosts', 'relatedPosts', 'seoOverride'));
    }

    public function detailsLegacy(Request $request): RedirectResponse
    {
        $slug = trim((string) $request->query('slug', ''));

        if ($slug !== '') {
            return redirect('/blog/' . $slug, 301);
        }

        $first = BlogPost::query()
            ->where('is_published', true)
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->first();

        if ($first) {
            return redirect('/blog/' . $first->slug, 301);
        }

        return redirect('/blog', 301);
    }

    private function normalizeSeoTitle(string $title): string
    {
        $clean = trim(preg_replace('/\s+/', ' ', strip_tags($title)));
        // Keep room for " | ARSDeveloper" suffix added in global header.
        return Str::limit($clean, 44, '');
    }
}
