@if ($paginator->hasPages())
    <nav class="admin-pagination" role="navigation" aria-label="Pagination Navigation">
        <div class="admin-pagination__meta">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
        </div>

        <div class="admin-pagination__links">
            @if ($paginator->onFirstPage())
                <span class="admin-pagination__link is-disabled" aria-disabled="true">Previous</span>
            @else
                <a class="admin-pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="admin-pagination__ellipsis">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="admin-pagination__link is-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="admin-pagination__link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="admin-pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
            @else
                <span class="admin-pagination__link is-disabled" aria-disabled="true">Next</span>
            @endif
        </div>
    </nav>
@endif
