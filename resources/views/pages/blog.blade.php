@php
    $page_title = 'Blog';
@endphp
@include('layouts.header')
<style>
    .blog-search-row {
        margin-bottom: 26px;
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
</style>

<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/shapes/page-header-bg-shape.png') }});"></div>
    <div class="page-header__shape-1">
        <img src="{{ asset('assets/images/shapes/page-header-shape-1.png') }}" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>Our <span>Blogs</span></h1>
            <div class="seo-heading-ladder" aria-hidden="true">
                <h2 class="seo-hidden-heading">Core page sections</h2>
                <h3 class="seo-hidden-heading">Section details</h3>
                <h4 class="seo-hidden-heading">Supporting information</h4>
                <h5 class="seo-hidden-heading">Additional notes</h5>
                <h6 class="seo-hidden-heading">Reference points</h6>
            </div>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>Our Blogs</li>
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
                    <input type="text" name="q" value="{{ $query }}" class="blog-search-input" placeholder="Search blog by SEO, CRM, WordPress, ecommerce...">
                    <button type="submit" class="thm-btn"><span class="icon-right"></span> Search</button>
                    @if($query !== '')
                        <a href="{{ route('blog.index') }}" class="thm-btn thm-btn-two">Reset</a>
                    @endif
                </form>
            </div>
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
                    {{ $posts->links() }}
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
