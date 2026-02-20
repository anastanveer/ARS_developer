@php
    $page_title = 'Portfolio Details';
    $summaryText = $portfolio->excerpt ?: 'This project was delivered for a UK business with a practical implementation plan, measurable milestones, and commercial outcomes.';
    $description = $portfolio->description ?: 'Project details will be updated soon.';
    $clientWebsite = $portfolio->project_url ?: null;
    $isFiverrCase = strcasecmp((string) ($portfolio->category ?? ''), 'Fiverr') === 0;
    $profileTitle = $isFiverrCase ? 'About the Developer Profile' : 'About This Project Delivery';
    $profileText = $isFiverrCase
        ? 'I have been actively delivering projects on Fiverr since 2017, working with 1000+ clients across website development, ecommerce, CRM, and business automation requirements.'
        : 'This delivery was executed with practical planning, quality control, and measurable performance outcomes aligned to client business goals.';
    $capabilityText = $isFiverrCase
        ? 'Full-stack web developer with hands-on delivery in Laravel, WordPress, Shopify, custom web applications, frontend UX, backend APIs, and ongoing support workflows.'
        : 'Execution included UI/UX planning, technical implementation, QA checks, and launch support with clear communication milestones.';
    $fallbackImage = asset('assets/images/project/portfolio-details-img-1.jpg');
    $imageSet = array_values(array_filter([
        $portfolio->image_path ?? null,
        $portfolio->image_path_2 ?? null,
        $portfolio->image_path_3 ?? null,
    ]));
    $resolveImage = static function (?string $path) use ($fallbackImage): string {
        if (!$path) {
            return $fallbackImage;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, '/')) {
            return url($path);
        }
        return asset($path);
    };
    $heroImage = $resolveImage($imageSet[0] ?? null);
    $galleryImageOne = $resolveImage($imageSet[1] ?? ($imageSet[0] ?? null));
    $galleryImageTwo = $resolveImage($imageSet[2] ?? ($imageSet[1] ?? ($imageSet[0] ?? null)));
@endphp
@include('layouts.header')
<style>
    .portfolio-details__live-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin: 0 0 18px;
    }

    .portfolio-details__live-actions .thm-btn {
        min-height: 46px;
    }

    .portfolio-details__live-actions .thm-btn--ghost {
        background: transparent;
        border: 1px solid rgba(var(--finris-base-rgb), .35);
        color: var(--finris-base);
    }

    .portfolio-details__live-actions .thm-btn--ghost:hover {
        color: var(--finris-white);
        background: var(--finris-base);
    }

    .related-projects-grid {
        margin-top: 18px;
    }

    .related-project-card {
        background: #fff;
        border: 1px solid #d9e5f8;
        border-radius: 16px;
        padding: 18px;
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .related-project-card__meta {
        margin: 0;
        color: var(--finris-base);
        font-weight: 700;
        font-size: 15px;
    }

    .related-project-card__title {
        margin: 0;
        font-size: 24px;
        line-height: 1.25;
        text-transform: uppercase;
    }

    .related-project-card__title a {
        color: var(--finris-black);
    }

    .related-project-card__title a:hover {
        color: var(--finris-base);
    }

    .related-project-card__img {
        display: block;
        border-radius: 12px;
        overflow: hidden;
        margin-top: 6px;
    }

    .related-project-card__img img {
        width: 100%;
        aspect-ratio: 626 / 352;
        object-fit: cover;
    }

    .related-project-card__actions {
        margin-top: auto;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .related-project-card__btn {
        flex: 1 1 160px;
        min-height: 48px;
        border-radius: 12px;
        padding: 12px 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        line-height: 1;
        letter-spacing: 0;
    }

    .related-project-card__btn--ghost {
        background: #ffffff;
        color: var(--finris-base);
        border: 1px solid rgba(var(--finris-base-rgb), .35);
    }

    .related-project-card__btn--ghost:hover {
        color: #fff;
        background: var(--finris-base);
        border-color: var(--finris-base);
    }

    @media (max-width: 575px) {
        .related-project-card__btn {
            flex-basis: 100%;
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
                    <h1>{{ $portfolio->title }}</h1><div class="thm-breadcrumb__inner">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                            <li><span></span></li>
                            <li><a href="/portfolio">Portfolio</a></li>
                            <li><span></span></li>
                            <li>Project Details</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="portfolio-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5">
                        <div class="portfolio-details__left">
                            <div class="portfolio-details__summary">
                                <div class="portfolio-details__summary-shape-1"></div>
                                <div class="portfolio-details__summary-shape-2"></div>
                                <div class="portfolio-details__summary-shape-3"></div>
                                <h3 class="portfolio-details__summary-title">Project Summary</h3>
                                <p class="portfolio-details__summary-text">{{ $summaryText }}</p>
                                <ul class="portfolio-details__summary-list list-unstyled">
                                    <li>
                                        <span>Start Date:</span>
                                        <p>{{ optional($portfolio->created_at)->format('d M Y') ?: '-' }}</p>
                                    </li>
                                    <li>
                                        <span>Last Update:</span>
                                        <p>{{ optional($portfolio->updated_at)->format('d M Y') ?: '-' }}</p>
                                    </li>
                                    <li>
                                        <span>Client:</span>
                                        <p>{{ $portfolio->client_name ?: 'UK Business Client' }}</p>
                                    </li>
                                    <li>
                                        <span>Category:</span>
                                        <p>{{ $portfolio->category ?: 'Digital Project' }}</p>
                                    </li>
                                    <li>
                                        <span>Region:</span>
                                        <p>United Kingdom</p>
                                    </li>
                                    <li>
                                        <span>Project Status:</span>
                                        <p>{{ $portfolio->is_published ? 'Delivered' : 'In Review' }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-7">
                        <div class="portfolio-details__right">
                            <div class="portfolio-details__img">
                                <img src="{{ $heroImage }}" alt="{{ $portfolio->title }}">
                            </div>
                            <div class="portfolio-details__content">
                                <h2 class="portfolio-details__title-1">{{ $portfolio->title }}</h2>
                                @if($clientWebsite)
                                    <div class="portfolio-details__live-actions">
                                        <a href="{{ $clientWebsite }}" target="_blank" rel="noopener" class="thm-btn"><span class="icon-right"></span> Live Preview</a>
                                        <a href="/portfolio" class="thm-btn thm-btn--ghost"><span class="icon-left-arrow-1"></span> Back to Portfolio</a>
                                    </div>
                                @endif
                                <p class="portfolio-details__text-1">{{ $description }}</p>
                                <h2 class="portfolio-details__title-2">{{ $profileTitle }}</h2>
                                <p class="portfolio-details__text-2">{{ $profileText }}</p>
                                <p class="portfolio-details__text-2">{{ $capabilityText }}</p>
                                <p class="portfolio-details__text-2">Our delivery focused on UK market expectations: fast-loading user experience, trust-first messaging, conversion-driven page flow, and practical admin handling for day-to-day operations.</p>
                                <h2 class="portfolio-details__title-2">Project Case Study</h2>
                                <p class="portfolio-details__text-3">{{ $caseNarrative['case_study_intro'] }}</p>
                                <div class="portfolio-case-proof">
                                    <div class="portfolio-case-proof__item">
                                        <h4>Problem</h4>
                                        <p>{{ $caseNarrative['challenge'] }}</p>
                                    </div>
                                    <div class="portfolio-case-proof__item">
                                        <h4>Solution</h4>
                                        <p>{{ $caseNarrative['approach'] }}</p>
                                    </div>
                                    <div class="portfolio-case-proof__item">
                                        <h4>Result</h4>
                                        <p>{{ $caseNarrative['result'] }}</p>
                                    </div>
                                </div>

                                <div class="portfolio-details__img-box">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <div class="portfolio-details__img-box-img">
                                                <img src="{{ $galleryImageOne }}" alt="{{ $portfolio->title }} preview">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <div class="portfolio-details__img-box-img-2">
                                                <img src="{{ $galleryImageTwo }}" alt="{{ $portfolio->title }} screen">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="portfolio-details__title-3">Delivery Highlights</h3>
                                <p class="portfolio-details__text-4">{{ $caseNarrative['highlights_text'] }}</p>

                                <div class="portfolio-details__list-and-img">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6">
                                            <ul class="portfolio-details__list list-unstyled">
                                                @foreach(($caseNarrative['highlights'] ?? []) as $highlight)
                                                    <li><p><span>Key Point:</span> {{ $highlight }}</p></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="portfolio-details__img-box-2">
                                                <div class="portfolio-details__img-box-two-img">
                                                    <img src="{{ $galleryImageOne }}" alt="Project result view">
                                                </div>
                                                <div class="portfolio-details__img-box-two-img">
                                                    <img src="{{ $galleryImageTwo }}" alt="Project workflow view">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="portfolio-details__title-4">Implementation Notes</h3>
                                <p class="portfolio-details__text-5">{{ $caseNarrative['implementation_text'] }}</p>

                                <div class="portfolio-details__points-box">
                                    <ul class="portfolio-details__points-list list-unstyled">
                                        @foreach(($caseNarrative['notes_left'] ?? []) as $item)
                                            <li><div class="icon"><span class="fas fa-check"></span></div><p>{{ $item }}</p></li>
                                        @endforeach
                                    </ul>
                                    <ul class="portfolio-details__points-list list-unstyled">
                                        @foreach(($caseNarrative['notes_right'] ?? []) as $item)
                                            <li><div class="icon"><span class="fas fa-check"></span></div><p>{{ $item }}</p></li>
                                        @endforeach
                                    </ul>
                                </div>

                                @if($clientWebsite)
                                    <h3 class="portfolio-details__title-5">Live Project</h3>
                                    <p class="portfolio-details__text-6">Visit the live project: <a href="{{ $clientWebsite }}" target="_blank" rel="noopener">{{ $clientWebsite }}</a></p>
                                @else
                                    <h3 class="portfolio-details__title-5">Client Feedback</h3>
                                    <p class="portfolio-details__text-6">Client teams highlighted the clarity of delivery, practical communication, and quality of implementation as key strengths of this project.</p>
                                @endif

                                <div class="portfolio-details__prev-and-next">
                                    <div class="portfolio-details__prev">
                                        @if($previousPortfolio)
                                            <a href="{{ route('portfolio.show', ['slug' => $previousPortfolio->slug]) }}"><span class="icon-left-arrow-1"></span>Prev Project</a>
                                        @else
                                            <a href="/portfolio"><span class="icon-left-arrow-1"></span>Back to Portfolio</a>
                                        @endif
                                    </div>
                                    <div class="portfolio-details__next">
                                        @if($nextPortfolio)
                                            <a href="{{ route('portfolio.show', ['slug' => $nextPortfolio->slug]) }}">Next Project<span class="icon-right-arrow-1"></span></a>
                                        @else
                                            <a href="/portfolio">View All Projects<span class="icon-right-arrow-1"></span></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($relatedPortfolios->count())
                    <div style="margin-top:50px;">
                        <h3 style="margin-bottom:20px;">Related Projects</h3>
                        <div class="row related-projects-grid">
                            @foreach($relatedPortfolios as $related)
                                @php
                                    $relatedImage = $resolveImage($related->image_path ?? null);
                                @endphp
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="related-project-card">
                                        <p class="related-project-card__meta">#{{ $related->category ?: 'Project' }}</p>
                                        <h3 class="related-project-card__title">
                                            <a href="{{ route('portfolio.show', ['slug' => $related->slug]) }}">{{ $related->title }}</a>
                                        </h3>
                                        <a class="related-project-card__img" href="{{ route('portfolio.show', ['slug' => $related->slug]) }}">
                                            <img src="{{ $relatedImage }}" alt="{{ $related->title }}">
                                        </a>
                                        <div class="related-project-card__actions">
                                            <a href="{{ route('portfolio.show', ['slug' => $related->slug]) }}" class="thm-btn related-project-card__btn related-project-card__btn--ghost"><span class="icon-right"></span> View Case</a>
                                            @if($related->project_url)
                                                <a href="{{ $related->project_url }}" target="_blank" rel="noopener" class="thm-btn related-project-card__btn"><span class="icon-right"></span> Live Preview</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section class="cta-one cta-two">
            <div class="container">
                <div class="cta-one__inner">
                    <div class="cta-one__img">
                        <img src="{{ asset('assets/images/resources/cta-one-img-1.png') }}" alt="">
                    </div>
                    <div class="cta-one__inner-content">
                        <div class="cta-one__shape-bg" style="background-image: url({{ asset('assets/images/shapes/cta-one-shape-bg.png') }});"></div>
                        <h3 class="cta-one__title">Need a similar project for your UK business?</h3>
                        <div class="cta-one__btn">
                            <a href="/contact">Get Started <span class=" icon-right-arrow-1"></span></a>
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
                        <p class="newsletter-two__text">Get the latest SEO tips and software insights straight to your<br>inbox. Stay informed</p>
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
