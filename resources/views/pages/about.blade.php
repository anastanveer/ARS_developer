@php
    $page_title = 'About';
@endphp
@include('layouts.header')

<style>
    .about-company-profile {
        position: relative;
        padding: 48px 0 80px;
        z-index: 1;
    }

    .about-company-profile__inner {
        background: linear-gradient(135deg, #f7fbff 0%, #ffffff 100%);
        border: 1px solid #d9e6f7;
        border-radius: 22px;
        padding: 36px;
    }

    .about-company-profile__meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-top: 22px;
        margin-bottom: 24px;
    }

    .about-company-profile__meta-item {
        border: 1px solid #d9e6f7;
        border-radius: 14px;
        background: #fff;
        padding: 16px;
    }

    .about-company-profile__meta-item h4 {
        margin: 0 0 8px;
        font-size: 16px;
        line-height: 1.3;
        color: var(--finris-black);
    }

    .about-company-profile__meta-item p {
        margin: 0;
        color: var(--finris-gray);
    }

    .about-company-profile__list {
        margin: 0;
        padding-left: 18px;
    }

    .about-company-profile__list li + li {
        margin-top: 8px;
    }

    .about-company-profile__actions {
        margin-top: 24px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .about-why-compact {
        padding-top: 64px !important;
        padding-bottom: 95px !important;
    }

    .about-why-compact .why-choose-four__right-title {
        font-size: 56px;
        line-height: 1.05;
        margin-bottom: 34px;
        -webkit-text-stroke: 1.5px rgba(var(--finris-base-rgb), 0.12);
    }

    .about-why-compact .why-choose-four__img-box {
        margin-top: 34px;
        padding-bottom: 160px;
    }

    .about-why-compact .why-choose-four__single h3 {
        margin-top: 20px;
        margin-bottom: 8px;
    }

    .about-proof {
        padding: 72px 0 90px;
        position: relative;
        z-index: 2;
    }

    .about-proof__inner {
        border: 1px solid #d6e5f9;
        border-radius: 22px;
        background:
            radial-gradient(circle at 92% 10%, rgba(17,130,216,.10), transparent 28%),
            radial-gradient(circle at 8% 88%, rgba(22,183,163,.10), transparent 30%),
            #ffffff;
        padding: 34px;
    }

    .about-proof__layout {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
        gap: 24px;
    }

    .about-proof__lead {
        border: 1px solid #dce8f8;
        border-radius: 16px;
        padding: 22px;
        background: #fff;
    }

    .about-proof__lead-text {
        margin: 0;
        color: var(--finris-gray);
        line-height: 1.75;
    }

    .about-proof__lead-text + .about-proof__lead-text {
        margin-top: 14px;
    }

    .about-proof__metrics {
        margin-top: 18px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .about-proof__metric {
        border: 1px solid #dbe8f9;
        border-radius: 12px;
        background: #f8fbff;
        padding: 14px;
    }

    .about-proof__metric h3 {
        margin: 0;
        font-size: 24px;
        line-height: 1;
    }

    .about-proof__metric p {
        margin: 6px 0 0;
        font-size: 14px;
        color: var(--finris-gray);
    }

    .about-proof__grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .about-proof__item {
        border: 1px solid #dce8f8;
        border-radius: 14px;
        background: #fff;
        padding: 14px 16px;
        display: flex;
        gap: 14px;
    }

    .about-proof__item-step {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        border-radius: 10px;
        background: #1182d8;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 2px;
    }

    .about-proof__item-body h3 {
        margin: 0 0 6px;
        font-size: 22px;
        line-height: 1.25;
    }

    .about-proof__item-body p {
        margin: 0;
        color: var(--finris-gray);
        line-height: 1.65;
    }

    .about-lifecycle {
        padding: 10px 0 100px;
    }

    .about-lifecycle__inner {
        background: linear-gradient(145deg, #f4f9ff 0%, #edf5ff 100%);
        border: 1px solid #d6e5f9;
        border-top: 10px solid #1182d8;
        border-radius: 16px;
        padding: 34px 34px 30px;
    }

    .about-lifecycle__header {
        max-width: 920px;
        margin: 0 auto 26px;
        text-align: center;
    }

    .about-lifecycle__header h2 {
        margin: 0 0 12px;
        font-size: 45px;
        line-height: 1.25;
        color: #0f2c57;
    }

    .about-lifecycle__header p {
        margin: 0;
        color: #476289;
        line-height: 1.72;
    }

    .about-lifecycle__flow {
        max-width: 980px;
        margin: 0 auto;
        display: grid;
        gap: 18px;
        position: relative;
    }

    .about-lifecycle__step {
        display: grid;
        grid-template-columns: 86px 1fr;
        gap: 16px;
        align-items: flex-start;
    }

    .about-lifecycle__num {
        font-size: 50px;
        line-height: 1;
        font-weight: 700;
        color: #1182d8;
        text-align: center;
        margin-top: 3px;
    }

    .about-lifecycle__card {
        border-left: 3px solid #9ec4ef;
        padding: 6px 0 0 16px;
    }

    .about-lifecycle__card h3 {
        margin: 0 0 8px;
        font-size: 34px;
        line-height: 1.3;
        color: #123566;
    }

    .about-lifecycle__card ul {
        margin: 0;
        padding-left: 18px;
        color: #415f8c;
        line-height: 1.65;
    }

    .about-lifecycle__cta {
        margin-top: 26px;
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .about-lifecycle__cta .thm-btn {
        min-height: 46px;
        border-radius: 999px;
    }

    @media (max-width: 991px) {
        .about-company-profile {
            padding-top: 34px;
        }

        .about-company-profile__meta {
            grid-template-columns: 1fr;
        }

        .about-company-profile__inner {
            padding: 24px;
        }

        .about-why-compact {
            padding-top: 56px !important;
            padding-bottom: 75px !important;
        }

        .about-why-compact .why-choose-four__right-title {
            font-size: 38px;
            margin-bottom: 20px;
        }

        .about-proof__grid {
            grid-template-columns: 1fr;
        }

        .about-proof__inner {
            padding: 22px;
        }

        .about-proof__layout {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .about-proof {
            padding-top: 44px;
        }

        .about-proof__metrics {
            grid-template-columns: 1fr;
        }

        .about-lifecycle {
            padding: 0 0 80px;
        }

        .about-lifecycle__inner {
            padding: 24px 18px 22px;
        }

        .about-lifecycle__header h2 {
            font-size: 32px;
        }

        .about-lifecycle__step {
            grid-template-columns: 58px 1fr;
            gap: 10px;
        }

        .about-lifecycle__num {
            font-size: 36px;
        }

        .about-lifecycle__card h3 {
            font-size: 24px;
        }
    }
</style>



        <!--Page Header Start-->
        <section class="page-header">
            <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);">
            </div>
            <div class="page-header__shape-1">
                <img src="assets/images/shapes/page-header-shape-1.png" alt="">
            </div>
            <div class="container">
                <div class="page-header__inner">
                    <h1>About <span>Us</span></h1>
            <div class="seo-heading-ladder" aria-hidden="true">
                <h2 class="seo-hidden-heading">Core page sections</h2>
                <h3 class="seo-hidden-heading">Section details</h3>
                <h4 class="seo-hidden-heading">Supporting information</h4>
                <h5 class="seo-hidden-heading">Additional notes</h5>
                <h6 class="seo-hidden-heading">Reference points</h6>
            </div>
                    <div class="thm-breadcrumb__inner">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><i class="icon-home"></i><a href="/">Home</a></li>
                            <li><span></span></li>
                            <li>About Us</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--Page Header End-->

        <!--About Three Start-->
        <section class="about-three">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7">
                        <div class="about-three__left" data-aos="fade-right" data-aos-duration="1200"
                            data-aos-delay="250">
                            <div class="about-three__img-box">
                                <div class="about-three__img">
                                    <img src="assets/images/resources/about-three-img-1.jpg" alt="">
                                </div>
                                <div class="about-three__img-2">
                                    <img src="assets/images/resources/about-three-img-2.jpg" alt="">
                                </div>
                                <div class="about-three__experience-box">
                                    <div class="about-three__count count-box">
                                        <h3 class="count-text" data-stop="25" data-speed="2000">00</h3>
                                    </div>
                                    <p class="about-three__count-text">Years of
                                        <br> Experience</p>
                                </div>
                                <div class="about-three__video-link">
                                    <a href="https://www.youtube.com/watch?v=Get7rqXYrbQ" class="video-popup">
                                        <div class="about-three__video-icon">
                                            <span class="icon-play-buttton"></span>
                                            <i class="ripple"></i>
                                        </div>
                                    </a>
                                </div>
                                <div class="about-three__shape-1 rotate-me"></div>
                                <div class="about-three__shape-2"></div>
                                <div class="about-three__shape-3"></div>
                                <div class="about-three__shape-4"></div>
                                <div class="about-three__shape-5 rotate-me">
                                    <img src="assets/images/shapes/about-three-shape-5.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="about-three__right">
                            <div class="section-title-two text-left sec-title-animation animation-style2">
                                <div class="section-title-two__tagline-box">
                                    <div class="section-title-two__tagline-icon-box">
                                        <div class="section-title-two__tagline-icon-1"></div>
                                        <div class="section-title-two__tagline-icon-2"></div>
                                    </div>
                                    <span class="section-title-two__tagline">About Us</span>
                                </div>
                                <h2 class="section-title-two__title title-animation">Trusted UK team for <span>web,
                                        software </span><br>and growth delivery.</h2>
                            </div>
                            <p class="about-three__text">ARSDeveloper builds business websites, CRM systems, WordPress
                                solutions, and performance SEO campaigns for UK companies that need measurable growth.</p>
                            <div class="about-three__client-and-text-box">
                                <div class="about-three__client-box">
                                    <div class="about-three__client-img">
                                        <img src="assets/images/resources/about-three-client-img.jpg" alt="">
                                    </div>
                                    <div class="about-three__client-content">
                                        <h3>ARSDeveloper Team</h3>
                                        <p>Software & Growth Specialists</p>
                                    </div>
                                </div>
                                <p class="about-three__client-text">We combine strategy, design, development, and
                                    marketing to deliver conversion-focused digital outcomes.</p>
                            </div>
                            <ul class="about-three__points-list list-unstyled">
                                <li>
                                    <div class="icon">
                                        <img src="assets/images/icon/about-three-points-icon-1.png" alt="">
                                    </div>
                                    <div class="content">
                                        <h3>Secure Architecture</h3>
                                        <p>We implement modern security standards to protect business data, users, and
                                            application integrity.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <img src="assets/images/icon/about-three-points-icon-2.png" alt="">
                                    </div>
                                    <div class="content">
                                        <h3>Custom Business Workflows</h3>
                                        <p>Every project is tailored to your process, goals, and scale requirements for
                                            long-term performance.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--About Three End-->

        <section class="about-proof">
            <div class="container">
                <div class="about-proof__inner">
                    <div class="section-title-two text-left sec-title-animation animation-style2">
                        <div class="section-title-two__tagline-box">
                            <div class="section-title-two__tagline-icon-box">
                                <div class="section-title-two__tagline-icon-1"></div>
                                <div class="section-title-two__tagline-icon-2"></div>
                            </div>
                            <span class="section-title-two__tagline">Why We Are Better</span>
                        </div>
                        <h2 class="section-title-two__title title-animation">Why clients stay with ARSDeveloper for long-term growth</h2>
                    </div>
                    <div class="about-proof__layout">
                        <div class="about-proof__lead">
                            <p class="about-proof__lead-text">Most agencies deliver a design. We deliver a business-ready system. Our process is built around commercial goals, technical quality, and practical operations your team can run after launch.</p>
                            <p class="about-proof__lead-text">From discovery to deployment, every milestone is validated for clarity, speed, SEO readiness, and conversion intent. This is why UK clients stay with us beyond launch.</p>
                            <div class="about-proof__metrics">
                                <div class="about-proof__metric">
                                    <h3>2017+</h3>
                                    <p>Continuous web delivery experience</p>
                                </div>
                                <div class="about-proof__metric">
                                    <h3>1000+</h3>
                                    <p>Client projects and implementation cycles</p>
                                </div>
                            </div>
                        </div>
                        <div class="about-proof__grid">
                            <article class="about-proof__item">
                                <span class="about-proof__item-step">01</span>
                                <div class="about-proof__item-body">
                                    <h3>Outcome-First Planning</h3>
                                    <p>We define goals, commercial priorities, and scope boundaries before development starts.</p>
                                </div>
                            </article>
                            <article class="about-proof__item">
                                <span class="about-proof__item-step">02</span>
                                <div class="about-proof__item-body">
                                    <h3>Full-Stack Delivery</h3>
                                    <p>Frontend, backend, CMS, ecommerce, and integrations are handled in one accountable flow.</p>
                                </div>
                            </article>
                            <article class="about-proof__item">
                                <span class="about-proof__item-step">03</span>
                                <div class="about-proof__item-body">
                                    <h3>SEO-Ready Foundation</h3>
                                    <p>Information architecture, metadata readiness, and technical quality are planned from day one.</p>
                                </div>
                            </article>
                            <article class="about-proof__item">
                                <span class="about-proof__item-step">04</span>
                                <div class="about-proof__item-body">
                                    <h3>Clear Ownership</h3>
                                    <p>You get direct communication, practical timelines, and transparent progress across milestones.</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!--Why Choose Three Start-->
        <section class="why-choose-four about-why-compact">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="why-choose-four__left" data-aos="slide-right" data-aos-duration="1200"
                            data-aos-delay="300">
                            <div class="section-title-two text-left sec-title-animation animation-style2">
                                <div class="section-title-two__tagline-box">
                                    <div class="section-title-two__tagline-icon-box">
                                        <div class="section-title-two__tagline-icon-1"></div>
                                        <div class="section-title-two__tagline-icon-2"></div>
                                    </div>
                                    <span class="section-title-two__tagline">Why Choose Us</span>
                                </div>
                                <h2 class="section-title-two__title title-animation">Why UK businesses choose
                                    <br><span>ARSDeveloper</span></h2>
                            </div>
                            <p class="why-choose-four__text">We focus on outcomes: faster websites, stronger rankings,
                                better lead quality, and scalable software systems that support business growth.</p>
                            <div class="why-choose-four__img-box">
                                <div class="why-choose-four__img">
                                    <img src="assets/images/resources/why-choose-four-img-1.jpg" alt="">
                                </div>
                                <div class="why-choose-four__img-shape-1 img-bounce">
                                    <img src="assets/images/shapes/why-choose-four-img-shape-1.png" alt="">
                                </div>
                                <div class="why-choose-four__img-2">
                                    <img src="assets/images/resources/why-choose-four-img-2.jpg" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="why-choose-four__right">
                            <h2 class="why-choose-four__right-title">PROVEN DELIVERY</h2>
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="why-choose-four__single" data-aos="fade-up" data-aos-duration="1200"
                                        data-aos-delay="100">
                                        <div class="why-choose-four__icon">
                                            <img src="assets/images/icon/why-choose-four-single-icon-1-1.png" alt="">
                                        </div>
                                        <h3>SEO + Speed Foundation</h3>
                                        <p>We build pages that load faster, communicate clearly, and support better Google visibility.</p>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="why-choose-four__single" data-aos="fade-up" data-aos-duration="1200"
                                        data-aos-delay="200">
                                        <div class="why-choose-four__icon">
                                            <img src="assets/images/icon/why-choose-four-single-icon-1-2.png" alt="">
                                        </div>
                                        <h3>Business Workflow Integration</h3>
                                        <p>CRM, forms, automations, and admin actions are mapped to your real operational process.</p>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="why-choose-four__single" data-aos="fade-up" data-aos-duration="1200"
                                        data-aos-delay="300">
                                        <div class="why-choose-four__icon">
                                            <img src="assets/images/icon/why-choose-four-single-icon-1-3.png" alt="">
                                        </div>
                                        <h3>Conversion-Led UX</h3>
                                        <p>Each section is structured to reduce friction and move users toward enquiry or purchase actions.</p>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="why-choose-four__single" data-aos="fade-up" data-aos-duration="1200"
                                        data-aos-delay="400">
                                        <div class="why-choose-four__icon">
                                            <img src="assets/images/icon/why-choose-four-single-icon-1-4.png" alt="">
                                        </div>
                                        <h3>Direct Technical Support</h3>
                                        <p>After launch, you get practical support for updates, fixes, and priority improvements.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Why Choose Three End-->



        <section class="about-lifecycle">
            <div class="container">
                <div class="about-lifecycle__inner">
                    <div class="about-lifecycle__header">
                        <h2>Engineering lifecycle for web, CRM and software delivery in the UK</h2>
                        <p>Our process is built for UK businesses that need one reliable partner for strategy, design, development, SEO foundations, and long-term technical support.</p>
                    </div>
                    <div class="about-lifecycle__flow">
                        <article class="about-lifecycle__step">
                            <div class="about-lifecycle__num">01</div>
                            <div class="about-lifecycle__card">
                                <h3>Discovery and scoping</h3>
                                <ul>
                                    <li>Audit your current website, CRM flow, lead process, and technical blockers.</li>
                                    <li>Define scope for business website, ecommerce, custom portal, or CRM module.</li>
                                    <li>Set practical milestones, budget control points, and delivery priorities.</li>
                                </ul>
                            </div>
                        </article>
                        <article class="about-lifecycle__step">
                            <div class="about-lifecycle__num">02</div>
                            <div class="about-lifecycle__card">
                                <h3>Design and planning</h3>
                                <ul>
                                    <li>Build UX structure and page hierarchy for clarity, trust, and conversion.</li>
                                    <li>Map UI components for web pages, dashboard screens, and client portal areas.</li>
                                    <li>Plan SEO-ready content structure and technical implementation sequence.</li>
                                </ul>
                            </div>
                        </article>
                        <article class="about-lifecycle__step">
                            <div class="about-lifecycle__num">03</div>
                            <div class="about-lifecycle__card">
                                <h3>Build and validation</h3>
                                <ul>
                                    <li>Develop frontend, backend, and required integrations in controlled sprints.</li>
                                    <li>Run QA for performance, responsiveness, browser behavior, and security basics.</li>
                                    <li>Validate workflows for enquiries, bookings, payments, and admin actions.</li>
                                </ul>
                            </div>
                        </article>
                        <article class="about-lifecycle__step">
                            <div class="about-lifecycle__num">04</div>
                            <div class="about-lifecycle__card">
                                <h3>Deployment and integration</h3>
                                <ul>
                                    <li>Deploy with stable release controls, backups, and rollback readiness.</li>
                                    <li>Connect analytics, form tracking, CRM sync, and operational notifications.</li>
                                    <li>Configure admin controls so your team can manage updates confidently.</li>
                                </ul>
                            </div>
                        </article>
                        <article class="about-lifecycle__step">
                            <div class="about-lifecycle__num">05</div>
                            <div class="about-lifecycle__card">
                                <h3>Continuous improvement</h3>
                                <ul>
                                    <li>Monitor rankings, Core Web Vitals, conversion data, and user behavior.</li>
                                    <li>Improve pages, forms, UX blocks, and campaign landing performance.</li>
                                    <li>Scale features, content, and automation without breaking system stability.</li>
                                </ul>
                            </div>
                        </article>
                    </div>
                    <div class="about-lifecycle__cta">
                        <a href="/contact" class="thm-btn"><span class="icon-right"></span> Book your free consultation</a>
                        <a href="/pricing" class="thm-btn thm-btn-two"><span class="icon-right"></span> Get a quote in minutes</a>
                    </div>
                </div>
            </div>
        </section>



        <section class="about-company-profile">
            <div class="container">
                <div class="about-company-profile__inner">
                    <div class="section-title-two text-left sec-title-animation animation-style2">
                        <div class="section-title-two__tagline-box">
                            <div class="section-title-two__tagline-icon-box">
                                <div class="section-title-two__tagline-icon-1"></div>
                                <div class="section-title-two__tagline-icon-2"></div>
                            </div>
                            <span class="section-title-two__tagline">Company Profile</span>
                        </div>
                        <h2 class="section-title-two__title title-animation">Who runs ARS Developer Ltd and how we deliver projects</h2>
                    </div>
                    <p>ARS Developer Ltd is a UK-registered software and web development company focused on business websites, ecommerce delivery, CRM systems, and growth support for companies across the UK market.</p>
                    <div class="about-company-profile__meta">
                        <div class="about-company-profile__meta-item">
                            <h4>Legal Identity</h4>
                            <p>ARS Developer Ltd, Company No: 17039150, registered in England & Wales.</p>
                        </div>
                        <div class="about-company-profile__meta-item">
                            <h4>Leadership Model</h4>
                            <p>Founder-led execution with direct oversight on planning, quality control, and delivery milestones.</p>
                        </div>
                        <div class="about-company-profile__meta-item">
                            <h4>Delivery Ownership</h4>
                            <p>Each project has a clear owner for scope, timelines, communication, and post-launch support.</p>
                        </div>
                    </div>
                    <ul class="about-company-profile__list">
                        <li>Transparent scope and timeline before build starts.</li>
                        <li>Structured milestone updates and practical weekly progress communication.</li>
                        <li>Technical quality checks for speed, SEO-readiness, and security basics.</li>
                        <li>Handover documentation and ongoing support options after launch.</li>
                    </ul>
                    <div class="about-company-profile__actions">
                        <a href="/portfolio" class="thm-btn thm-btn-two"><span class="icon-right"></span> View Our Work</a>
                        <a href="/contact" class="thm-btn"><span class="icon-right"></span> Talk to Our Team</a>
                    </div>
                </div>
            </div>
        </section>


        <!--Counter One Start -->
        <section class="counter-one">
            <div class="counter-one__shape-1 float-bob-x"
                style="background-image: url(assets/images/shapes/counter-one-shape-1.png);"></div>
            <div class="container">
                <ul class="list-unstyled counter-one__list">
                    <li data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
                        <div class="counter-one__single">
                            <div class="counter-one__count count-box">
                                <p class="count-text" data-stop="240" data-speed="1500">00</p>
                                <span class="counter-one__count-plus">+</span>
                            </div>
                            <p class="counter-one__text">Projects Completed</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
                        <div class="counter-one__single">
                            <div class="counter-one__count count-box">
                                <p class="count-text" data-stop="40" data-speed="1500">00</p>
                                <span class="counter-one__count-plus">+</span>
                            </div>
                            <p class="counter-one__text">Awards Achieve</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200">
                        <div class="counter-one__single">
                            <div class="counter-one__count count-box">
                                <p class="count-text" data-stop="780" data-speed="1500">00</p>
                                <span class="counter-one__count-plus">+</span>
                            </div>
                            <p class="counter-one__text">Positive Review</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-duration="1200" data-aos-delay="250">
                        <div class="counter-one__single">
                            <div class="counter-one__count count-box">
                                <p class="count-text" data-stop="130" data-speed="1500">00</p>
                                <span class="counter-one__count-plus">+</span>
                            </div>
                            <p class="counter-one__text">Satisfied Client</p>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        <!--Counter One End -->
        @include('partials.testimonial-two-section', ['testimonialSeoHeading' => 'About page client testimonials'])


        <!--Awards One Start -->
        <section class="awards-one">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7">
                        <div class="awards-one__left">
                            <div class="section-title-two text-left sec-title-animation animation-style2">
                                <div class="section-title-two__tagline-box">
                                    <div class="section-title-two__tagline-icon-box">
                                        <div class="section-title-two__tagline-icon-1"></div>
                                        <div class="section-title-two__tagline-icon-2"></div>
                                    </div>
                                    <span class="section-title-two__tagline">Our Journey</span>
                                </div>
                                <h2 class="section-title-two__title title-animation">Building a trusted UK startup
                                    <br>with transparent
                                    <span>delivery milestones.</span></h2>
                            </div>
                            <div class="awards-one__img-box">
                                <div class="awards-one__img">
                                    <img src="assets/images/resources/awards-one-img-1.jpg" alt="Startup team planning project delivery">
                                </div>
                                <div class="awards-one__img-2">
                                    <img src="assets/images/resources/awards-one-img-2.jpg" alt="Roadmap and launch planning session">
                                    <div class="awards-one__experience-box">
                                        <div class="awards-one__count count-box">
                                            <h3 class="count-text" data-stop="12" data-speed="2000">00</h3>
                                            <span>+</span>
                                        </div>
                                        <p class="awards-one__count-text">Live Projects Delivered</p>
                                    </div>
                                    <div class="awards-one__shape-1"></div>
                                    <div class="awards-one__shape-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="awards-one__right">
                            <ul class="awards-one__awards-list list-unstyled">
                                <li>
                                    <div class="icon">
                                        <span class="icon-trophy-1"></span>
                                    </div>
                                    <div class="content">
                                        <h3>Discovery and Planning Framework</h3>
                                        <p>Every project starts with business goals, scope mapping, and timeline
                                            planning so delivery stays clear from day one.</p>
                                        <div class="awards-one__tag-and-date">
                                            <div class="awards-one__tag">
                                                <p><span class="icon-sparkle"></span>Process</p>
                                            </div>
                                            <div class="awards-one__date">
                                                <p><span class="fas fa-calendar-alt"></span>Week 1 Focus</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <span class="icon-trophy-1"></span>
                                    </div>
                                    <div class="content">
                                        <h3>MVP Build and Launch Readiness</h3>
                                        <p>We deliver practical MVPs with clean UX, SEO-ready structure, and test
                                            coverage before production rollout.</p>
                                        <div class="awards-one__tag-and-date">
                                            <div class="awards-one__tag">
                                                <p><span class="icon-sparkle"></span>Delivery</p>
                                            </div>
                                            <div class="awards-one__date">
                                                <p><span class="fas fa-calendar-alt"></span>Week 2-6 Window</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <span class="icon-trophy-1"></span>
                                    </div>
                                    <div class="content">
                                        <h3>Support and Continuous Optimization</h3>
                                        <p>After launch, we handle updates, performance tracking, and priority fixes so
                                            your business can scale without delivery gaps.</p>
                                        <div class="awards-one__tag-and-date">
                                            <div class="awards-one__tag">
                                                <p><span class="icon-sparkle"></span>Growth</p>
                                            </div>
                                            <div class="awards-one__date">
                                                <p><span class="fas fa-calendar-alt"></span>Ongoing</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Awards One End -->

        <!--CTA One Start -->
        <section class="cta-one cta-two">
            <div class="container">
                <div class="cta-one__inner">
                    <div class="cta-one__img">
                        <img src="assets/images/resources/cta-one-img-1.png" alt="">
                    </div>
                    <div class="cta-one__inner-content">
                        <div class="cta-one__shape-bg"
                            style="background-image: url(assets/images/shapes/cta-one-shape-bg.png);"></div>
                        <h3 class="cta-one__title">Start your journey with our <br> exceptional services.</h3>
                        <div class="cta-one__btn">
                            <a href="/contact">Get Started <span class=" icon-right-arrow-1"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--CTA One End -->

        <!--Newsletter Two Start -->
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
        <!--Newsletter Two End -->

@include('layouts.footer')
