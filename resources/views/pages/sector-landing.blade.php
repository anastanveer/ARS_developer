@php
    $page_title = $sector['name'] ?? 'Sector Services';
@endphp
@include('layouts.header')

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

<section class="services-details" style="padding-top: 120px; padding-bottom: 80px;">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="services-details__left">
                    <div class="services-details__content" style="margin-top: 0;">
                        <h2 class="services-details__title-1">{{ $sector['headline'] }}</h2>
                        <p class="services-details__text-1">{{ $sector['summary'] }}</p>
                        <h3 class="services-details__title-2">What We Deliver</h3>
                        <div class="services-details__points-box">
                            <ul class="services-details__points-list list-unstyled">
                                @foreach(array_slice($sector['highlights'], 0, 2) as $point)
                                    <li>
                                        <div class="icon"><span class="icon-check"></span></div>
                                        <p>{{ $point }}</p>
                                    </li>
                                @endforeach
                            </ul>
                            <ul class="services-details__points-list list-unstyled">
                                @foreach(array_slice($sector['highlights'], 2) as $point)
                                    <li>
                                        <div class="icon"><span class="icon-check"></span></div>
                                        <p>{{ $point }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <h3 class="services-details__title-3">Sector FAQ</h3>
                        <div class="accrodion-grp faq-one-accrodion" data-grp-name="sector-faq-{{ \Illuminate\Support\Str::slug($sector['name']) }}">
                            <div class="accrodion active">
                                <div class="accrodion-title"><h4>Can you deliver this sector in phased milestones?</h4></div>
                                <div class="accrodion-content"><div class="inner"><p>Yes. We split delivery into planning, build, review, and launch phases so your team can approve each stage clearly.</p></div></div>
                            </div>
                            <div class="accrodion">
                                <div class="accrodion-title"><h4>Will this setup be SEO and speed ready?</h4></div>
                                <div class="accrodion-content"><div class="inner"><p>Yes. We implement technical SEO structure, conversion-focused content flow, and performance optimization from the initial build.</p></div></div>
                            </div>
                            <div class="accrodion">
                                <div class="accrodion-title"><h4>Can internal teams manage updates after launch?</h4></div>
                                <div class="accrodion-content"><div class="inner"><p>Yes. We provide admin-friendly content structure and handover support so your team can manage daily updates smoothly.</p></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="services-details__right">
                    <div class="services-details__contact-box">
                        <h3>Need This Setup?</h3>
                        <span></span>
                        <p class="services-details__contact-text">Share your current workflow and growth target. We will recommend the best scope and timeline for {{ $sector['name'] }} delivery.</p>
                        <div class="services-details__contact-btn-box">
                            <a href="/contact" class="services-details__contact-btn thm-btn"><i class="icon-right"></i>Book Strategy Call</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')
