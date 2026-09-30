<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Rewrites the posts listed in database/content/blog-retopic.php onto new subjects.
 *
 * The blog carried six posts on answer-engine optimisation, five on hiring a Laravel
 * developer, three on what a CRM costs and three on SaaS MVPs. Each set asked the
 * same question with the words rearranged, which is what a generated blog looks like
 * from the outside — and that impression forms before anyone reads the content.
 *
 * The strongest post in each set kept its subject. The rest are rewritten here onto
 * subjects nothing else on the site covers. Because the subject changes, the slug
 * changes with it, and BlogPageController::show() reads the same file to redirect
 * the old URL permanently to the new one.
 *
 * The original post is backed up before it is overwritten, and a post already on its
 * new slug is skipped, so this is safe to run on every deploy.
 */
class RetopicBlogPosts extends Command
{
    protected $signature = 'blog:retopic {--dry-run : Report what would change without saving}';

    protected $description = 'Rewrite near-duplicate blog posts onto subjects the site does not already cover';

    public function handle(): int
    {
        $map = require database_path('content/blog-retopic.php');
        $dryRun = (bool) $this->option('dry-run');
        $changed = 0;
        $backup = [];

        foreach ($map as $oldSlug => $new) {
            $post = BlogPost::query()->where('slug', $oldSlug)->first();

            if (!$post) {
                // Already rewritten, or the post never existed here.
                continue;
            }

            if (BlogPost::query()->where('slug', $new['slug'])->exists()) {
                $this->warn(sprintf('  %s: target slug %s is taken, skipping', $oldSlug, $new['slug']));
                continue;
            }

            $content = $this->buildContent($new);

            $this->line(sprintf(
                '  %-52s -> %s (%d words)',
                substr($oldSlug, 0, 52),
                $new['slug'],
                str_word_count(strip_tags($content))
            ));

            $changed++;

            if ($dryRun) {
                continue;
            }

            $backup[$oldSlug] = [
                'title' => $post->title,
                'content' => $post->content,
                'excerpt' => $post->excerpt,
            ];

            $post->slug = $new['slug'];
            $post->title = $new['title'];
            $post->excerpt = $new['excerpt'];
            $post->content = $content;
            $post->meta_title = $new['meta_title'];
            $post->meta_description = $new['meta_description'];

            foreach (['og_title' => 'meta_title', 'twitter_title' => 'meta_title'] as $field => $source) {
                if ($this->hasColumn($post, $field)) {
                    $post->{$field} = $new[$source];
                }
            }

            foreach (['og_description', 'twitter_description'] as $field) {
                if ($this->hasColumn($post, $field)) {
                    $post->{$field} = $new['meta_description'];
                }
            }

            if ($this->hasColumn($post, 'featured_image')) {
                $post->featured_image = $new['image'];
            }

            if ($this->hasColumn($post, 'featured_alt')) {
                $post->featured_alt = $new['image_alt'];
            }

            $post->save();
        }

        if ($backup !== []) {
            $path = 'blog-retopic-backup-' . now()->format('Y-m-d-His') . '.json';
            $disk = Storage::disk('local');
            $disk->put($path, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->line('  Original posts saved to ' . $disk->path($path));
        }

        if ($changed === 0) {
            $this->info('blog:retopic — nothing to rewrite.');

            return self::SUCCESS;
        }

        $this->info(sprintf('%s %d post%s.', $dryRun ? 'Dry run:' : 'Rewrote', $changed, $changed === 1 ? '' : 's'));

        return self::SUCCESS;
    }

    private function buildContent(array $new): string
    {
        $html = '<h2>Quick Answer</h2><p>' . $new['answer'] . '</p>';

        foreach ($new['sections'] as $section) {
            $html .= '<h2>' . $section['heading'] . '</h2>';
            foreach ($section['body'] as $paragraph) {
                $html .= '<p>' . $paragraph . '</p>';
            }
        }

        $html .= '<h2>Frequently Asked Questions</h2>';
        foreach ($new['faq'] as $item) {
            $html .= '<h3>' . $item['q'] . '</h3><p>' . $item['a'] . '</p>';
        }

        return $html . '<h2>Next Step</h2><p>' . $new['cta'] . '</p>';
    }

    private function hasColumn(BlogPost $post, string $column): bool
    {
        return $post->getConnection()
            ->getSchemaBuilder()
            ->hasColumn($post->getTable(), $column);
    }
}
