<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Console\Command;

/**
 * Removes the generated filler that made most blog posts copies of each other.
 *
 * Two seeders assembled each article from a fixed template with only the topic
 * phrase substituted, so the same eight or nine sections appeared word for word on
 * every post they wrote. Measured on the live site, one post was 1,060 of its 1,449
 * words identical to another, and 43 of 83 posts were more than half duplicated.
 * That is what Google calls scaled content, and it is the most likely reason the
 * domain was refused AdSense for "Low value content".
 *
 * The seeders no longer emit it, but they are not run on deploy — posts are
 * editable from the admin, so reseeding would discard real edits. This command
 * instead subtracts the known blocks from the stored HTML and leaves everything
 * else alone, which makes it safe to re-run: once the filler is gone there is
 * nothing left to match.
 */
class StripBlogBoilerplate extends Command
{
    protected $signature = 'blog:strip-boilerplate {--dry-run : Report what would change without saving}';

    protected $description = 'Remove templated filler sections shared across blog posts';

    /** Nodes removed while processing the post currently in hand. */
    private int $removed = 0;

    /** Whole <h2> sections whose every paragraph was generated from a template. */
    private const GENERIC_SECTIONS = [
        'uk buyer decision framework',
        'cost, timeline, and scope planning',
        'seo, content, and trust signals',
        'governance, testing, and post-launch support',
        'example project scenario',
        'practical implementation checklist',
        'how ars developer ltd can help',
        'who this is for',
        'common mistakes to avoid',
        'what this means in practice',
    ];

    /**
     * Paragraphs and list items that appear inside otherwise genuine sections.
     * Matched on a distinctive opening phrase, lowercased and whitespace-collapsed.
     */
    private const GENERIC_FRAGMENTS = [
        'for uk businesses in 2026, the safest approach is to connect',
        'for uk buyers comparing suppliers, the strongest content usually explains',
        'in practical terms, a strong',
        'taken together, these points show how',
        'if a business is actively researching',
        'this is also why long-form content tends to perform better',
        'for uk service brands, the best-performing pages also reduce',
        'if your team is reviewing',
        'continue with our <a href="/services">services</a>',
        'if this topic is active in your business, start with a scoped review',
        'define the exact commercial goal behind',
        'align the page with related service, pricing, case-study, and faq content',
        'use search console data, internal linking, and conversion tracking',
        'review the content regularly so it stays relevant',
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $changed = 0;
        $wordsRemoved = 0;

        foreach (BlogPost::query()->cursor() as $post) {
            $original = (string) $post->content;

            if (trim($original) === '' || $original === strip_tags($original)) {
                continue;
            }

            $stripped = $this->strip($original);

            // Compare removals, not strings: parsing and re-serialising the HTML
            // normalises entities and whitespace on its own, so a post where nothing
            // matched still comes back textually different. Rewriting those would be
            // 83 pointless edits and a misleading report.
            if ($this->removed === 0) {
                continue;
            }

            $before = str_word_count(strip_tags($original));
            $after = str_word_count(strip_tags($stripped));
            $wordsRemoved += $before - $after;
            $changed++;

            $this->line(sprintf('  %-58s %5d -> %4d words', $post->slug, $before, $after));

            if (!$dryRun) {
                $post->content = $stripped;
                $post->save();
            }
        }

        if ($changed === 0) {
            $this->info('blog:strip-boilerplate — nothing to remove.');

            return self::SUCCESS;
        }

        $this->info(sprintf(
            '%s %d post%s, %d duplicated words removed.',
            $dryRun ? 'Dry run:' : 'Updated',
            $changed,
            $changed === 1 ? '' : 's',
            $wordsRemoved
        ));

        return self::SUCCESS;
    }

    private function strip(string $html): string
    {
        $this->removed = 0;
        $dom = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        // The stored value is a fragment, so wrap it and read the wrapper back out.
        $dom->loadHTML(
            '<?xml encoding="UTF-8"?><div id="ars-root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($dom);
        $root = $xpath->query('//div[@id="ars-root"]')->item(0);

        if (!$root instanceof DOMElement) {
            return $html;
        }

        $this->removeGenericSections($root);
        $this->removeGenericFragments($xpath, $root);
        $this->removeEmptySections($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return trim($out);
    }

    /** Drops each listed <h2> together with everything up to the next <h2>. */
    private function removeGenericSections(DOMElement $root): void
    {
        foreach (iterator_to_array($root->childNodes) as $node) {
            if (!$node instanceof DOMElement || strtolower($node->nodeName) !== 'h2') {
                continue;
            }

            if (!in_array($this->normalise($node->textContent), self::GENERIC_SECTIONS, true)) {
                continue;
            }

            $sibling = $node->nextSibling;
            $root->removeChild($node);
            $this->removed++;

            while ($sibling !== null) {
                $next = $sibling->nextSibling;

                if ($sibling instanceof DOMElement && strtolower($sibling->nodeName) === 'h2') {
                    break;
                }

                $root->removeChild($sibling);
                $this->removed++;
                $sibling = $next;
            }
        }
    }

    /** Drops individual paragraphs and list items that were template-generated. */
    private function removeGenericFragments(DOMXPath $xpath, DOMElement $root): void
    {
        foreach ($xpath->query('.//p | .//li', $root) as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }

            $text = $this->normalise($node->textContent);
            $inner = $this->normalise($this->innerHtml($node));

            foreach (self::GENERIC_FRAGMENTS as $fragment) {
                if (str_starts_with($text, $fragment) || str_starts_with($inner, $fragment)) {
                    $node->parentNode?->removeChild($node);
                    $this->removed++;
                    break;
                }
            }
        }
    }

    /**
     * A heading left with no body, or a list left with no items, is debris from the
     * removals above rather than content a reader should meet.
     */
    private function removeEmptySections(DOMElement $root): void
    {
        foreach (iterator_to_array($root->getElementsByTagName('ul')) as $list) {
            if ($list->getElementsByTagName('li')->length === 0) {
                $list->parentNode?->removeChild($list);
            }
        }

        foreach (iterator_to_array($root->childNodes) as $node) {
            if (!$node instanceof DOMElement || strtolower($node->nodeName) !== 'h2') {
                continue;
            }

            $sibling = $node->nextSibling;

            while ($sibling !== null && trim($sibling->textContent) === '' && !$sibling instanceof DOMElement) {
                $sibling = $sibling->nextSibling;
            }

            if ($sibling === null || ($sibling instanceof DOMElement && strtolower($sibling->nodeName) === 'h2')) {
                $root->removeChild($node);
            }
        }
    }

    private function innerHtml(DOMElement $node): string
    {
        $html = '';
        foreach ($node->childNodes as $child) {
            $html .= $node->ownerDocument?->saveHTML($child) ?? '';
        }

        return $html;
    }

    private function normalise(string $text): string
    {
        return strtolower(trim((string) preg_replace('/\s+/u', ' ', html_entity_decode($text))));
    }
}
