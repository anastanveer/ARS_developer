<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;

/**
 * Adds the written-for-this-post sections held in database/content/blog-depth.php.
 *
 * Removing the templated filler left the shorter posts at 200-400 words, which
 * trades a duplicate-content problem for a thin-content one. The answer is not to
 * hide those pages but to give them something worth reading, so each entry in the
 * depth file is written for one post: a mechanism, a number, a failure mode that
 * belongs to that subject and could not be moved to another page without becoming
 * false. That is the difference between depth and the padding this replaces.
 *
 * Sections are inserted ahead of the FAQ, which should stay last but for the CTA.
 * The command skips any heading already present, so it is safe on every deploy and
 * will not fight an edit made from the admin.
 */
class AddBlogDepth extends Command
{
    protected $signature = 'blog:add-depth {--dry-run : Report what would change without saving}';

    protected $description = 'Add per-post depth sections to blog posts that are short after de-duplication';

    public function handle(): int
    {
        $depth = require database_path('content/blog-depth.php');
        $dryRun = (bool) $this->option('dry-run');
        $changed = 0;
        $wordsAdded = 0;
        $missing = [];

        foreach ($depth as $slug => $sections) {
            $post = BlogPost::query()->where('slug', $slug)->first();

            if (!$post) {
                $missing[] = $slug;
                continue;
            }

            $content = (string) $post->content;
            $before = str_word_count(strip_tags($content));

            $addition = '';
            foreach ($sections as $section) {
                [$heading, $paragraphs] = [$section['heading'], $section['body']];

                if (str_contains($content, '<h2>' . $heading . '</h2>')) {
                    continue;
                }

                $addition .= '<h2>' . $heading . '</h2>';
                foreach ($paragraphs as $paragraph) {
                    $addition .= '<p>' . $paragraph . '</p>';
                }
            }

            if ($addition === '') {
                continue;
            }

            $content = $this->insert($content, $addition);
            $after = str_word_count(strip_tags($content));
            $wordsAdded += $after - $before;
            $changed++;

            $this->line(sprintf('  %-58s %4d -> %4d words', $slug, $before, $after));

            if (!$dryRun) {
                $post->content = $content;
                $post->save();
            }
        }

        foreach ($missing as $slug) {
            $this->warn('  no post found for ' . $slug);
        }

        if ($changed === 0) {
            $this->info('blog:add-depth — nothing to add.');

            return self::SUCCESS;
        }

        $this->info(sprintf(
            '%s %d post%s, %d words added.',
            $dryRun ? 'Dry run:' : 'Updated',
            $changed,
            $changed === 1 ? '' : 's',
            $wordsAdded
        ));

        return self::SUCCESS;
    }

    /** Places the new sections before the FAQ, or before the closing CTA, or last. */
    private function insert(string $content, string $addition): string
    {
        foreach (['<h2>Frequently Asked Questions</h2>', '<h2>FAQs</h2>', '<h2>Next Step</h2>'] as $marker) {
            $position = strpos($content, $marker);

            if ($position !== false) {
                return substr($content, 0, $position) . $addition . substr($content, $position);
            }
        }

        return $content . $addition;
    }
}
