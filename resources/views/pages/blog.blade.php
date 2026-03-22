@php
    $page_title = 'AI SEO Blog | UK Software Development, AEO, GEO and Growth Insights';
    $seoOverride = [
        'title' => 'AI SEO Blog UK | AEO, GEO, AI Search and Software Growth Insights',
        'description' => 'Read AI SEO, AEO, GEO, technical SEO, software growth, CRM, ecommerce, and conversion insights for UK businesses building search visibility and lead generation.',
        'keywords' => 'ai seo blog uk, aeo blog uk, geo seo uk, answer engine optimization uk, technical seo blog uk, software growth blog uk, ecommerce seo uk',
        'related_links' => [
            '/services',
            '/search-engine-optimization',
            '/software-development',
            '/pricing',
            '/contact',
        ],
    ];
@endphp
@include('layouts.header')
<style>
    .blog-search-row {
        margin-bottom: 26px;
    }

    .blog-cluster-banner {
        border: 1px solid #d4e3fb;
        background: linear-gradient(180deg, #f8fbff 0%, #eff6ff 100%);
        border-radius: 16px;
        padding: 18px 20px;
        margin-bottom: 30px;
    }

    .blog-cluster-banner h2 {
        font-size: 28px;
        line-height: 1.2;
        margin: 0 0 8px;
        color: #102a4d;
    }

    .blog-cluster-banner p {
        margin: 0 0 14px;
        color: #536987;
        max-width: 820px;
    }

    .blog-search-form {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        border-radius: 16px;
        background: linear-gradient(180deg, #f8fbff 0%, #f2f8ff 100%);
        border: 1px solid #d8e6fb;
        box-shadow: 0 10px 24px rgba(16, 42, 77, 0.06);
    }

    .blog-search-form .blog-search-input {
        width: 100%;
        border: 1px solid #cfe0fb;
        border-radius: 12px;
        height: 54px;
        padding: 0 18px;
        color: #133158;
        font-size: 16px;
        background: #fff;
        outline: none;
    }

    .blog-search-form .blog-search-input:focus {
        border-color: #1183ea;
        box-shadow: 0 0 0 3px rgba(17, 131, 234, 0.14);
    }

    .blog-search-form .thm-btn {
        min-width: 138px;
        height: 54px;
        padding: 0 22px;
        border: 0;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        white-space: nowrap;
        line-height: 1;
    }

    .blog-search-form .thm-btn .icon-right {
        margin-right: 8px;
        font-size: 12px;
    }

    .blog-search-form .thm-btn-two {
        min-width: 110px;
    }

    @media (max-width: 767px) {
        .blog-search-form {
            flex-wrap: wrap;
        }

        .blog-search-form .thm-btn,
        .blog-search-form .thm-btn-two {
            min-width: 100%;
        }
    }

    .blog-list__pagination {
        width: 100%;
        margin-top: 26px;
        display: flex;
        justify-content: center;
    }

    .blog-pagination {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        padding: 14px 18px;
        border: 1px solid #d7e6fb;
        border-radius: 16px;
        background: linear-gradient(180deg, #f8fbff 0%, #f1f7ff 100%);
        box-shadow: 0 12px 26px rgba(16, 42, 77, 0.06);
    }

    .blog-pagination__link,
    .blog-pagination__current,
    .blog-pagination__dots {
        min-width: 42px;
        height: 42px;
        padding: 0 14px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 700;
        line-height: 1;
        text-decoration: none;
    }

    .blog-pagination__link {
        color: #123561;
        background: #fff;
        border: 1px solid #d2e3fb;
        transition: all .2s ease;
    }

    .blog-pagination__link:hover {
        color: #fff;
        background: #0f7fe9;
        border-color: #0f7fe9;
    }

    .blog-pagination__current {
        color: #fff;
        background: linear-gradient(135deg, #0f7fe9 0%, #34a0ff 100%);
        border: 1px solid transparent;
        box-shadow: 0 12px 22px rgba(15, 127, 233, 0.22);
    }

    .blog-pagination__dots {
        color: #6d84a8;
        background: transparent;
        min-width: auto;
        padding: 0 2px;
    }

    .blog-pagination__nav {
        min-width: 108px;
    }
</style>

<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/shapes/page-header-bg-shape.png') }});"></div>
    <div class="page-header__shape-1">
        <img src="{{ asset('assets/images/shapes/page-header-shape-1.png') }}" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>AI SEO, AEO and <span>Growth Blogs</span></h1><div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>AI SEO and Growth Blogs</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="blog-page">
    <div class="container">
        <div class="row blog-search-row">
            <div class="col-xl-9 col-lg-10">
                <form method="get" action="{{ route('blog.index') }}" class="blog-search-form">
                    <input type="text" name="q" value="{{ $query }}" class="blog-search-input" placeholder="Search blog by AI SEO, AEO, GEO, CRM, SaaS, ecommerce...">
                    <button type="submit" class="thm-btn"><span class="icon-right"></span> Search</button>
                    @if($query !== '')
                        <a href="{{ route('blog.index') }}" class="thm-btn thm-btn-two">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="blog-cluster-banner">
            <h2>Start with the UK AI SEO Growth Hub</h2>
            <p>Read the pillar guide first, then go deeper into supporting posts for AI SEO, AEO, GEO, EEAT, technical SEO, and conversion-focused implementation.</p>
            <a href="{{ route('seo.hub') }}" class="thm-btn thm-btn-two"><span class="icon-right"></span>Open Pillar Guide</a>
        </div>

        <div class="row">
            @forelse($posts as $post)
                @php
                    $image = $post->featured_image
                        ? (str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset(ltrim($post->featured_image, '/')))
                        : asset('assets/images/blog/blog-2-1.jpg');
                    $summary = $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $post->content), 140, '...');
                @endphp
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="blog-two__single">
                        <div class="blog-two__img">
                            <img src="{{ $image }}" alt="{{ $post->featured_image_alt ?: $post->title }}" loading="lazy">
                            <div class="blog-two__plus">
                                <a href="{{ route('blog.show', $post->slug) }}"><span class="icon-plus"></span></a>
                            </div>
                        </div>
                        <div class="blog-two__content">
                            <div class="blog-two__date">
                                <p>{{ optional($post->published_at)->format('F d, Y') ?: $post->created_at->format('F d, Y') }} <span class="icon-calendar"></span></p>
                            </div>
                            <div class="blog-two__content-inner">
                                <ul class="list-unstyled blog-two__tag">
                                    <li><p>{{ $post->category ?: 'Business Growth' }}</p></li>
                                    <li><p>&#9733;</p></li>
                                    <li><p>UK Insights</p></li>
                                </ul>
                                <h3 class="blog-two__title">
                                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="blog-two__text">{{ \Illuminate\Support\Str::limit($summary, 120, '...') }}</p>
                            </div>
                            <div class="blog-two__read-more">
                                <div class="blog-two__read-more-line"></div>
                                <a href="{{ route('blog.show', $post->slug) }}" class="blog-two__read-more-text">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="blog-two__single" style="padding: 40px; text-align: center;">
                        <h3>No blog posts found</h3>
                        <p>Try a different search term or check back soon for fresh updates.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="row">
                <div class="blog-list__pagination">
                    <nav class="blog-pagination" aria-label="Blog pagination">
                        @if ($posts->onFirstPage())
                            <span class="blog-pagination__link blog-pagination__nav" aria-disabled="true">Prev</span>
                        @else
                            <a class="blog-pagination__link blog-pagination__nav" href="{{ $posts->previousPageUrl() }}" rel="prev">Prev</a>
                        @endif

                        @for ($pageNumber = 1; $pageNumber <= $posts->lastPage(); $pageNumber++)
                            @if ($pageNumber === $posts->currentPage())
                                <span class="blog-pagination__current" aria-current="page">{{ $pageNumber }}</span>
                            @else
                                <a class="blog-pagination__link" href="{{ $posts->url($pageNumber) }}">{{ $pageNumber }}</a>
                            @endif
                        @endfor

                        @if ($posts->hasMorePages())
                            <a class="blog-pagination__link blog-pagination__nav" href="{{ $posts->nextPageUrl() }}" rel="next">Next</a>
                        @else
                            <span class="blog-pagination__link blog-pagination__nav" aria-disabled="true">Next</span>
                        @endif
                    </nav>
                </div>
            </div>
        @endif
    </div>
</section>

<section class="newsletter-two">
    <div class="newsletter-two__big-text">Subscribe Newsletter</div>
    <div class="container">
        <div class="newsletter-two__inner">
            <div class="newsletter-two__left">
                <h2 class="newsletter-two__title">Subscribe Newsletter</h2>
                <p class="newsletter-two__text">Get the latest SEO tips and software insights straight to your
                    <br> inbox. Stay informed</p>
            </div>
            <div class="newsletter-two__right">
                <form class="newsletter-two__form newsletter-form-validated" action="{{ route('contact.submit') }}" method="post">
                    @csrf
                    <input type="hidden" name="form_type" value="newsletter">
                    <input type="hidden" name="subject" value="Newsletter Subscription Request">
                    <input type="hidden" name="message" value="Please add me to ARSDeveloper updates.">
                    <div class="newsletter-two__input">
                        <input type="email" name="email" placeholder="Enter Your Email" required>
                    </div>
                    <button type="submit" class="newsletter-two__btn">Subscribe</button>
                </form>
                <div class="result"></div>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')
