@php
    $testimonialSeoHeading = $testimonialSeoHeading ?? 'Client testimonials section';
    $defaultTestimonials = [
        [
            'name' => 'Sarah Olivia',
            'title' => 'Project Manager',
            'company' => 'Healthcare Clinic (UK)',
            'text' => 'I have been thoroughly impressed with the service provided. The team exceeded our expectations in every way, delivering results that truly made a difference.',
            'result' => 'Result: +42% qualified enquiries in 90 days.',
            'rating' => 5,
        ],
        [
            'name' => 'Sarah Albart',
            'title' => 'Operations Lead',
            'company' => 'UK Law Firm',
            'text' => 'The team delivered clear technical improvements and practical guidance. Communication stayed strong throughout and outcomes matched our goals.',
            'result' => 'Result: 2.1x increase in consultation bookings.',
            'rating' => 5,
        ],
        [
            'name' => 'Jessica Brown',
            'title' => 'Founder',
            'company' => 'UK Ecommerce Brand',
            'text' => 'Delivery quality was excellent. We got conversion gains quickly and the support process after launch remained reliable and responsive.',
            'result' => 'Result: checkout completion improved by 31%.',
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
                <span class="section-title-two__tagline">Testimonial</span>
            </div>
            <h2 class="section-title-two__title title-animation">What our satisfied customers <br> are saying
                <span>about us. </span></h2>
        </div>
        <h3 class="seo-hidden-heading">{{ $testimonialSeoHeading }}</h3>
        <div class="testimonial-two__carousel owl-theme owl-carousel">
            @foreach($testimonialItems as $testimonial)
                <div class="item">
                    <div class="testimonial-two__single">
                        <div class="testimonial-two__img-box">
                            <div class="testimonial-two__img">
                                <img src="assets/images/testimonial/testimonial-2-1.png" alt="Client review profile image">
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
