<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $canonicalBase = rtrim((string) (app()->environment('local')
            ? url('/')
            : (config('regions.regions.uk.base_url') ?: url('/'))), '/');
        $query = trim((string) ($request->query('q') ?? $request->query('search-field') ?? ''));
        $results = $this->performSearch($query);

        $popularKeywords = [
            'web development uk',
            'custom crm development',
            'wordpress website design',
            'technical seo audit',
            'software agency stoke-on-trent',
        ];

        return view('pages.search', [
            'page_title' => 'Search',
            'query' => $query,
            'results' => $results,
            'popularKeywords' => $popularKeywords,
            'seoOverride' => [
                'title' => $query !== '' ? ('Search: ' . $query . ' - UK Software Services') : 'Search Results - ARSDeveloper UK',
                'description' => $query !== ''
                    ? ('Search results for "' . $query . '" across ARSDeveloper services, portfolio, and blog pages.')
                    : 'Search ARSDeveloper services, portfolio projects, and blog content.',
                'keywords' => $query !== '' ? ($query . ', uk software agency search, web development uk') : 'search ARSDeveloper, UK software services',
                'canonical' => $canonicalBase . '/search',
                'robots' => 'noindex, follow',
                'type' => 'SearchResultsPage',
            ],
        ]);
    }

    public function suggest(Request $request)
    {
        $query = trim((string) ($request->query('q') ?? ''));
        $results = $this->performSearch($query)->take(8)->map(function (array $item) {
            return [
                'title' => $item['title'],
                'url' => $item['url'],
                'snippet' => strip_tags((string) $item['snippet']),
                'score' => $item['score'],
            ];
        })->values();

        return response()->json([
            'query' => $query,
            'count' => $results->count(),
            'results' => $results,
        ]);
    }

    private function performSearch(string $query)
    {
        $tokens = $this->expandTokens($this->tokenize($query));
        $items = collect(config('site_search.pages', []));

        return $items->map(function (array $item) use ($query, $tokens) {
            $score = $this->scoreItem($item, $query, $tokens);

            return [
                'title' => $item['title'] ?? '',
                'url' => $item['url'] ?? '#',
                'keywords' => $item['keywords'] ?? '',
                'snippet' => $this->buildSnippet($item['content'] ?? '', $tokens),
                'score' => $score,
            ];
        })->filter(fn ($row) => $query !== '' ? $row['score'] > 0 : false)
            ->sortByDesc('score')
            ->values();
    }

    private function tokenize(string $text): array
    {
        return collect(preg_split('/\s+/', Str::lower($text), -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($t) => trim($t))
            ->filter(fn ($t) => mb_strlen($t) > 1)
            ->unique()
            ->values()
            ->all();
    }

    private function expandTokens(array $tokens): array
    {
        $synonyms = [
            'crm' => ['crm', 'customer relationship management', 'sales pipeline'],
            'seo' => ['seo', 'search engine optimization', 'organic traffic'],
            'wordpress' => ['wordpress', 'wp'],
            'web' => ['web', 'website', 'site', 'web development', 'web design'],
            'software' => ['software', 'application', 'app', 'system'],
            'uk' => ['uk', 'united kingdom', 'stoke-on-trent'],
        ];

        $expanded = collect($tokens);
        foreach ($tokens as $token) {
            foreach ($synonyms as $root => $group) {
                if ($token === $root || in_array($token, $group, true)) {
                    $expanded = $expanded->merge($group);
                }
            }
        }

        return $expanded->unique()->values()->all();
    }

    private function scoreItem(array $item, string $query, array $tokens): float
    {
        $title = Str::lower((string) ($item['title'] ?? ''));
        $keywords = Str::lower((string) ($item['keywords'] ?? ''));
        $content = Str::lower((string) ($item['content'] ?? ''));
        $q = Str::lower($query);
        $score = 0.0;

        if ($q !== '') {
            if (str_contains($title, $q)) {
                $score += 70;
            }
            if (str_contains($keywords, $q)) {
                $score += 40;
            }
            if (str_contains($content, $q)) {
                $score += 25;
            }

            similar_text($q, $title, $similarity);
            if ($similarity >= 55) {
                $score += $similarity / 3.0;
            }
        }

        $matchedTokenCount = 0;
        foreach ($tokens as $token) {
            $token = Str::lower($token);
            $matched = false;

            if (str_contains($title, $token)) {
                $score += 18;
                $matched = true;
            }
            if (str_contains($keywords, $token)) {
                $score += 12;
                $matched = true;
            }
            if (str_contains($content, $token)) {
                $score += 5;
                $matched = true;
            }

            if ($matched) {
                $matchedTokenCount++;
            }
        }

        if (count($tokens) > 0 && $matchedTokenCount >= max(1, (int) floor(count($tokens) * 0.7))) {
            $score += 20;
        }

        return round($score, 2);
    }

    private function buildSnippet(string $content, array $tokens): string
    {
        $plain = trim($content);
        if ($plain === '') {
            return '';
        }

        $lower = Str::lower($plain);
        $pos = null;
        foreach ($tokens as $token) {
            $p = strpos($lower, Str::lower($token));
            if ($p !== false) {
                $pos = $p;
                break;
            }
        }

        if ($pos === null) {
            $snippet = Str::limit($plain, 180);
        } else {
            $start = max(0, $pos - 70);
            $snippet = ($start > 0 ? '...' : '') . mb_substr($plain, $start, 180) . '...';
        }

        $snippetHtml = e($snippet);
        foreach ($tokens as $token) {
            $pattern = '/' . preg_quote($token, '/') . '/i';
            $snippetHtml = preg_replace($pattern, '<mark>$0</mark>', $snippetHtml);
        }

        return $snippetHtml;
    }
}
