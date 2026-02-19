@php
    $page_title = 'Portfolio';

    $normaliseCategory = static function (?string $value): string {
        $raw = trim((string) $value);
        $slug = \Illuminate\Support\Str::slug($raw);

        if (str_contains($slug, 'wordpress')) return 'WordPress';
        if (str_contains($slug, 'shopify') || str_contains($slug, 'woocommerce') || str_contains($slug, 'ecommerce')) return 'Shopify';
        if ($slug === 'wix') return 'Wix';
        if ($slug === 'webflow') return 'Webflow';
        if (str_contains($slug, 'crm') || str_contains($slug, 'portal')) return 'CRM';
        if (str_contains($slug, 'landing')) return 'Landing Pages';
        if (str_contains($slug, 'fiverr')) return 'Fiverr';
        if (str_contains($slug, 'custom') || str_contains($slug, 'software') || str_contains($slug, 'saas')) return 'Custom Coding';

        return $raw !== '' ? $raw : 'Other';
    };

    $portfolioGroups = collect($portfolios ?? [])
        ->groupBy(fn ($item) => $normaliseCategory($item->category ?? null))
        ->map(fn ($items) => $items->values());

    $tabs = ['all' => 'All'];
    foreach ($portfolioGroups as $label => $items) {
        if ($items->isEmpty()) {
            continue;
        }
        $tabs[\Illuminate\Support\Str::slug($label)] = $label;
    }

    $flatItems = collect();
    foreach ($tabs as $tabKey => $tabLabel) {
        if ($tabKey === 'all') {
            continue;
        }

        $group = $portfolioGroups->get($tabLabel, collect());
        foreach ($group as $item) {
            $flatItems->push([
                'tab' => $tabKey,
                'model' => $item,
            ]);
        }
    }

    $flatItems = $flatItems->values();

    $resolveImageUrl = static function (?string $path): string {
        $fallback = asset('assets/images/project/portfolio-page-1-1.jpg');
        if (!$path) {
            return $fallback;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, '/')) {
            return url($path);
        }
        return asset($path);
    };
@endphp
@include('layouts.header')

<style>
    .portfolio-page--wide .container { max-width: 1360px; }
    .portfolio-filter-tabs { display:flex; flex-wrap:wrap; justify-content:center; gap:10px; margin:0 0 28px; }
    .portfolio-filter-tabs__btn {
        border:1px solid #d3def1; background:#fff; color:#173153; border-radius:999px;
        padding:10px 16px; font-weight:700; font-size:14px; line-height:1; transition:all .2s ease;
    }
    .portfolio-filter-tabs__btn.is-active, .portfolio-filter-tabs__btn:hover { border-color:#1182d8; background:#1182d8; color:#fff; }
    .portfolio-page__item.is-hidden { display:none; }
    .portfolio-page__img img { width:100%; aspect-ratio:626/352; object-fit:cover; border-radius:10px; }

    .portfolio-page--wide .portfolio-page__single { margin-left:0; margin-right:0; margin-bottom:72px; }
    .portfolio-page--wide ul li:nth-child(2) .portfolio-page__single,
    .portfolio-page--wide ul li:nth-child(4) .portfolio-page__single,
    .portfolio-page--wide ul li:nth-child(6) .portfolio-page__single { margin-left:0; }
    .portfolio-page--wide .portfolio-page__single-inner { padding-left:76px; }
    .portfolio-page--wide .portfolio-page__case-box { left:-114px; top:214px; min-width:252px; justify-content:space-between; }
    .portfolio-page--wide .portfolio-page__case-count:before { content:''; counter-increment:none; }

    .portfolio-page__summary { margin-top:10px; margin-bottom:14px; color:var(--finris-gray); }
    .portfolio-page__btn-box { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
    .portfolio-page__btn--ghost { background-color:transparent; color:var(--finris-base); border:1px solid rgba(var(--finris-base-rgb), .35); }
    .portfolio-page__btn--ghost:hover { background-color:var(--finris-base); color:var(--finris-white); }

    .portfolio-page__fiverr-logo-card {
        margin-top:16px; margin-bottom:15px; border-radius:14px; background:linear-gradient(135deg, #ffffff 0%, #f4fff8 100%);
        border:1px solid #d8efe2; min-height:238px; display:flex; align-items:center; justify-content:center; padding:24px;
    }
    .portfolio-page__fiverr-logo-inner { text-align:center; }
    .portfolio-page__fiverr-badge {
        width:70px; height:70px; border-radius:50%; background:#1dbf73; color:#fff; display:inline-flex;
        align-items:center; justify-content:center; font-size:26px; font-weight:800; letter-spacing:.5px; margin-bottom:12px;
    }
    .portfolio-page__fiverr-title { font-size:22px; font-weight:700; color:#0f172a; margin-bottom:4px; }
    .portfolio-page__fiverr-sub { color:var(--finris-gray); margin:0; }

    @media (max-width: 767px) {
        .portfolio-filter-tabs { justify-content:flex-start; }
        .portfolio-page--wide .portfolio-page__single-inner { padding-left:0; }
        .portfolio-page--wide .portfolio-page__case-box {
            position:relative; left:0; top:0; transform:rotate(0deg); min-width:0; margin-bottom:16px;
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
            <h1>Our <span>Portfolio</span></h1>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>Our Portfolio</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="portfolio-page portfolio-page--wide">
    <div class="container">
        <div class="section-title text-center sec-title-animation animation-style1">
            <div class="section-title__tagline-box justify-content-center">
                <div class="section-title__tagline-icon-box">
                    <div class="section-title__tagline-icon-1"></div>
                    <div class="section-title__tagline-icon-2"></div>
                </div>
                <span class="section-title__tagline">Our Portfolio</span>
            </div>
            <h2 class="section-title__title title-animation">Explore our case studies and live projects.</h2>
        </div>

        @if($flatItems->isNotEmpty())
            <div class="portfolio-filter-tabs" role="tablist" aria-label="Portfolio categories">
                @foreach($tabs as $tabKey => $tabLabel)
                    <button type="button"
                            class="portfolio-filter-tabs__btn{{ $tabKey === 'all' ? ' is-active' : '' }}"
                            data-portfolio-filter="{{ $tabKey }}"
                            aria-selected="{{ $tabKey === 'all' ? 'true' : 'false' }}">
                        {{ $tabLabel }}
                    </button>
                @endforeach
            </div>

            <ul class="row list-unstyled" id="portfolio-grid">
                @foreach($flatItems as $index => $entry)
                    @php
                        $item = $entry['model'];
                        $tabKey = $entry['tab'];
                        $detailUrl = route('portfolio.show', ['slug' => $item->slug]);
                        $summary = trim((string) ($item->excerpt ?: 'Business-focused delivery with conversion-ready implementation and measurable outcomes.'));
                        $imageUrl = $resolveImageUrl($item->image_path ?? null);
                    @endphp
                    <li id="portfolio-{{ $item->slug }}" class="col-xl-6 col-lg-6 col-md-6 portfolio-page__item js-portfolio-item" data-portfolio-tab="{{ $tabKey }}">
                        <div class="portfolio-page__single">
                            <div class="portfolio-page__single-inner">
                                <div class="portfolio-page__case-box">
                                    <p class="portfolio-page__case-text">Case <span class="portfolio-page__case-count"></span></p>
                                    <div class="portfolio-page__case-border"></div>
                                </div>
                                <div class="portfolio-page__content">
                                    <p class="portfolio-page__sub-title">#{{ $item->category ?: 'Project' }} | {{ preg_replace('/^www\./', '', (string) (parse_url((string) $item->project_url, PHP_URL_HOST) ?: ($item->client_name ?: 'project'))) }}</p>
                                    <h3 class="portfolio-page__title"><a href="{{ $detailUrl }}">{{ $item->title }}</a></h3>
                                    <p class="portfolio-page__summary">{{ \Illuminate\Support\Str::limit($summary, 165) }}</p>

                                    @if(strcasecmp((string) $item->category, 'Fiverr') === 0)
                                        <div class="portfolio-page__fiverr-logo-card">
                                            <div class="portfolio-page__fiverr-logo-inner">
                                                <div class="portfolio-page__fiverr-badge">fi</div>
                                                <div class="portfolio-page__fiverr-title">Fiverr</div>
                                                <p class="portfolio-page__fiverr-sub">Since 2017 • Multiple Project Deliveries</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="portfolio-page__img">
                                            <img src="{{ $imageUrl }}" alt="{{ $item->title }}">
                                        </div>
                                    @endif

                                    <div class="portfolio-page__btn-box">
                                        <a href="{{ $detailUrl }}" class="portfolio-page__btn thm-btn portfolio-page__btn--ghost"><span class="icon-right"></span> View Case Details</a>
                                        @if($item->project_url)
                                            <a href="{{ $item->project_url }}" target="_blank" rel="noopener" class="portfolio-page__btn thm-btn"><span class="icon-right"></span> {{ strcasecmp((string) $item->category, 'Fiverr') === 0 ? 'Open Fiverr Portfolio' : 'Live Preview' }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p style="text-align:center;color:var(--finris-gray);">No published portfolio items found. Add them from admin to display here.</p>
        @endif
    </div>
</section>

<script>
    (function () {
        var filterButtons = document.querySelectorAll('[data-portfolio-filter]');
        var portfolioItems = document.querySelectorAll('.js-portfolio-item');
        if (!filterButtons.length || !portfolioItems.length) return;

        function applyFilter(filterKey) {
            portfolioItems.forEach(function (item) {
                var key = item.getAttribute('data-portfolio-tab') || '';
                var show = filterKey === 'all' || key === filterKey;
                item.classList.toggle('is-hidden', !show);
            });

            var caseCounter = 0;
            portfolioItems.forEach(function (item) {
                if (item.classList.contains('is-hidden')) return;
                caseCounter += 1;
                var node = item.querySelector('.portfolio-page__case-count');
                if (node) node.textContent = caseCounter;
            });
        }

        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var selected = button.getAttribute('data-portfolio-filter') || 'all';
                filterButtons.forEach(function (btn) {
                    var active = btn === button;
                    btn.classList.toggle('is-active', active);
                    btn.setAttribute('aria-selected', active ? 'true' : 'false');
                });
                applyFilter(selected);
            });
        });

        applyFilter('all');
    })();
</script>

@include('layouts.footer')
