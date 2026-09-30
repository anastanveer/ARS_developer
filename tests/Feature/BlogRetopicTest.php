<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guards the rewrite that moved near-duplicate posts onto new subjects.
 *
 * The blog had six posts on answer-engine optimisation, five on hiring a Laravel
 * developer and three each on CRM cost and SaaS MVPs. Ten were rewritten onto
 * subjects nothing else covers. The slug changed with the subject, so the old URLs
 * have to keep working, and the new posts have to stay distinct from each other.
 */
class BlogRetopicTest extends TestCase
{
    use RefreshDatabase;

    private function map(): array
    {
        return require database_path('content/blog-retopic.php');
    }

    public function test_every_rewritten_post_has_a_distinct_subject(): void
    {
        $headings = [];
        $sentences = [];

        foreach ($this->map() as $oldSlug => $new) {
            foreach ($new['sections'] as $section) {
                $heading = strtolower($section['heading']);
                $this->assertArrayNotHasKey(
                    $heading,
                    $headings,
                    "Heading \"{$section['heading']}\" appears in {$oldSlug} and " . ($headings[$heading] ?? '?') . '.'
                );
                $headings[$heading] = $oldSlug;

                foreach ($section['body'] as $paragraph) {
                    foreach (preg_split('/(?<=[.!?])\s+/', strip_tags($paragraph)) as $sentence) {
                        $sentence = strtolower(trim($sentence));

                        if (str_word_count($sentence) < 8) {
                            continue;
                        }

                        $this->assertArrayNotHasKey($sentence, $sentences, "A sentence is repeated across rewritten posts ({$oldSlug}).");
                        $sentences[$sentence] = $oldSlug;
                    }
                }
            }
        }
    }

    public function test_every_rewritten_post_has_its_own_illustration(): void
    {
        $images = [];

        foreach ($this->map() as $oldSlug => $new) {
            $this->assertFileExists(public_path($new['image']), "{$oldSlug}: illustration is missing.");
            $this->assertArrayNotHasKey($new['image'], $images, "{$new['image']} is used by two rewritten posts.");
            $images[$new['image']] = $oldSlug;
        }
    }

    public function test_old_urls_redirect_permanently_to_the_new_subject(): void
    {
        $map = $this->map();
        $oldSlug = array_key_first($map);

        BlogPost::query()->create([
            'title' => $map[$oldSlug]['title'],
            'slug' => $map[$oldSlug]['slug'],
            'excerpt' => 'x',
            'content' => '<p>Body.</p>',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->get('/blog/' . $oldSlug)
            ->assertStatus(301)
            ->assertRedirect('/blog/' . $map[$oldSlug]['slug']);
    }

    public function test_an_unknown_slug_is_still_a_404(): void
    {
        // The redirect map must not turn the whole blog namespace into a redirect.
        $this->get('/blog/no-such-article-exists')->assertNotFound();
    }

    public function test_retopic_rewrites_then_does_nothing(): void
    {
        $map = $this->map();
        $oldSlug = array_key_first($map);

        BlogPost::query()->create([
            'title' => 'Old subject',
            'slug' => $oldSlug,
            'excerpt' => 'old',
            'content' => '<h2>Old</h2><p>Old body.</p>',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->artisan('blog:retopic')->assertSuccessful();

        $post = BlogPost::query()->where('slug', $map[$oldSlug]['slug'])->firstOrFail();
        $this->assertSame($map[$oldSlug]['title'], $post->title);
        $this->assertStringNotContainsString('Old body.', (string) $post->content);
        $this->assertGreaterThan(400, str_word_count(strip_tags((string) $post->content)));

        $this->artisan('blog:retopic')->expectsOutputToContain('nothing to rewrite');
    }
}
