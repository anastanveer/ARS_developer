<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guards against the filler coming back.
 *
 * Two seeders built each article from a template with only the topic phrase
 * substituted, so the same eight or nine sections appeared word for word across
 * every post they wrote — on the live site, 43 of 83 posts were more than half
 * identical to another post, and one was 1,060 of its 1,449 words duplicated.
 */
class BlogBoilerplateTest extends TestCase
{
    use RefreshDatabase;

    /** Headings that only ever introduced template-generated prose. */
    private const FILLER_HEADINGS = [
        'UK buyer decision framework',
        'Cost, timeline, and scope planning',
        'SEO, content, and trust signals',
        'Governance, testing, and post-launch support',
        'Example project scenario',
        'Practical implementation checklist',
        'How ARS Developer Ltd can help',
        'Who this is for',
        'Common mistakes to avoid',
        'What this means in practice',
    ];

    public function test_seeded_posts_carry_no_templated_sections(): void
    {
        $this->seed(\Database\Seeders\BlogPostSeeder::class);
        $this->seed(\Database\Seeders\SeoBlogPost2026Seeder::class);

        foreach (BlogPost::all() as $post) {
            foreach (self::FILLER_HEADINGS as $heading) {
                $this->assertStringNotContainsString(
                    '<h2>' . $heading . '</h2>',
                    (string) $post->content,
                    "Post {$post->slug} still carries the templated \"{$heading}\" section."
                );
            }
        }
    }

    public function test_strip_command_removes_filler_and_then_does_nothing(): void
    {
        BlogPost::query()->create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'excerpt' => 'x',
            'content' => '<h2>Quick Answer</h2><p>Something specific to this post.</p>'
                . '<h2>UK buyer decision framework</h2>'
                . '<p>Most UK teams do not need more vague technology options.</p>'
                . '<p>A stronger decision framework also includes commercial fit.</p>'
                . '<h2>Frequently Asked Questions</h2><h3>Q</h3><p>A</p>',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->artisan('blog:strip-boilerplate')->assertSuccessful();

        $content = (string) BlogPost::query()->where('slug', 'test-post')->firstOrFail()->content;

        $this->assertStringNotContainsString('UK buyer decision framework', $content);
        $this->assertStringNotContainsString('Most UK teams do not need', $content);
        // Everything that was about this post specifically has to survive.
        $this->assertStringContainsString('Something specific to this post.', $content);
        $this->assertStringContainsString('Frequently Asked Questions', $content);

        // Re-running must be a no-op, because it runs on every deploy.
        $this->artisan('blog:strip-boilerplate')->expectsOutputToContain('nothing to remove');
    }

    public function test_strip_command_leaves_untemplated_posts_untouched(): void
    {
        $original = '<h2>A real heading</h2><p>Prose that no template produced.</p>';

        BlogPost::query()->create([
            'title' => 'Genuine Post',
            'slug' => 'genuine-post',
            'excerpt' => 'x',
            'content' => $original,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->artisan('blog:strip-boilerplate')->assertSuccessful();

        // Byte-for-byte: parsing and re-serialising the HTML would rewrite entities
        // and whitespace, so a post with nothing to remove must never be saved.
        $this->assertSame(
            $original,
            (string) BlogPost::query()->where('slug', 'genuine-post')->firstOrFail()->content
        );
    }
}
