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

    .blog-details__img {
        border-radius: 18px;
        overflow: hidden;
    }

    .blog-details__img img {
        width: 100%;
        height: clamp(300px, 38vw, 500px);
        object-fit: cover;
        object-position: center;
        display: block;
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

    .blog-details__toc,
    .blog-details__trust-box {
        margin: 0 0 24px;
        border: 1px solid #d6e6fb;
        background: #f8fbff;
        border-radius: 12px;
        padding: 18px;
    }

    .blog-details__toc h3,
    .blog-details__trust-box h3 {
        margin: 0 0 10px;
        color: #102a4d;
        font-size: 20px;
    }

    .blog-details__toc ul,
    .blog-details__trust-list {
        margin: 0;
        padding-left: 18px;
        color: #4f6386;
    }

    .blog-details__toc li + li,
    .blog-details__trust-list li + li {
        margin-top: 8px;
    }

    .blog-details__toc a {
        color: #123561;
        text-decoration: none;
    }

    .blog-details__toc a:hover {
        color: #0f7fe9;
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

    .sidebar__post-list {
        max-height: 468px;
        overflow-y: auto;
        padding-right: 6px;
    }

    .sidebar__post-list::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar__post-list::-webkit-scrollbar-track {
        background: #edf4ff;
        border-radius: 999px;
    }

    .sidebar__post-list::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #127de8 0%, #0d5fb5 100%);
        border-radius: 999px;
    }

    .sidebar__post-list li {
        padding-right: 2px;
    }

    .sidebar__post-list li + li {
        margin-top: 14px;
    }

    .sidebar__topic-group {
        padding: 14px;
        border: 1px solid #dce7f9;
        border-radius: 16px;
        background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
    }

    .sidebar__topic-group--active {
        border-color: #a8ceff;
        box-shadow: 0 14px 26px rgba(15, 127, 233, 0.08);
    }

    .sidebar__topic-group-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .sidebar__topic-group-title {
        margin: 0;
        color: #102a4d;
        font-size: 18px;
        line-height: 1.25;
    }

    .sidebar__topic-group-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(15, 127, 233, 0.1);
        color: #0f63bd;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .sidebar__topic-posts li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .sidebar__topic-posts li + li {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e4eefb;
    }

    .sidebar__post-image {
        flex: 0 0 88px;
        width: 88px;
        max-width: 88px;
        height: 72px;
        border-radius: 10px;
        overflow: hidden;
    }

    .sidebar__post-image-link {
        display: block;
        border-radius: 10px;
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .sidebar__post-image-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(16, 42, 77, 0.18);
    }

    .sidebar__post-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
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

    .sidebar__post-content a {
        display: inline-block;
    }

    .sidebar__single.sidebar__guide {
        background:
            radial-gradient(circle at top right, rgba(15, 127, 233, 0.16), transparent 34%),
            linear-gradient(180deg, #fbfdff 0%, #f1f7ff 100%);
        border: 1px solid #d8e6fb;
        border-radius: 18px;
        box-shadow: 0 18px 38px rgba(16, 42, 77, 0.08);
        overflow: hidden;
        padding: 22px;
    }

    .sidebar__guide-head {
        margin-bottom: 18px;
        padding: 4px 4px 18px;
        border-bottom: 1px solid #dce8f8;
    }

    .sidebar__guide-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(15, 127, 233, 0.1);
        color: #0f63bd;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .sidebar__guide-head .sidebar__title {
        margin-bottom: 6px;
    }

    .sidebar__guide-text {
        margin: 0;
        color: #5f7395;
        line-height: 1.6;
        font-size: 15px;
    }

    .sidebar__guide-list li + li {
        margin-top: 12px;
    }

    .sidebar__guide-list a {
        color: #123561;
        font-weight: 600;
        line-height: 1.45;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #d9e7fb;
        box-shadow: 0 10px 24px rgba(17, 53, 97, 0.05);
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, color .2s ease;
        text-decoration: none;
    }

    .sidebar__guide-list {
        padding: 4px;
    }

    .sidebar__guide-link-label {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .sidebar__guide-link-index {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #117be8 0%, #34a0ff 100%);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 30px;
        font-size: 13px;
        font-weight: 700;
    }

    .sidebar__guide-link-text {
        display: block;
    }

    .sidebar__guide-link-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #edf5ff;
        color: #0f7fe9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        font-size: 13px;
    }

    .sidebar__guide-list a:hover {
        transform: translateY(-2px);
        border-color: #9cc8ff;
        box-shadow: 0 16px 28px rgba(17, 53, 97, 0.12);
        color: #0f63bd;
    }

    @media (max-width: 767px) {
        .blog-details__author-copy,
        .blog-details__comment-form-row,
        .blog-details__comment-form-row--triple {
            grid-template-columns: 1fr;
        }

        .blog-details__author-box,
        .blog-details__comments {
            padding: 20px 16px;
        }

        .blog-details__author-title,
        .blog-details__comments-title {
            font-size: 24px;
        }

        .blog-details__comment-form .btn {
            width: 100%;
            justify-self: stretch;
        }

        .blog-details__comment-form textarea {
            min-height: 160px;
        }

        .sidebar__single.sidebar__guide {
            padding: 18px;
        }

        .sidebar__guide-head,
        .sidebar__guide-list {
            padding-left: 0;
            padding-right: 0;
        }
    }

    .blog-details__share-list {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .blog-details__share-list button,
    .blog-details__share-list a {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: 1px solid #d7e3f7;
        background: #fff;
        color: #123561;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .2s ease;
        flex: 0 0 46px;
        padding: 0;
        margin: 0;
        box-shadow: none;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        overflow: hidden;
        vertical-align: middle;
        text-decoration: none;
    }

    .blog-details__share-list button:hover,
    .blog-details__share-list a:hover {
        background: #0f7fe9;
        border-color: #0f7fe9;
        color: #fff;
    }

    .blog-details__share-list button span,
    .blog-details__share-list a span {
        line-height: 1;
    }

    .blog-details__share-feedback {
        display: inline-block;
        margin-left: 10px;
        color: #0f7fe9;
        font-size: 14px;
        font-weight: 600;
    }

    @media (max-width: 767px) {
        .blog-details__author-copy,
        .blog-details__comment-form-row,
        .blog-details__comment-form-row--triple {
            grid-template-columns: 1fr;
        }

        .blog-details__author-box,
        .blog-details__comments {
            padding: 20px 16px;
        }

        .blog-details__author-title,
        .blog-details__comments-title {
            font-size: 24px;
        }

        .blog-details__comment-form .btn {
            width: 100%;
            justify-self: stretch;
        }

        .blog-details__comment-form textarea {
            min-height: 160px;
        }

        .blog-details__img img {
            height: clamp(220px, 52vw, 320px);
        }
    }
    .blog-details__author-box,
    .blog-details__comments {
        margin: 28px 0 0;
        border: 1px solid #d6e6fb;
        background: #ffffff;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 18px 45px rgba(16, 42, 77, 0.06);
    }

    .blog-details__author-head,
    .blog-details__comments-head {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        align-items: flex-start;
        margin-bottom: 18px;
    }

    .blog-details__author-kicker,
    .blog-details__comments-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #0f7fe9;
        margin-bottom: 10px;
    }

    .blog-details__author-title,
    .blog-details__comments-title {
        margin: 0;
        color: #102a4d;
        font-size: 30px;
        line-height: 1.15;
    }

    .blog-details__author-copy {
        display: grid;
        grid-template-columns: 92px 1fr;
        gap: 18px;
        align-items: start;
    }

    .blog-details__author-avatar {
        width: 92px;
        height: 92px;
        border-radius: 22px;
        background: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
        border: 1px solid #d6e6fb;
        display: grid;
        place-items: center;
        overflow: hidden;
    }

    .blog-details__author-avatar img {
        width: 62px;
        height: 62px;
        object-fit: contain;
    }

    .blog-details__author-role {
        margin: 0 0 10px;
        color: #0f7fe9;
        font-weight: 700;
    }

    .blog-details__author-summary {
        margin: 0 0 12px;
        color: #4f6386;
    }

    .blog-details__author-points {
        margin: 0;
        padding-left: 18px;
        color: #4f6386;
    }

    .blog-details__author-points li + li {
        margin-top: 8px;
    }

    .blog-details__comments-note {
        margin: 6px 0 0;
        color: #6f84a6;
        font-size: 14px;
    }

    .blog-details__comment-list {
        display: grid;
        gap: 18px;
        margin: 0 0 24px;
    }

    .blog-details__comment-item {
        border: 1px solid #deebff;
        background: linear-gradient(180deg, #fbfdff 0%, #f5f9ff 100%);
        border-radius: 16px;
        padding: 18px;
    }

    .blog-details__comment-meta {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .blog-details__comment-meta strong {
        color: #102a4d;
    }

    .blog-details__comment-meta span,
    .blog-details__comment-meta a {
        color: #6f84a6;
        font-size: 14px;
        text-decoration: none;
    }

    .blog-details__comment-body {
        color: #4f6386;
        margin: 0;
        white-space: pre-line;
        line-height: 1.8;
    }

    .blog-details__comment-form {
        display: grid;
        gap: 16px;
    }

    .blog-details__comment-form-row {
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .blog-details__comment-form-row--triple {
        grid-template-columns: 1.1fr 1.1fr .8fr;
    }

    .blog-details__comment-form label {
        display: block;
        margin-bottom: 8px;
        color: #123561;
        font-weight: 700;
    }

    .blog-details__comment-form input,
    .blog-details__comment-form textarea {
        width: 100%;
        border: 1px solid #d3e2fb;
        border-radius: 14px;
        background: #fff;
        color: #123561;
        padding: 15px 16px;
        font-size: 15px;
    }

    .blog-details__comment-form textarea {
        min-height: 180px;
        resize: vertical;
    }

    .blog-details__captcha-box {
        border: 1px solid #d6e6fb;
        background: #f8fbff;
        border-radius: 14px;
        padding: 16px;
    }

    .blog-details__captcha-box p {
        margin: 0 0 10px;
        color: #4f6386;
    }

    .blog-details__checkbox {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #4f6386;
        font-weight: 500;
    }

    .blog-details__checkbox input {
        width: 18px;
        height: 18px;
        margin: 0;
    }

    .blog-details__comment-form .btn {
        justify-self: start;
        border: 0;
        border-radius: 999px;
        padding: 14px 26px;
        background: linear-gradient(135deg, #0f63b8 0%, #268be2 100%);
        color: #fff;
        font-weight: 700;
    }

    .blog-details__form-alert {
        border-radius: 14px;
        padding: 14px 16px;
        font-weight: 600;
    }

    .blog-details__form-alert--success {
        background: #eefbf3;
        border: 1px solid #b8e5c7;
        color: #166534;
    }

    .blog-details__form-alert--error {
        background: #fff2f2;
        border: 1px solid #f3c4c4;
        color: #9f1d1d;
    }

    .blog-details__honeypot {
        position: absolute;
        left: -9999px;
        opacity: 0;
        pointer-events: none;
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
                        <h2 class="seo-hidden-heading">Blog post overview and metadata</h2>
                        <h3 class="seo-hidden-heading">Author, publish date, read time, and category</h3>
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

                        @if(!empty($tableOfContents))
                            <div class="blog-details__toc">
                                <h3>In This Guide</h3>
                                <ul>
                                    @foreach($tableOfContents as $item)
                                        <li><a href="#{{ $item['id'] }}">{{ $item['label'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="blog-details__trust-box">
                            <h3>Why This Article Is Trustworthy</h3>
                            <ul class="blog-details__trust-list">
                                <li>Reviewed by the ARS Developer editorial team for UK business relevance.</li>
                                <li>Structured around buyer-intent SEO, technical delivery, and measurable conversion outcomes.</li>
                                <li>Connected to related service pages, pricing guidance, and supporting articles for stronger topic depth.</li>
                            </ul>
                        </div>

                        @php
                            $contentHasHtml = $post->content && $post->content !== strip_tags($post->content);
                            $renderedContent = (string) $post->content;
                            if ($contentHasHtml) {
                                $renderedContent = preg_replace_callback('/<h2([^>]*)>(.*?)<\/h2>/is', static function ($matches) {
                                    $headingText = trim(preg_replace('/\s+/', ' ', strip_tags((string) ($matches[2] ?? ''))));
                                    $id = \Illuminate\Support\Str::slug($headingText);

                                    return '<h2' . ($matches[1] ?? '') . ' id="' . e($id) . '">' . ($matches[2] ?? '') . '</h2>';
                                }, $renderedContent) ?? $renderedContent;
                            }
                        @endphp
                        <div class="blog-details__article">
                            @if($contentHasHtml)
                                {!! $renderedContent !!}
                            @else
                                {!! nl2br(e((string) $post->content)) !!}
                            @endif
                        </div>

                        <div class="blog-details__cluster-links">
                            <h3>Next Step Resources</h3>
                            <p style="margin:0 0 14px; color:#4f6386;">Move from research into action with the most relevant service, proof, pricing, and project planning pages for this topic.</p>
                            <div class="blog-details__cluster-links-list">
                                @php
                                    $resourceLinks = !empty($clusterLinks) && is_array($clusterLinks)
                                        ? $clusterLinks
                                        : [
                                            ['label' => 'UK SEO Growth Hub', 'url' => '/uk-growth-hub'],
                                            ['label' => 'Service Solutions', 'url' => '/services'],
                                            ['label' => 'Case Studies', 'url' => '/portfolio'],
                                            ['label' => 'Pricing Plans', 'url' => '/pricing'],
                                            ['label' => 'Book Strategy Call', 'url' => '/contact'],
                                        ];
                                @endphp
                                @foreach($resourceLinks as $resourceLink)
                                    <a href="{{ $resourceLink['url'] }}">{{ $resourceLink['label'] }}</a>
                                @endforeach
                            </div>
                        </div>


                        <section class="blog-details__author-box" aria-labelledby="blogAuthorTitle">
                            <div class="blog-details__author-head">
                                <div>
                                    <span class="blog-details__author-kicker"><i class="fa fa-building"></i> About ARS Developer Ltd</span>
                                    <h3 class="blog-details__author-title" id="blogAuthorTitle">{{ $authorProfile['title'] }}</h3>
                                </div>
                            </div>
                            <div class="blog-details__author-copy">
                                <div class="blog-details__author-avatar">
                                    <img src="{{ $authorProfile['image'] }}" alt="{{ $authorProfile['title'] }} logo">
                                </div>
                                <div>
                                    <p class="blog-details__author-role">{{ $authorProfile['role'] }}</p>
                                    <p class="blog-details__author-summary">{{ $authorProfile['summary'] }}</p>
                                    <p class="blog-details__author-summary">ARS Developer Ltd supports UK businesses with websites, software systems, CRM workflows, SEO implementation, and conversion-focused growth support. Our content is written to help decision-makers compare options clearly and move toward practical next steps with more confidence.</p>
                                    <ul class="blog-details__author-points">
                                        @foreach($authorProfile['points'] as $point)
                                            <li>{{ $point }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </section>

                        <section class="blog-details__comments" id="blog-comments" aria-labelledby="blogCommentsTitle">
                            <div class="blog-details__comments-head">
                                <div>
                                    <span class="blog-details__comments-kicker"><i class="fa fa-comments"></i> Project Comments</span>
                                    <h3 class="blog-details__comments-title" id="blogCommentsTitle">Leave a Comment</h3>
                                    <p class="blog-details__comments-note">Ask a practical question, share a project insight, or add a useful note. Every comment is reviewed before it appears publicly.</p>
                                </div>
                            </div>

                            @if(session('comment_success'))
                                <div class="blog-details__form-alert blog-details__form-alert--success">{{ session('comment_success') }}</div>
                            @endif

                            @if($approvedComments->isNotEmpty())
                                <div class="blog-details__comment-list">
                                    @foreach($approvedComments as $comment)
                                        <article class="blog-details__comment-item">
                                            <div class="blog-details__comment-meta">
                                                <strong>{{ $comment->full_name }}</strong>
                                                <span>{{ $comment->created_at?->format('d M Y') }}</span>
                                                @if($comment->website)
                                                    <a href="{{ $comment->website }}" target="_blank" rel="nofollow noopener">Website</a>
                                                @endif
                                            </div>
                                            <p class="blog-details__comment-body">{{ $comment->comment }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            @endif

                            @if($errors->hasBag('default') && $errors->any())
                                <div class="blog-details__form-alert blog-details__form-alert--error">
                                    Please fix the highlighted fields and submit again.
                                </div>
                            @endif

                            <form method="post" action="{{ route('blog.comments.store', ['slug' => $post->slug]) }}#blog-comments" class="blog-details__comment-form">
                                @csrf
                                <input class="blog-details__honeypot" type="text" name="company_name" tabindex="-1" autocomplete="off">

                                <div>
                                    <label for="blog_comment_comment">Your Comment</label>
                                    <textarea id="blog_comment_comment" name="comment" placeholder="Share your question, feedback, or project insight..." required>{{ old('comment') }}</textarea>
                                    @error('comment')<small style="color:#b42318">{{ $message }}</small>@enderror
                                </div>

                                <div class="blog-details__comment-form-row blog-details__comment-form-row--triple">
                                    <div>
                                        <label for="blog_comment_full_name">Full Name</label>
                                        <input id="blog_comment_full_name" type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Your full name" required>
                                        @error('full_name')<small style="color:#b42318">{{ $message }}</small>@enderror
                                    </div>
                                    <div>
                                        <label for="blog_comment_email">Business Email</label>
                                        <input id="blog_comment_email" type="email" name="email" value="{{ old('email') }}" placeholder="name@company.co.uk" required>
                                        @error('email')<small style="color:#b42318">{{ $message }}</small>@enderror
                                    </div>
                                    <div>
                                        <label for="blog_comment_website">Website (Optional)</label>
                                        <input id="blog_comment_website" type="text" name="website" value="{{ old('website') }}" placeholder="arsdeveloper.co.uk">
                                        @error('website')<small style="color:#b42318">{{ $message }}</small>@enderror
                                    </div>
                                </div>

                                @include('partials.recaptcha')

                                <label class="blog-details__checkbox" for="blog_comment_newsletter_opt_in">
                                    <input id="blog_comment_newsletter_opt_in" type="checkbox" name="newsletter_opt_in" value="1" {{ old('newsletter_opt_in') ? 'checked' : '' }}>
                                    <span>Send me occasional UK software, SEO, and growth updates.</span>
                                </label>

                                <button class="btn" type="submit">Submit for Review</button>
                            </form>
                        </section>


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
                                        $sharePageUrl = route('blog.show', $post->slug);
                                    @endphp
                                    <a target="_blank" rel="noopener" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" aria-label="Share on LinkedIn" title="Share on LinkedIn"><span class="icon-linkedin"></span></a>
                                    <a target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" aria-label="Share on Facebook" title="Share on Facebook"><span class="icon-facebook"></span></a>
                                    <a target="_blank" rel="noopener" href="https://www.instagram.com/arsdeveloperuk/" aria-label="Open Instagram" title="Open Instagram"><span class="fab fa-instagram"></span></a>
                                    <button
                                        type="button"
                                        class="blog-share-copy"
                                        data-share-url="{{ $sharePageUrl }}"
                                        data-share-title="{{ $post->title }}"
                                        aria-label="Share or copy link"
                                        title="Share or copy link"
                                    >
                                        <span class="fa fa-link"></span>
                                    </button>
                                </div>
                                <span class="blog-details__share-feedback" id="blogShareFeedback" aria-live="polite"></span>
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
                        <h3 class="sidebar__title">Browse by Topic</h3>
                        <ul class="sidebar__post-list list-unstyled">
                            @forelse($topicGroups as $group)
                                <li>
                                    <div class="sidebar__topic-group{{ !empty($group['is_current']) ? ' sidebar__topic-group--active' : '' }}">
                                        <div class="sidebar__topic-group-head">
                                            <h4 class="sidebar__topic-group-title">{{ $group['category'] }}</h4>
                                            @if(!empty($group['is_current']))
                                                <span class="sidebar__topic-group-badge">Current Cluster</span>
                                            @endif
                                        </div>
                                        <ul class="sidebar__topic-posts list-unstyled">
                                            @foreach($group['posts'] as $recent)
                                                @php
                                                    $recentImage = $recent->featured_image
                                                        ? (str_starts_with($recent->featured_image, 'http') ? $recent->featured_image : asset(ltrim($recent->featured_image, '/')))
                                                        : asset('assets/images/blog/lp-1-1.jpg');
                                                @endphp
                                                <li>
                                                    <div class="sidebar__post-image">
                                                        <a class="sidebar__post-image-link" href="{{ route('blog.show', $recent->slug) }}" aria-label="{{ $recent->title }}">
                                                            <img src="{{ $recentImage }}" alt="{{ $recent->title }}">
                                                        </a>
                                                    </div>
                                                    <div class="sidebar__post-content">
                                                        <h3>
                                                            <span class="sidebar__post-content-meta"><i class="fa fa-calendar-alt"></i>{{ optional($recent->published_at)->format('d M Y') ?: $recent->created_at->format('d M Y') }}</span>
                                                            <a href="{{ route('blog.show', $recent->slug) }}">{{ \Illuminate\Support\Str::limit($recent->title, 50) }}</a>
                                                        </h3>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @empty
                                <li><div class="sidebar__post-content"><h3>No topic groups</h3></div></li>
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

                    @if(!empty($clusterLinks))
                        <div class="sidebar__single sidebar__guide">
                            <div class="sidebar__guide-head">
                                <span class="sidebar__guide-kicker"><i class="fa fa-compass"></i> Topical Path</span>
                                <h3 class="sidebar__title">Cluster Guide</h3>
                                <p class="sidebar__guide-text">Use these linked pages to move from article research into services, proof, and project planning.</p>
                            </div>
                            <ul class="sidebar__guide-list list-unstyled">
                                @foreach($clusterLinks as $index => $link)
                                    <li>
                                        <a href="{{ url($link['url']) }}">
                                            <span class="sidebar__guide-link-label">
                                                <span class="sidebar__guide-link-index">{{ $index + 1 }}</span>
                                                <span class="sidebar__guide-link-text">{{ $link['label'] }}</span>
                                            </span>
                                            <span class="sidebar__guide-link-icon"><i class="fa fa-arrow-right"></i></span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('click', async function (event) {
    var button = event.target.closest('.blog-share-copy');
    if (!button) {
        return;
    }

    var url = button.getAttribute('data-share-url') || window.location.href;
    var title = button.getAttribute('data-share-title') || document.title;
    var feedback = document.getElementById('blogShareFeedback');

    try {
        if (navigator.share) {
            await navigator.share({ title: title, url: url });
            if (feedback) {
                feedback.textContent = 'Shared';
            }
            return;
        }

        await navigator.clipboard.writeText(url);
        if (feedback) {
            feedback.textContent = 'Link copied';
        }
    } catch (error) {
        if (feedback) {
            feedback.textContent = 'Share unavailable';
        }
    }

    setTimeout(function () {
        if (feedback) {
            feedback.textContent = '';
        }
    }, 2200);
});
</script>

@include('layouts.footer')
