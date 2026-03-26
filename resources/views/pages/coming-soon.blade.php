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
                            awesome site, we'll be here soon with a new and improved site.
                        </p>
                    </div>

                </div>
            </div>
        </section>
        <!--End Coming Soon page-->

@include('layouts.footer')
