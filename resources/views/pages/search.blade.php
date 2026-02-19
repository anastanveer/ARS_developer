@php
    $page_title = 'Search Results';
@endphp
@include('layouts.header')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);"></div>
    <div class="page-header__shape-1">
        <img src="assets/images/shapes/page-header-shape-1.png" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>Search <span>Results</span></h1>
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
                    <li>Search</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="blog-list" style="padding-top: 120px;">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="sidebar__single sidebar__search" style="margin-bottom: 40px;">
                    <div class="sidebar__title-box">
                        <div class="sidebar__title-icon">
                            <img src="assets/images/icon/sidebar-title-icon.png" alt="">
                        </div>
                        <h2 class="sidebar__title">Find Content</h2>
                    </div>
                    <p class="sidebar__search-text">Search services, pages, portfolio items and blog topics.</p>
                    <form method="get" action="{{ route('search') }}" class="sidebar__search-form">
                        <input type="search" name="q" value="{{ $query }}" placeholder="Search ARSDeveloper" required>
                        <button type="submit"><i class="icon-search-1"></i></button>
                    </form>
                </div>
            </div>
        </div>

        @if($query === '')
            <div class="row">
                <div class="col-xl-12">
                    <div class="blog-list__single" style="padding: 28px 30px;">
                        <h3 class="blog-list__title">Start searching</h3>
                        <p class="blog-list__text" style="margin-bottom: 14px;">Try these popular searches:</p>
                        <div>
                            @foreach($popularKeywords as $keyword)
                                <a href="{{ route('search', ['q' => $keyword]) }}" class="thm-btn" style="margin: 0 10px 10px 0;">{{ $keyword }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-xl-12">
                    <p style="color: #c7d0df; margin-bottom: 20px;">
                        {{ $results->count() }} result(s) found for <strong style="color: #fff;">"{{ $query }}"</strong>
                    </p>
                </div>
            </div>

            @forelse($results as $result)
                <div class="row">
                    <div class="col-xl-12">
                        <div class="blog-list__single" style="padding: 28px 30px; margin-bottom: 20px;">
                            <h3 class="blog-list__title" style="margin-bottom: 10px;">
                                <a href="{{ $result['url'] }}">{{ $result['title'] }}</a>
                            </h3>
                            <p style="font-size: 14px; margin-bottom: 10px;">
                                <a href="{{ $result['url'] }}" style="color: #22d7b8;">{{ url($result['url']) }}</a>
                            </p>
                            <p class="blog-list__text">{!! $result['snippet'] !!}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="row">
                    <div class="col-xl-12">
                        <div class="blog-list__single" style="padding: 28px 30px;">
                            <h3 class="blog-list__title">No results found</h3>
                            <p class="blog-list__text">Try different words like web development, CRM, WordPress, SEO, or software agency UK.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        @endif
    </div>
</section>

@include('layouts.footer')
