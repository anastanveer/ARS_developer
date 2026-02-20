@php
    $page_title = $post->title;
    $readMinutes = max(2, (int) ceil(str_word_count(strip_tags((string) $post->content)) / 220));
@endphp
@include('layouts.header')
<style>
    .blog-details__title-1 {
        line-height: 1.2;
        text-wrap: balance;
        margin-bottom: 20px;
    }

    .blog-details__insight {
        background: linear-gradient(180deg, #f5f9ff 0%, #eef5ff 100%);
        border: 1px solid #d9e8ff;
        border-radius: 12px;
        padding: 20px 22px;
        margin-bottom: 24px;
    }

    .blog-details__insight h3 {
        font-size: 20px;
        margin: 0 0 8px;
        color: #102a4d;
    }

    .blog-details__insight p {
        margin: 0;
        color: #4b6187;
    }

    .blog-details__article {
        color: #4f6386;
        line-height: 1.9;
        font-size: 17px;
    }

    .blog-details__article h2,
    .blog-details__article h3 {
        color: #102a4d;
        margin-top: 26px;
        margin-bottom: 12px;
        line-height: 1.28;
    }

    .blog-details__article ul,
    .blog-details__article ol {
        margin: 0 0 18px 20px;
    }

    .blog-details__article a {
        color: #117be8;
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .blog-details__eeat {
        margin: 20px 0 24px;
        border: 1px solid #d6e6fb;
        background: #f8fbff;
        border-radius: 12px;
        padding: 16px 18px;
    }

    .blog-details__eeat strong {
        color: #0f2a4d;
    }

    .blog-details__cluster-links {
        margin-top: 22px;
        border: 1px solid #d6e6fb;
        background: #ffffff;
        border-radius: 12px;
        padding: 18px;
    }

    .blog-details__cluster-links h3 {
        margin-bottom: 10px;
        color: #102a4d;
    }

    .blog-details__cluster-links-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .blog-details__cluster-links-list a {
        display: inline-flex;
        align-items: center;
        padding: 8px 14px;
        border-radius: 40px;
        border: 1px solid #cae0ff;
        color: #123561;
        font-weight: 600;
        line-height: 1.1;
        text-decoration: none;
        transition: all .2s ease;
    }

    .blog-details__cluster-links-list a:hover {
        background: #0f7fe9;
        border-color: #0f7fe9;
        color: #fff;
    }

    .sidebar__search-form.blog-search-ui {
        position: relative;
        border: 1px solid #d3e2fb;
        border-radius: 12px;
        background: #f8fbff;
        overflow: hidden;
        display: block;
        padding: 6px;
    }

    .sidebar__search-form.blog-search-ui input[type="search"] {
        border: 1px solid #cfe0fb;
        height: 52px;
        width: 100%;
        padding: 0 58px 0 14px;
        color: #12315b;
        font-size: 15px;
        border-radius: 10px;
        background: #fff;
    }

    .sidebar__search-form.blog-search-ui button {
        width: 40px;
        height: 40px;
        border: 0;
        background: #0f7fe9;
        color: #fff;
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border-radius: 10px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        line-height: 1;
        padding: 0;
    }

    .sidebar__search-form.blog-search-ui button i {
        display: block;
        line-height: 1;
    }

    .sidebar__post-content h3 {
        line-height: 1.35;
    }

    .sidebar__post-content-meta {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 6px;
        line-height: 1;
    }

    .sidebar__post-content-meta i {
        font-size: 13px;
        line-height: 1;
    }
</style>

<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/shapes/page-header-bg-shape.png') }});"></div>
    <div class="page-header__shape-1">
        <img src="{{ asset('assets/images/shapes/page-header-shape-1.png') }}" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>{{ \Illuminate\Support\Str::limit($post->title, 70) }}</h1><div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><span></span></li>
                    <li>{{ \Illuminate\Support\Str::limit($post->title, 55) }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="blog-details">
    <div class="container">
        <div class="row">
            @php
                $heroImage = $post->featured_image
                    ? (str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset(ltrim($post->featured_image, '/')))
                    : asset('assets/images/blog/blog-details-img-1.jpg');
            @endphp
            <div class="col-xl-8 col-lg-7">
                <article class="blog-details__left">
                    <div class="blog-details__img">
                        <img src="{{ $heroImage }}" alt="{{ $post->featured_image_alt ?: $post->title }}">
                    </div>
                    <div class="blog-details__content">
                        <ul class="blog-details__meta list-unstyled">
                            <li>
                                <div class="content">
                                    <p>Post By</p>
                                    <h4>{{ $post->author_name ?: 'ARS Developer Team' }}</h4>
                                </div>
                            </li>
                            <li>
                                <div class="icon"><span class="fas fa-calendar-alt"></span></div>
                                <div class="content">
                                    <p>Published</p>
                                    <h4>{{ optional($post->published_at)->format('F d, Y') ?: $post->created_at->format('F d, Y') }}</h4>
                                </div>
                            </li>
                            <li>
                                <div class="icon"><span class="fas fa-clock"></span></div>
                                <div class="content">
                                    <p>Read Time</p>
                                    <h4>{{ $readMinutes }} min</h4>
                                </div>
                            </li>
                            <li>
                                <div class="icon"><span class="fas fa-folder-open"></span></div>
                                <div class="content">
                                    <h4>{{ $post->category ?: 'Business Growth' }}</h4>
                                </div>
                            </li>
                        </ul>

                        <h2 class="blog-details__title-1">{{ $post->title }}</h2>

                        <div class="blog-details__eeat">
                            <p>
                                <strong>Reviewed by:</strong> {{ $post->author_name ?: 'ARS Developer Editorial Team' }} |
                                <strong>Updated:</strong> {{ optional($post->updated_at)->format('d M Y') }} |
                                <strong>UK Focus:</strong> Buyer-intent SEO, web delivery, and measurable conversion growth.
                            </p>
                        </div>

                        @if(!empty($post->excerpt))
                            <div class="blog-details__insight">
                                <h3>Quick Summary</h3>
                                <p>{{ $post->excerpt }}</p>
                            </div>
                        @endif

                        @php $contentHasHtml = $post->content && $post->content !== strip_tags($post->content); @endphp
                        <div class="blog-details__article">
                            @if($contentHasHtml)
                                {!! $post->content !!}
                            @else
                                {!! nl2br(e((string) $post->content)) !!}
                            @endif
                        </div>

                        <div class="blog-details__cluster-links">
                            <h3>Next Step Resources</h3>
                            <div class="blog-details__cluster-links-list">
                                <a href="/uk-growth-hub">UK SEO Growth Hub</a>
                                <a href="/services">Service Solutions</a>
                                <a href="/portfolio">Case Studies</a>
                                <a href="/pricing">Pricing Plans</a>
                                <a href="/contact">Book Strategy Call</a>
                            </div>
                        </div>

                        <div class="blog-details__tag-and-share" style="margin-top:30px;">
                            <div class="blog-details__tag">
                                <span class="blog-details__tag-title">Tags:</span>
                                <ul class="blog-details__tag-list list-unstyled">
                                    <li>
                                        <a href="{{ route('blog.index', ['q' => $post->category]) }}">{{ $post->category ?: 'UK Growth' }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="blog-details__share">
                                <span class="blog-details__share-title">Share:</span>
                                <div class="blog-details__share-list">
                                    @php
                                        $shareUrl = urlencode(route('blog.show', $post->slug));
                                        $shareText = urlencode($post->title);
                                    @endphp
                                    <a target="_blank" rel="noopener" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" aria-label="Share on LinkedIn" title="Share on LinkedIn"><span class="icon-linkedin"></span></a>
                                    <a target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" aria-label="Share on Facebook" title="Share on Facebook"><span class="icon-facebook"></span></a>
                                    <a target="_blank" rel="noopener" href="https://www.instagram.com/arsdeveloperuk/" aria-label="Open Instagram" title="Open Instagram"><span class="fab fa-instagram"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="sidebar">
                    <div class="sidebar__single sidebar__search">
                        <form method="get" action="{{ route('blog.index') }}" class="sidebar__search-form blog-search-ui">
                            <input type="search" name="q" placeholder="Search UK SEO, CRM, Web topics..." aria-label="Search blog articles">
                            <button type="submit" aria-label="Search blog"><i class="fa fa-search"></i></button>
                        </form>
                    </div>

                    <div class="sidebar__single sidebar__post">
                        <h3 class="sidebar__title">Recent Posts</h3>
                        <ul class="sidebar__post-list list-unstyled">
                            @forelse($recentPosts as $recent)
                                @php
                                    $recentImage = $recent->featured_image
                                        ? (str_starts_with($recent->featured_image, 'http') ? $recent->featured_image : asset(ltrim($recent->featured_image, '/')))
                                        : asset('assets/images/blog/lp-1-1.jpg');
                                @endphp
                                <li>
                                    <div class="sidebar__post-image">
                                        <img src="{{ $recentImage }}" alt="{{ $recent->title }}">
                                    </div>
                                    <div class="sidebar__post-content">
                                        <h3>
                                            <span class="sidebar__post-content-meta"><i class="fa fa-calendar-alt"></i>{{ optional($recent->published_at)->format('d M Y') ?: $recent->created_at->format('d M Y') }}</span>
                                            <a href="{{ route('blog.show', $recent->slug) }}">{{ \Illuminate\Support\Str::limit($recent->title, 50) }}</a>
                                        </h3>
                                    </div>
                                </li>
                            @empty
                                <li><div class="sidebar__post-content"><h3>No recent posts</h3></div></li>
                            @endforelse
                        </ul>
                    </div>

                    @if($relatedPosts->isNotEmpty())
                        <div class="sidebar__single sidebar__category">
                            <h3 class="sidebar__title">Related Posts</h3>
                            <ul class="sidebar__category-list list-unstyled">
                                @foreach($relatedPosts as $related)
                                    <li><a href="{{ route('blog.show', $related->slug) }}">{{ \Illuminate\Support\Str::limit($related->title, 60) }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
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
