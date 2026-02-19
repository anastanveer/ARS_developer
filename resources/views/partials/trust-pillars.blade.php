<section class="trust-pillars">
    @php
        $items = $trustPillars ?? [
            ['title' => 'UK-Registered Delivery', 'text' => 'Business-first software solutions planned for UK compliance and growth goals.'],
            ['title' => 'Clear Milestones', 'text' => 'Scope, timeline, and delivery stages defined before execution begins.'],
            ['title' => 'Performance & SEO Ready', 'text' => 'Technical setup designed for speed, clean indexing, and lead conversion.'],
            ['title' => 'Post-Launch Support', 'text' => 'Ongoing updates, optimization, and support plans after go-live.'],
        ];
    @endphp
    <div class="container">
        <div class="trust-pillars__inner">
            @foreach($items as $item)
                <div class="trust-pillars__item">
                    <h3>{{ $item['title'] ?? '' }}</h3>
                    <p>{{ $item['text'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
