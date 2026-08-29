@php
    $page_title = $sector['name'] ?? 'Sector Services';
@endphp
@include('layouts.header')
<style>
    .sector-hero {
        padding: 110px 0 86px;
    }

    .sector-hero__wrap {
        border: 1px solid #d9e7fb;
        border-radius: 28px;
        background:
            radial-gradient(circle at top right, rgba(15, 127, 233, 0.12), transparent 28%),
            linear-gradient(180deg, #fbfdff 0%, #f3f9ff 100%);
        box-shadow: 0 22px 44px rgba(16, 42, 77, 0.08);
        overflow: hidden;
    }

    .sector-hero__main,
    .sector-hero__aside {
        padding: 34px;
    }

    .sector-hero__main {
        border-right: 1px solid #e0ebfb;
    }

    .sector-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        border-radius: 999px;
        background: rgba(15, 127, 233, 0.1);
        color: #0f63bd;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .sector-hero__title {
        margin: 0 0 14px;
        color: #102a4d;
        font-size: 42px;
        line-height: 1.08;
        max-width: 760px;
    }

    .sector-hero__summary {
        margin: 0 0 22px;
        color: #5a7091;
        font-size: 17px;
        line-height: 1.8;
        max-width: 760px;
    }

    .sector-hero__stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .sector-hero__stat {
        padding: 18px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid #dce8fb;
    }

    .sector-hero__stat strong {
        display: block;
        color: #117be8;
        font-size: 27px;
        line-height: 1;
        margin-bottom: 7px;
    }

    .sector-hero__stat span {
        color: #123561;
        font-weight: 700;
        font-size: 14px;
    }

    .sector-hero__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .sector-hero__card {
        padding: 18px 18px 16px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid #dce8fb;
        min-height: 100%;
    }

    .sector-hero__card span {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #117be8 0%, #35a3ff 100%);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .sector-hero__card p {
        margin: 0;
        color: #4f6588;
        line-height: 1.7;
    }

    .sector-hero__aside {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .sector-hero__cta,
    .sector-hero__faq {
        padding: 22px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid #dce8fb;
    }

    .sector-hero__cta h3,
    .sector-hero__faq h3 {
        margin: 0 0 10px;
        color: #102a4d;
        font-size: 24px;
    }

    .sector-hero__cta p,
    .sector-hero__faq p,
    .sector-hero__faq li {
        color: #5a7091;
        line-height: 1.75;
    }

    .sector-hero__faq ul {
        margin: 0;
        padding-left: 18px;
    }

    .sector-hero__faq li + li {
        margin-top: 8px;
    }

    .sector-hero__cta-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .sector-hero__chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: #eef5ff;
        color: #123561;
        font-weight: 700;
        text-decoration: none;
    }

    @media (max-width: 991px) {
        .sector-hero__main {
            border-right: 0;
            border-bottom: 1px solid #e0ebfb;
        }

        .sector-hero__title {
            font-size: 34px;
        }

        .sector-hero__stats,
        .sector-hero__grid {
            grid-template-columns: 1fr;
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
            <h1>{{ $sector['name'] }} <span>Services</span></h1>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="/">Home</a></li>
                    <li><span></span></li>
                    <li>{{ $sector['name'] }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="sector-hero">
    <div class="container">
        <div class="sector-hero__wrap">
            <div class="row g-0">
                <div class="col-xl-8 col-lg-7">
                    <div class="sector-hero__main">
                        <span class="sector-hero__eyebrow"><i class="fa fa-map-marker-alt"></i> UK Local Coverage</span>
                        <h2 class="sector-hero__title">{{ $sector['headline'] }}</h2>
                        <p class="sector-hero__summary">{{ $sector['summary'] }}</p>
                        <div class="sector-hero__stats">
                            <div class="sector-hero__stat">
                                <strong>SEO</strong>
                                <span>Commercial intent structure</span>
                            </div>
                            <div class="sector-hero__stat">
                                <strong>UX</strong>
                                <span>Conversion-focused page flow</span>
                            </div>
                            <div class="sector-hero__stat">
                                <strong>Build</strong>
                                <span>Software, web, and support</span>
                            </div>
                        </div>
                        <div class="sector-hero__grid">
                            @foreach($sector['highlights'] as $index => $point)
                                <div class="sector-hero__card">
                                    <span>{{ $index + 1 }}</span>
                                    <p>{{ $point }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="sector-hero__aside">
                        <div class="sector-hero__cta">
                            <h3>Need This Setup?</h3>
                            <p>Share your current workflow and growth target. We will recommend the best scope, internal link plan, and delivery model for {{ $sector['name'] }}.</p>
                            <div class="sector-hero__cta-links">
                                <a href="/contact" class="services-details__contact-btn thm-btn"><i class="icon-right"></i>Book Strategy Call</a>
                                <a href="/pricing" class="sector-hero__chip"><i class="fa fa-pound-sign"></i> View Pricing</a>
                                <a href="/portfolio" class="sector-hero__chip"><i class="fa fa-briefcase"></i> Portfolio</a>
                            </div>
                        </div>
                        <div class="sector-hero__faq">
                            <h3>Quick Fit Check</h3>
                            <ul>
                                <li>Phased milestones with clear review points</li>
                                <li>SEO-ready build structure from day one</li>
                                <li>Admin-friendly updates and launch support</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- The page above is a hero, a one-line summary and four bullets — 123-131 words,
     which is not enough for a page that is indexed and sitting in the sitemap. This
     is the substance: what this sector actually struggles with, what the build
     involves, and the questions a buyer here asks before enquiring. --}}
@if(!empty($sector['body']))
<section class="sector-detail">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <h2 class="sector-detail__title">How we approach {{ $sector['name'] }}</h2>
                @foreach($sector['body'] as $para)
                    <p class="sector-detail__para">{{ $para }}</p>
                @endforeach

                @if(!empty($sector['faq']))
                    <h2 class="sector-detail__title sector-detail__title--mt">Common questions</h2>
                    @foreach($sector['faq'] as $item)
                        <div class="sector-detail__faq">
                            <h3>{{ $item['q'] }}</h3>
                            <p>{{ $item['a'] }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

<style>
    .sector-detail { padding: 0 0 90px; }
    .sector-detail__title { font-size: 30px; line-height: 1.25; margin: 0 0 22px; }
    .sector-detail__title--mt { margin-top: 54px; }
    .sector-detail__para { font-size: 16px; line-height: 1.95; margin: 0 0 22px; }
    .sector-detail__faq { margin: 0 0 26px; }
    .sector-detail__faq h3 { font-size: 19px; line-height: 1.4; margin: 0 0 8px; }
    .sector-detail__faq p { font-size: 16px; line-height: 1.9; margin: 0; }
    @media (max-width: 767px) {
        .sector-detail { padding-bottom: 60px; }
        .sector-detail__title { font-size: 25px; }
    }
</style>
@endif

@include('layouts.footer')
