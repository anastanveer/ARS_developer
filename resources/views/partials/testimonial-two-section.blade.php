@php
    $testimonialSeoHeading = $testimonialSeoHeading ?? 'Client testimonials section';
    $defaultTestimonials = [
        [
            'name' => 'Emma Carter',
            'title' => 'Client Review',
            'company' => 'Northstar Retail',
            'text' => 'The ARSDeveloper team handled the ecommerce build and SEO implementation with great clarity. Delivery milestones were on time and support remained strong after launch.',
            'result' => 'Result: Higher conversion rate and faster page speed after launch.',
            'rating' => 5,
        ],
        [
            'name' => 'Daniel Brooks',
            'title' => 'Client Review',
            'company' => 'Sterling Clinics',
            'text' => 'From discovery through implementation, everything was managed in a clear structure. Our team had visibility on milestones, payments, and requirements throughout the project.',
            'result' => 'Result: Improved enquiry workflow and admin visibility.',
            'rating' => 5,
        ],
        [
            'name' => 'Olivia Khan',
            'title' => 'Client Review',
            'company' => 'B2B Service Team',
            'text' => 'We needed a cleaner service structure and better lead handling. ARSDeveloper delivered a practical website flow with stronger calls to action and clearer reporting points.',
            'result' => 'Result: Clearer lead flow and better internal follow-up.',
            'rating' => 5,
        ],
    ];

    $testimonialItems = collect($approvedReviews ?? [])->map(function ($item) {
        return [
            'name' => $item->reviewer_name ?: 'Verified Client',
            'title' => 'Client Review',
            'company' => $item->company_name ?: ($item->project?->title ?: 'UK Project'),
            'text' => (string) ($item->review_text ?: ''),
            'result' => $item->result_summary ? 'Result: ' . $item->result_summary : '',
            'rating' => max(1, min(5, (int) ($item->rating ?: 5))),
        ];
    })->filter(fn ($row) => trim((string) ($row['text'] ?? '')) !== '')->values();

    if ($testimonialItems->isEmpty()) {
        $testimonialItems = collect($defaultTestimonials);
    }
@endphp

<section class="testimonial-two">
    <div class="testimonial-two__shape-bg-1"
        style="background-image: url(assets/images/shapes/testimonial-two-shape-bg-1.png);"></div>
    <div class="testimonial-two__shape-bg-2"
        style="background-image: url(assets/images/shapes/testimonial-two-shape-bg-2.png);"></div>
    <div class="testimonial-two__shape-1">
        <img src="assets/images/shapes/testimonial-two-shape-1.png" alt="">
    </div>
    <div class="testimonial-two__shape-2">
        <img src="assets/images/shapes/testimonial-two-shape-2.png" alt="">
    </div>
    <div class="testimonial-two__quote">
        <span class="icon-left"></span>
    </div>
    <div class="container">
        <div class="section-title-two text-left sec-title-animation animation-style2">
            <div class="section-title-two__tagline-box">
                <div class="section-title-two__tagline-icon-box">
                    <div class="section-title-two__tagline-icon-1"></div>
                    <div class="section-title-two__tagline-icon-2"></div>
                </div>
                <span class="section-title-two__tagline">Client Feedback</span>
            </div>
            <h2 class="section-title-two__title title-animation">What clients say about our websites, <br> software delivery,
                and
                <span>support experience.</span></h2>
        </div>
        <h3 class="seo-hidden-heading">{{ $testimonialSeoHeading }}</h3>
        <div class="testimonial-two__carousel owl-theme owl-carousel">
            @foreach($testimonialItems as $testimonial)
                @php
                    $initials = collect(explode(' ', trim((string) $testimonial['name'])))
                        ->filter()
                        ->take(2)
                        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                        ->implode('');
                @endphp
                <div class="item">
                    <div class="testimonial-two__single">
                        <div class="testimonial-two__icon-box" aria-hidden="true">
                            <div class="testimonial-two__icon-badge">
                                <span>{{ $initials !== '' ? $initials : 'UK' }}</span>
                            </div>
                            <div class="testimonial-two__icon-ring">
                                <i class="fas fa-quote-right"></i>
                            </div>
                        </div>
                        <div class="testimonial-two__content">
                            <div class="testimonial-two__client-info">
                                <h4 class="testimonial-two__client-name"><a href="/about">{{ $testimonial['name'] }}</a></h4>
                                <p class="testimonial-two__client-title">{{ $testimonial['title'] }} • {{ $testimonial['company'] }}</p>
                            </div>
                            <p class="testimonial-two__text">"{{ $testimonial['text'] }}"</p>
                            @if(!empty($testimonial['result']))
                                <p class="testimonial-two__result">{{ $testimonial['result'] }}</p>
                            @endif
                            <form class="testimonial-two__star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i > $testimonial['rating'] ? 'stroke-clr' : '' }}">&#9733;</span>
                                @endfor
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
