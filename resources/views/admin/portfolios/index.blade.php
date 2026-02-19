@extends('admin.layout', ['title' => 'Portfolio Items'])

@section('content')
<div class="top">
    <h1 style="margin:0">Portfolio Items</h1>
    <a class="btn" href="{{ route('admin.portfolios.create') }}">Add Portfolio</a>
</div>

<div class="card">
    <style>
        .admin-pagination{
            margin-top:14px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            flex-wrap:wrap;
        }
        .admin-pagination__meta{
            color:var(--muted);
            font-size:13px;
        }
        .admin-pagination__links{
            display:flex;
            align-items:center;
            gap:6px;
            flex-wrap:wrap;
        }
        .admin-page-link{
            min-width:36px;
            height:36px;
            padding:0 10px;
            border-radius:10px;
            border:1px solid #d7e3f8;
            background:#fff;
            color:#21487d;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            font-size:13px;
            font-weight:700;
        }
        .admin-page-link:hover{background:#f2f7ff}
        .admin-page-link.is-active{
            background:var(--brand);
            border-color:var(--brand);
            color:#fff;
        }
        .admin-page-link.is-disabled{
            color:#98abc8;
            background:#f7faff;
            pointer-events:none;
        }
    </style>
    <table>
        <thead><tr><th>#</th><th>Title</th><th>Category</th><th>Published</th><th>Sort</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($portfolios as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->category ?: '-' }}</td>
                <td>{{ $item->is_published ? 'Yes' : 'No' }}</td>
                <td>{{ $item->sort_order }}</td>
                <td>
                    <a class="btn" href="{{ route('admin.portfolios.edit', $item) }}">Edit</a>
                    <form class="inline" method="post" action="{{ route('admin.portfolios.destroy', $item) }}" onsubmit="return confirm('Delete this item?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn red">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No portfolio item found.</td></tr>
        @endforelse
        </tbody>
    </table>
    @if($portfolios->lastPage() > 1)
        <div class="admin-pagination">
            <div class="admin-pagination__meta">
                Showing {{ $portfolios->firstItem() ?: 0 }} to {{ $portfolios->lastItem() ?: 0 }} of {{ $portfolios->total() }} items
            </div>
            <div class="admin-pagination__links">
                @if($portfolios->onFirstPage())
                    <span class="admin-page-link is-disabled">Prev</span>
                @else
                    <a class="admin-page-link" href="{{ $portfolios->previousPageUrl() }}">Prev</a>
                @endif

                @for($page = 1; $page <= $portfolios->lastPage(); $page++)
                    <a class="admin-page-link {{ $portfolios->currentPage() === $page ? 'is-active' : '' }}" href="{{ $portfolios->url($page) }}">{{ $page }}</a>
                @endfor

                @if($portfolios->hasMorePages())
                    <a class="admin-page-link" href="{{ $portfolios->nextPageUrl() }}">Next</a>
                @else
                    <span class="admin-page-link is-disabled">Next</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
