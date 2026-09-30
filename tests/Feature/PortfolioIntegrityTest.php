<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use Database\Seeders\PortfolioCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guards the portfolio against the two faults that got the site rejected from
 * AdSense, and against the one the first attempt at fixing it introduced.
 *
 * The catalogue named major brands as clients. Removing them from a list whose
 * screenshots were addressed by array position then handed every surviving project
 * the previous entry's screenshot, so Kylie Cosmetics' homepage rendered under
 * another client's name — a worse version of the claim being removed. Screenshots
 * are now bound to the URL, and these tests fail if either fault returns.
 */
class PortfolioIntegrityTest extends TestCase
{
    use RefreshDatabase;

    /** Companies with their own in-house teams, plus links that are not projects. */
    private const UNSUPPORTABLE = [
        'kyliecosmetics.com',
        'gymshark.com',
        'colourpop.com',
        'allbirds.com',
        'loxclubapp.com',
        'butter.us',
        'adaline.ai',
        'canva.com/design',
        'utm_source=chatgpt.com',
        'wixsite.com',
        'webflow.io',
    ];

    private function seedCatalogue(): void
    {
        $this->seed(PortfolioCatalogSeeder::class);
    }

    public function test_catalogue_claims_no_client_it_cannot_support(): void
    {
        $this->seedCatalogue();

        foreach (Portfolio::all() as $portfolio) {
            $haystack = strtolower($portfolio->project_url . ' ' . $portfolio->client_name);

            foreach (self::UNSUPPORTABLE as $needle) {
                $this->assertStringNotContainsString(
                    $needle,
                    $haystack,
                    "Portfolio entry {$portfolio->slug} claims {$needle}."
                );
            }
        }
    }

    public function test_every_screenshot_exists_on_disk(): void
    {
        $this->seedCatalogue();

        foreach (Portfolio::all() as $portfolio) {
            $this->assertFileExists(
                public_path($portfolio->image_path),
                "Portfolio entry {$portfolio->slug} points at a missing screenshot."
            );
        }
    }

    public function test_removed_brand_screenshots_are_not_shipped(): void
    {
        // These files are the brand homepages. If they come back, the next removal
        // from the middle of a list can silently republish them under a new name.
        foreach ([
            'assets/Portfolio/Shopify/1.avif',
            'assets/Portfolio/Shopify/2.avif',
            'assets/Portfolio/Shopify/3.avif',
            'assets/Portfolio/Shopify/7.avif',
            'assets/Portfolio/Custom/3.avif',
        ] as $path) {
            $this->assertFileDoesNotExist(public_path($path), "{$path} is still shipped.");
        }
    }

    public function test_detail_pages_are_not_indexed(): void
    {
        $this->seedCatalogue();
        $portfolio = Portfolio::query()->firstOrFail();

        $this->get('/portfolio-details/' . $portfolio->slug)
            ->assertOk()
            ->assertSee('name="robots" content="noindex, follow"', false);
    }

    public function test_detail_pages_do_not_load_adsense(): void
    {
        $this->seedCatalogue();
        $portfolio = Portfolio::query()->firstOrFail();

        $this->get('/portfolio-details/' . $portfolio->slug)
            ->assertOk()
            ->assertDontSee('adsbygoogle.js', false);
    }

    public function test_unknown_slug_redirects_instead_of_serving_another_project(): void
    {
        $this->seedCatalogue();

        // This used to return 200 with the first project, which made
        // /portfolio-details/<anything> an unbounded set of duplicate pages.
        $this->get('/portfolio-details/no-such-project')
            ->assertRedirect('/portfolio');
    }

    public function test_static_tab_urls_redirect_to_the_listing(): void
    {
        $this->seedCatalogue();

        $this->get('/portfolio-details?tab=shopify&item=1')
            ->assertRedirect('/portfolio');
    }

    public function test_sitemap_offers_the_listing_but_not_the_detail_pages(): void
    {
        $this->seedCatalogue();

        $response = $this->get('/sitemaps/portfolio.xml')->assertOk();
        $body = $response->getContent();

        $this->assertStringContainsString('/portfolio<', $body);
        $this->assertStringNotContainsString('/portfolio-details/', $body);
    }

    public function test_listing_page_stays_indexable(): void
    {
        $this->seedCatalogue();

        $this->get('/portfolio')
            ->assertOk()
            ->assertDontSee('name="robots" content="noindex', false);
    }
}
