<footer class="site-footer-two">
            <div class="site-footer-two__shape-bg"
                style="background-image: url({{ asset('assets/images/shapes/site-footer-two-shape-bg.png') }});"></div>
            <div class="site-footer-two__shape-1 zoominout"></div>
            <div class="site-footer-two__shape-2 zoominout"></div>
            <div class="site-footer-two__top">
                <div class="site-footer-two__main-content">
                    <div class="container">
                        <div class="site-footer-two__main-content-inner">
                            <div class="site-footer-two__star rotate-me">
                                <img src="{{ asset('assets/images/shapes/site-footer-two-star.png') }}" alt="">
                            </div>
                            <div class="row">

                                <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                                    <div class="footer-widget-two__about">
                                        <div class="footer-widget-two__about-logo">
                                            <a href="/"><img src="{{ asset('assets/images/resources/ars-logo-nav-white.png') }}"
                                                    alt="ARSDeveloper"></a>
                                        </div>
                                        <ul class="footer-widget-two__get-in-touch-list list-unstyled">
                                            <li>
                                                <div class="icon">
                                                    <span class="icon-pin"></span>
                                                </div>
                                                <div class="text">
                                                    <p>38 Elm Street, ST6 2HN <br> Stoke-on-Trent, United Kingdom</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="icon">
                                                    <span class="icon-envelope"></span>
                                                </div>
                                                <div class="text">
                                                    <p><a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a></p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="icon">
                                                    <span class="icon-phone"></span>
                                                </div>
                                                <div class="text">
                                                    <p><a href="tel:+44747803428">+44 747803428</a></p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="site-footer-two__social-box">
                                            <p class="site-footer-two__social-title">{{ __('ui.follow_us') }}</p>
                                            <div class="site-footer-two__social">
                                                <a href="https://www.facebook.com/arsdeveloperuk" target="_blank" rel="noopener"><i class="icon-facebook"></i></a>
                                                <a href="https://linkedin.com/company/arsdeveloperuk" target="_blank" rel="noopener"><i class="icon-linkedin"></i></a>
                                                <a href="https://www.instagram.com/arsdeveloperuk/" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                                    <div class="footer-widget-two__quick-links">
                                        <h4 class="footer-widget-two__title">{{ __('ui.quick_links') }}</h4>
                                        <ul class="footer-widget-two__quick-links-list list-unstyled">
                                            <li><a href="/">{{ __('ui.home') }}</a></li>
                                            <li><a href="/about">{{ __('ui.about_us') }}</a></li>
                                            <li><a href="/portfolio">{{ __('ui.portfolio') }}</a></li>
                                            <li><a href="/services">{{ __('ui.services') }}</a></li>
                                            <li><a href="/pricing">{{ __('ui.pricing') }}</a></li>
                                            <li><a href="/blog">{{ __('ui.blog') }}</a></li>
                                            <li><a href="/uk-growth-hub">UK SEO Growth Hub</a></li>
                                            <li><a href="{{ route('client.portal.access') }}">Client Portal</a></li>
                                            <li><a href="/contact">{{ __('ui.contact') }}</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="300ms">
                                    <div class="footer-widget-two__support">
                                        <h4 class="footer-widget-two__title">{{ __('ui.legal_support') }}</h4>
                                        <ul
                                            class="footer-widget-two__quick-links-list footer-widget-two__support-list list-unstyled">
                                            <li><a href="/privacy-policy">{{ __('ui.privacy_policy') }}</a></li>
                                            <li><a href="/terms-and-conditions">{{ __('ui.terms_conditions') }}</a></li>
                                            <li><a href="/cookie-policy">{{ __('ui.cookie_policy') }}</a></li>
                                            <li><a href="/refund-policy">{{ __('ui.refund_policy') }}</a></li>
                                            <li><a href="/service-disclaimer">{{ __('ui.service_disclaimer') }}</a></li>
                                            <li><a href="/gallery">{{ __('ui.gallery') }}</a></li>
                                            <li><a href="/faq">{{ __('ui.faqs') }}</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                                    <div class="footer-widget-two__services">
                                        <h4 class="footer-widget-two__title">{{ __('ui.our_services') }}</h4>
                                        <ul
                                            class="footer-widget-two__quick-links-list footer-widget-two__services-list list-unstyled">
                                            <li><a href="/software-development">Software Development</a></li>
                                            <li><a href="/web-design-development">Web App Development</a></li>
                                            <li><a href="/app-development">Mobile App Development</a></li>
                                            <li><a href="/design-and-branding">UX/UI Design</a></li>
                                            <li><a href="/search-engine-optimization">SEO Services</a></li>
                                            <li><a href="/digital-marketing">Digital Marketing</a></li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-footer-two__bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="site-footer-two__bottom-inner">
                                <div class="site-footer-two__copyright">
                                    <p class="site-footer-two__copyright-text">&copy; {{ now()->year }} {{ config('company.legal_name') }}.</p>
                                    <p class="site-footer-two__copyright-text" style="font-size:12px;line-height:1.5;opacity:.92;margin-top:6px;">
                                        Company No: {{ config('company.company_number') }} | Registered in {{ config('company.registered_in') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--Site Footer Two End-->




    </div><!-- /.page-wrapper -->


    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <!-- /.mobile-nav__overlay -->
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>

            <div class="logo-box">
                <a href="/" aria-label="logo image"><img src="{{ asset('assets/images/resources/ars-logo-nav-white.png') }}" width="146"
                        alt="" /></a>
            </div>
            <!-- /.logo-box -->
            <div class="mobile-nav__container"></div>
            <!-- /.mobile-nav__container -->

            <ul class="mobile-nav__contact list-unstyled">
                <li>
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a>
                </li>
                <li>
                    <i class="fas fa-phone"></i>
                    <a href="tel:+44747803428">+44 747803428</a>
                </li>
            </ul><!-- /.mobile-nav__contact -->
            <div class="mobile-nav__top">
                <div class="mobile-nav__social">
                    <a href="https://www.facebook.com/arsdeveloperuk" target="_blank" rel="noopener" class="fab fa-facebook-square"></a>
                    <a href="https://linkedin.com/company/arsdeveloperuk" target="_blank" rel="noopener" class="fab fa-linkedin"></a>
                    <a href="https://www.instagram.com/arsdeveloperuk/" target="_blank" rel="noopener" class="fab fa-instagram"></a>
                </div><!-- /.mobile-nav__social -->
            </div><!-- /.mobile-nav__top -->



        </div>
        <!-- /.mobile-nav__content -->
    </div>
    <!-- /.mobile-nav__wrapper -->



    <!-- Search Popup -->
    <div class="search-popup">
        <div class="color-layer"></div>
        <button class="close-search"><span class="far fa-times fa-fw"></span></button>
        <form id="search-popup-form" method="get" action="{{ route('search') }}" autocomplete="off">
            <div class="form-group">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search Here" required="">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
            <div id="search-popup-results" class="search-popup-results"></div>
        </form>
    </div>
    <!-- End Search Popup -->



    <a href="#" data-target="html" class="scroll-to-target scroll-to-top">
        <span class="scroll-to-top__wrapper"><span class="scroll-to-top__inner"></span></span>
        <span class="scroll-to-top__text"> Go Back Top</span>
    </a>

    <div class="sticky-quick-actions" aria-label="Quick contact actions">
        <a href="{{ route('client.portal.access') }}" class="sticky-quick-actions__item">
            <i class="fas fa-user-shield" aria-hidden="true"></i>
            <span>Client Access</span>
        </a>
        <a href="/#book-meeting" class="sticky-quick-actions__item">
            <i class="fas fa-phone" aria-hidden="true"></i>
            <span>Book Call</span>
        </a>
        <a href="/#free-audit" class="sticky-quick-actions__item">
            <i class="fas fa-clipboard-check" aria-hidden="true"></i>
            <span>Free Audit</span>
        </a>
    </div>


    <style>
        .search-popup-results {
            max-width: 740px;
            margin: 10px auto 0;
            padding: 10px;
            border-radius: 10px;
            background: rgba(16, 42, 77, 0.95);
            display: none;
            max-height: 320px;
            overflow-y: auto;
        }

        .search-popup-results.is-open {
            display: block;
        }

        .search-popup-result {
            display: block;
            padding: 10px 12px;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 8px;
        }

        .search-popup-result:last-child {
            margin-bottom: 0;
        }

        .search-popup-result:hover {
            border-color: #22d7b8;
        }

        .search-popup-result strong {
            display: block;
            color: #fff;
            font-size: 16px;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .search-popup-result span {
            color: #b6c4d8;
            font-size: 13px;
            line-height: 1.4;
        }

        .search-popup-result-empty {
            color: #d6e0ef;
            font-size: 14px;
            padding: 8px 4px;
        }
    </style>

    <script defer src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/jquery.ajaxchimp.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/jquery.appear.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/swiper.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/wow.js') }}"></script>
    <script defer src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/aos.js') }}"></script>
    @php
        $footerPath = '/' . trim(request()->path(), '/');
        if ($footerPath === '//') {
            $footerPath = '/';
        }
        $isHomeFooterPath = in_array($footerPath, ['/', '/index.php'], true);
        $isGalleryFooterPath = in_array($footerPath, ['/gallery', '/gallery.php'], true);
        $isComingSoonFooterPath = in_array($footerPath, ['/coming-soon', '/coming-soon.php'], true);
    @endphp
    @if($isGalleryFooterPath)
        <script defer src="{{ asset('assets/js/isotope.js') }}"></script>
    @endif
    @if($isHomeFooterPath)
        <script defer src="{{ asset('assets/js/marquee.min.js') }}"></script>
    @endif
    @if($isComingSoonFooterPath)
        <script defer src="{{ asset('assets/js/countdown.min.js') }}"></script>
    @endif


    <script defer src="{{ asset('assets/js/gsap/gsap.js') }}"></script>
    <script defer src="{{ asset('assets/js/gsap/ScrollTrigger.js') }}"></script>
    <script defer src="{{ asset('assets/js/gsap/SplitText.js') }}"></script>




    <!-- template js -->
    <script defer src="{{ asset('assets/js/script.js') }}"></script>
    <script>
        (function () {
            var form = document.getElementById('search-popup-form');
            var input = form ? form.querySelector('input[name="q"]') : null;
            var box = document.getElementById('search-popup-results');
            var debounceTimer = null;
            var currentResults = [];

            if (!form || !input || !box) {
                return;
            }

            function closeBox() {
                box.classList.remove('is-open');
                box.innerHTML = '';
            }

            function renderResults(data) {
                currentResults = Array.isArray(data.results) ? data.results : [];

                if (!currentResults.length) {
                    box.innerHTML = '<div class="search-popup-result-empty">No matching page found.</div>';
                    box.classList.add('is-open');
                    return;
                }

                var html = currentResults.map(function (item) {
                    return '<a class="search-popup-result" href="' + item.url + '"><strong>' +
                        item.title + '</strong><span>' + (item.snippet || '') + '</span></a>';
                }).join('');

                box.innerHTML = html;
                box.classList.add('is-open');
            }

            function runSuggest(query) {
                if (!query || query.length < 2) {
                    currentResults = [];
                    closeBox();
                    return Promise.resolve([]);
                }

                return fetch('{{ route('search.suggest') }}?q=' + encodeURIComponent(query), {
                    headers: {
                        'Accept': 'application/json'
                    }
                }).then(function (res) {
                    return res.json();
                }).then(function (data) {
                    renderResults(data);
                    return currentResults;
                }).catch(function () {
                    closeBox();
                    return [];
                });
            }

            input.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    runSuggest(input.value.trim());
                }, 220);
            });

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var query = input.value.trim();

                runSuggest(query).then(function (results) {
                    if (results.length > 0) {
                        window.location.href = results[0].url;
                        return;
                    }
                    if (query.length > 0) {
                        box.innerHTML = '<div class="search-popup-result-empty">No matching page found for "' + query + '".</div>';
                        box.classList.add('is-open');
                    }
                });
            });

            document.addEventListener('click', function (e) {
                if (!form.contains(e.target)) {
                    closeBox();
                }
            });
        })();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.arsRegion || !window.arsRegion.rates) {
                return;
            }

            var active = window.arsRegion.rates[window.arsRegion.key];
            if (!active || typeof active.rate_from_usd !== 'number') {
                return;
            }

            var rate = active.rate_from_usd;
            var symbol = active.symbol || '$';
            var targets = document.querySelectorAll('.pricing-one__price-box, .pricing-two__price-box');

            targets.forEach(function (node) {
                var originalText = node.getAttribute('data-usd-source') || node.textContent;
                node.setAttribute('data-usd-source', originalText);

                var updatedText = originalText.replace(/\$([\d,]+(?:\.\d+)?)/g, function (_, raw) {
                    var usdValue = parseFloat(String(raw).replace(/,/g, ''));
                    if (!isFinite(usdValue)) {
                        return '$' + raw;
                    }

                    var converted = usdValue * rate;
                    var rounded = Math.round(converted);
                    var formatted = rounded.toLocaleString('en-US');
                    return symbol + formatted;
                });

                node.textContent = updatedText;
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function toReadable(text) {
                return String(text || '')
                    .replace(/\.[a-z0-9]+$/i, '')
                    .replace(/[_-]+/g, ' ')
                    .replace(/\s+/g, ' ')
                    .trim();
            }

            function guessContextualLabel(img) {
                var explicit = img.getAttribute('data-seo-alt') || img.getAttribute('data-alt');
                if (explicit && explicit.trim().length > 0) {
                    return explicit.trim();
                }

                var nearestHeading = img.closest('section, article, .container, .row, .col, .blog, .portfolio, .services, .about');
                if (nearestHeading) {
                    var heading = nearestHeading.querySelector('h1, h2, h3, h4');
                    if (heading && heading.textContent.trim().length > 0) {
                        return 'ARSDeveloper UK - ' + heading.textContent.trim() + ' visual';
                    }
                }

                var src = img.getAttribute('src') || '';
                var srcParts = src.split('/');
                var fileName = srcParts.length ? srcParts[srcParts.length - 1] : '';
                var readable = toReadable(fileName);
                if (readable.length > 0) {
                    return 'ARSDeveloper UK - ' + readable;
                }

                return 'ARSDeveloper UK service image';
            }

            var images = document.querySelectorAll('img');
            images.forEach(function (img, index) {
                var isHeroImage = Boolean(
                    img.closest('.main-slider') ||
                    img.closest('.page-header') ||
                    img.closest('.blog-details') ||
                    img.closest('.portfolio-details') ||
                    img.hasAttribute('data-lcp')
                );

                var alt = (img.getAttribute('alt') || '').trim();
                if (alt.length === 0) {
                    alt = guessContextualLabel(img);
                    img.setAttribute('alt', alt);
                }

                var title = (img.getAttribute('title') || '').trim();
                if (title.length === 0) {
                    img.setAttribute('title', alt);
                }

                if (!img.hasAttribute('loading')) {
                    img.setAttribute('loading', isHeroImage ? 'eager' : 'lazy');
                }
                if (!img.hasAttribute('decoding')) {
                    img.setAttribute('decoding', 'async');
                }
                if (isHeroImage && !img.hasAttribute('fetchpriority')) {
                    img.setAttribute('fetchpriority', 'high');
                }
            });

            var frames = document.querySelectorAll('iframe');
            frames.forEach(function (frame) {
                if (!frame.hasAttribute('loading')) {
                    frame.setAttribute('loading', 'lazy');
                }
            });
        });
    </script>
</body>

</html>
