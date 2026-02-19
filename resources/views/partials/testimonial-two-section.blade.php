@php
    $testimonialSeoHeading = $testimonialSeoHeading ?? 'Client testimonials section';
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
            <div class="item">
                <div class="testimonial-two__single">
                    <div class="testimonial-two__img-box">
                        <div class="testimonial-two__img">
                            <img src="assets/images/testimonial/testimonial-2-1.png" alt="">
                        </div>
                    </div>
                    <div class="testimonial-two__content">
                        <div class="testimonial-two__client-info">
                            <h4 class="testimonial-two__client-name"><a href="/about">Sarah Olivia</a></h4>
                            <p class="testimonial-two__client-title">Project Manager • Healthcare Clinic (UK)</p>
                        </div>
                        <p class="testimonial-two__text">"I have been thoroughly impressed with the service provided. The team exceeded our expectations in every way, delivering results that truly made a difference. I highly recommend them!"</p>
                        <p class="testimonial-two__result">Result: +42% qualified enquiries in 90 days.</p>
                        <form class="testimonial-two__star-rating">
                            <input type="radio" id="20-star" name="rating" value="1">
                            <label for="20-star" class="star">&#9733;</label>
                            <input type="radio" id="19-stars" name="rating" value="2">
                            <label for="19-stars" class="star">&#9733;</label>
                            <input type="radio" id="18-stars" name="rating" value="3">
                            <label for="18-stars" class="star">&#9733;</label>
                            <input type="radio" id="17-stars" name="rating" value="4">
                            <label for="17-stars" class="star stroke-clr">&#9733;</label>
                            <input type="radio" id="16-stars" name="rating" value="5">
                            <label for="16-stars" class="star stroke-clr">&#9733;</label>
                        </form>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="testimonial-two__single">
                    <div class="testimonial-two__img-box">
                        <div class="testimonial-two__img">
                            <img src="assets/images/testimonial/testimonial-2-1.png" alt="">
                        </div>
                    </div>
                    <div class="testimonial-two__content">
                        <div class="testimonial-two__client-info">
                            <h4 class="testimonial-two__client-name"><a href="/about">Sarah Albart</a></h4>
                            <p class="testimonial-two__client-title">Operations Lead • UK Law Firm</p>
                        </div>
                        <p class="testimonial-two__text">"I have been thoroughly impressed with the service provided. The team exceeded our expectations in every way, delivering results that truly made a difference. I highly recommend them!"</p>
                        <p class="testimonial-two__result">Result: 2.1x increase in consultation bookings.</p>
                        <form class="testimonial-two__star-rating">
                            <input type="radio" id="10-star" name="rating" value="1">
                            <label for="10-star" class="star">&#9733;</label>
                            <input type="radio" id="9-stars" name="rating" value="2">
                            <label for="9-stars" class="star">&#9733;</label>
                            <input type="radio" id="8-stars" name="rating" value="3">
                            <label for="8-stars" class="star">&#9733;</label>
                            <input type="radio" id="7-stars" name="rating" value="4">
                            <label for="7-stars" class="star stroke-clr">&#9733;</label>
                            <input type="radio" id="6-stars" name="rating" value="5">
                            <label for="6-stars" class="star stroke-clr">&#9733;</label>
                        </form>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="testimonial-two__single">
                    <div class="testimonial-two__img-box">
                        <div class="testimonial-two__img">
                            <img src="assets/images/testimonial/testimonial-2-1.png" alt="">
                        </div>
                    </div>
                    <div class="testimonial-two__content">
                        <div class="testimonial-two__client-info">
                            <h4 class="testimonial-two__client-name"><a href="/about">Jessica Brown</a></h4>
                            <p class="testimonial-two__client-title">Founder • UK Ecommerce Brand</p>
                        </div>
                        <p class="testimonial-two__text">"I have been thoroughly impressed with the service provided. The team exceeded our expectations in every way, delivering results that truly made a difference. I highly recommend them!"</p>
                        <p class="testimonial-two__result">Result: checkout completion improved by 31%.</p>
                        <form class="testimonial-two__star-rating">
                            <input type="radio" id="15-star" name="rating" value="1">
                            <label for="15-star" class="star">&#9733;</label>
                            <input type="radio" id="14-stars" name="rating" value="2">
                            <label for="14-stars" class="star">&#9733;</label>
                            <input type="radio" id="13-stars" name="rating" value="3">
                            <label for="13-stars" class="star">&#9733;</label>
                            <input type="radio" id="12-stars" name="rating" value="4">
                            <label for="12-stars" class="star stroke-clr">&#9733;</label>
                            <input type="radio" id="11-stars" name="rating" value="5">
                            <label for="11-stars" class="star stroke-clr">&#9733;</label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
