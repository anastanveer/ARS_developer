@php
    $page_title = 'Coming Soon';
@endphp
@include('layouts.header')

        <!--Start Coming Soon page-->
        <section class="coming-soon-page full-height">
            <div class="coming-soon-page__bg"
                style="background-image: url(assets/images/backgrounds/coming-soon-page-bg.jpg);"></div>
            <div class="coming-soon-page__content">
                <div class="inner">
                    <h1 class="big-title">We're Coming Soon...</h1><div class="timer-box clearfix">
                        <div class="countdown-timer">
                            <div class="default-coundown">
                                <div class="box">
                                    <div class="countdown coming-soon-countdown" data-countdown-time="2026/12/28"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text">
                        <p>
                            Website is under construction. We'll be here soon with new<br>
                            awesome site, subscribe to be notified.
                        </p>
                    </div>
                    <div class="coming-soon-page__subscribe-box">
                        <form class="subscribe-form newsletter-form-validated" action="{{ route('contact.submit') }}" method="post">
                            @csrf
                            <input type="hidden" name="form_type" value="newsletter">
                            <input type="hidden" name="subject" value="Coming Soon Subscription Request">
                            <input type="hidden" name="message" value="Please add me to ARSDeveloper updates from coming soon page.">
                            <input name="email" placeholder="Enter your email address" type="email" required>
                            <button type="submit" class="thm-btn coming-soon-page__btn"><span
                                    class="icon-right"></span> Send Message</button>
                        </form>
                        <div class="result"></div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Coming Soon page-->

@include('layouts.footer')
