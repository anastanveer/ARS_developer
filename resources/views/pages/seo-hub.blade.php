@php
    $page_title = 'UK SEO Growth Hub';
@endphp
@include('layouts.header')

<style>
    .seo-hub {
        padding: 120px 0;
    }

    .seo-hub__panel {
        background: #f8fbff;
        border: 1px solid #dbe8fb;
        border-radius: 16px;
        padding: 34px;
        margin-bottom: 24px;
    }

    .seo-hub__panel h2 {
        margin-bottom: 14px;
    }

    .seo-hub__qa h3 {
        font-size: 28px;
        line-height: 1.25;
        margin-top: 26px;
        margin-bottom: 10px;
        color: #102a4d;
    }

    .seo-hub__qa p {
        margin: 0 0 12px;
        color: #51678d;
    }

    .seo-hub__list {
        margin: 0;
        padding-left: 18px;
        color: #51678d;
    }

    .seo-hub__cluster-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-top: 22px;
    }

    .seo-hub__cluster-card {
        border: 1px solid #dbe8fb;
        border-radius: 14px;
        padding: 18px 18px 16px;
        background: #ffffff;
    }

    .seo-hub__cluster-card h4 {
        margin-bottom: 8px;
        line-height: 1.3;
        font-size: 22px;
    }

    .seo-hub__cluster-card p {
        margin-bottom: 10px;
        color: #60779d;
    }

    .seo-hub__links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .seo-hub__chip {
        border: 1px solid #c6daf8;
        border-radius: 30px;
        padding: 8px 14px;
        color: #153867;
        font-weight: 600;
        font-size: 14px;
        line-height: 1;
        background: #fff;
    }

    @media (max-width: 991px) {
        .seo-hub__cluster-grid {
            grid-template-columns: 1fr;
        }

        .seo-hub {
            padding: 90px 0;
        }
    }
</style>

<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/shapes/page-header-bg-shape.png') }});"></div>
    <div class="page-header__shape-1">
        <img src="{{ asset('assets/images/shapes/page-header-shape-1.png') }}" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>UK SEO <span>Growth Hub</span></h1>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>UK SEO Growth Hub</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="seo-hub">
    <div class="container">
        <div class="seo-hub__panel seo-hub__qa">
            <h2 class="section-title-two__title">AEO + GEO + EEAT Playbook for UK Buyer-Intent Growth</h2>

            <h3>What is the fastest way to make content AI Overview ready?</h3>
            <p>Start every important section with a direct answer in plain English, then expand with proof, examples, and internal links to service pages and case studies.</p>

            <h3>How should UK service businesses structure topic clusters in 2026?</h3>
            <p>Use one pillar page to cover the full strategy, then publish supporting pages for specific buying questions like pricing, timeline, migration risk, and platform choice.</p>

            <h3>What does full EEAT compliance look like in practice?</h3>
            <p>EEAT is shown with real delivery evidence, clear company identity, named editorial ownership, transparent service scope, and updated technical governance across templates.</p>

            <ul class="seo-hub__list">
                <li>Question-first headings with concise answer blocks for featured snippets.</li>
                <li>Entity-consistent schema for Organization, LocalBusiness, Article, and FAQ.</li>
                <li>Buyer-intent internal links from insights to pricing, service, and contact pages.</li>
                <li>CWV-safe delivery: optimized images, stable layout dimensions, and reduced JS blocking.</li>
            </ul>

            <div class="seo-hub__links">
                <a href="/services" class="seo-hub__chip">UK Software Services</a>
                <a href="/pricing" class="seo-hub__chip">Pricing & Plans</a>
                <a href="/portfolio" class="seo-hub__chip">Case Studies</a>
                <a href="/contact" class="seo-hub__chip">Book Strategy Call</a>
            </div>
        </div>

        @if($pillarPost)
            <div class="seo-hub__panel">
                <h3 class="section-title-two__title">Pillar Guide</h3>
                <div class="seo-hub__cluster-card" style="margin-top: 14px;">
                    <h4><a href="{{ route('blog.show', $pillarPost->slug) }}">{{ $pillarPost->title }}</a></h4>
                    <p>{{ $pillarPost->excerpt }}</p>
                    <a href="{{ route('blog.show', $pillarPost->slug) }}" class="thm-btn thm-btn-two"><span class="icon-right"></span>Read Pillar Guide</a>
                </div>
            </div>
        @endif

        <div class="seo-hub__panel">
            <h3 class="section-title-two__title">Supporting Cluster Articles</h3>
            <div class="seo-hub__cluster-grid">
                @forelse($supportPosts as $post)
                    <article class="seo-hub__cluster-card">
                        <h4><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h4>
                        <p>{{ \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags((string) $post->content), 150, '...') }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="blog-two__read-more-text">Read article</a>
                    </article>
                @empty
                    <article class="seo-hub__cluster-card">
                        <h4>No supporting posts yet</h4>
                        <p>Publish supporting UK buyer-intent posts in admin to complete the cluster.</p>
                    </article>
                @endforelse
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')

