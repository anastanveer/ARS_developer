@extends('admin.layout', ['title' => 'Blog Posts'])

@section('content')
<div class="top">
    <h1 style="margin:0">Blog Posts</h1>
    <a class="btn" href="{{ route('admin.blog-posts.create') }}">Add Blog Post</a>
</div>

<style>
    .blog-status{
        position:relative;
        display:inline-block;
    }
    .blog-status__trigger{
        display:inline-flex;
        align-items:center;
        gap:8px;
        min-width:118px;
        justify-content:center;
        padding:9px 14px;
        border-radius:999px;
        border:1px solid #d7e4fb;
        background:#fff;
        color:#173257;
        font-weight:700;
        cursor:pointer;
        box-shadow:0 8px 18px rgba(22,61,128,.08);
    }
    .blog-status__trigger.is-scheduled{
        background:#fff8e9;
        border-color:#ffe1a1;
        color:#9a6500;
    }
    .blog-status__trigger.is-published{
        background:#ecfff5;
        border-color:#bfe9cf;
        color:#167149;
    }
    .blog-status__trigger.is-draft{
        background:#f4f7fc;
        border-color:#d9e3f3;
        color:#536c92;
    }
    .blog-status__caret{
        font-size:11px;
        opacity:.8;
    }
    .blog-status__panel{
        position:absolute;
        top:calc(100% + 10px);
        right:0;
        width:290px;
        background:#fff;
        border:1px solid #dbe6fa;
        border-radius:16px;
        box-shadow:0 22px 40px rgba(19,49,103,.16);
        padding:14px;
        z-index:50;
        display:none;
    }
    .blog-status.open .blog-status__panel{display:block}
    .blog-status__panel label{
        display:block;
        font-size:12px;
        font-weight:700;
        color:#5f7699;
        margin-bottom:6px;
        text-transform:uppercase;
        letter-spacing:.05em;
    }
    .blog-status__panel .grid{
        display:grid;
        gap:10px;
    }
    .blog-status__meta{
        font-size:12px;
        color:#6f82a1;
        line-height:1.5;
        background:#f7faff;
        border:1px solid #e4edfb;
        border-radius:12px;
        padding:10px 12px;
    }
    .blog-status__actions{
        display:flex;
        gap:8px;
        justify-content:flex-end;
        margin-top:4px;
    }
    .blog-actions{
        display:flex;
        align-items:center;
        gap:8px;
        flex-wrap:wrap;
    }
    .blog-actions form.inline{display:inline-flex}
</style>

<div class="card">
    <table>
        <thead><tr><th>#</th><th>Title</th><th>Category</th><th>Publish Time (UK)</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($posts as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>
                    <strong>{{ $item->title }}</strong>
                    <div class="muted" style="font-size:12px">/{{ $item->slug }}</div>
                </td>
                <td>{{ $item->category ?: '-' }}</td>
                <td>{{ optional($item->published_at)->timezone('Europe/London')->format('d M Y, h:i A') ?: '-' }}</td>
                <td>
                    @php
                        $statusLabel = !$item->is_published ? 'Draft' : ($item->isScheduled() ? 'Scheduled' : 'Published');
                        $statusClass = !$item->is_published ? 'is-draft' : ($item->isScheduled() ? 'is-scheduled' : 'is-published');
                        $statusValue = !$item->is_published ? 'draft' : ($item->isScheduled() ? 'scheduled' : 'published');
                    @endphp
                    <div class="blog-status" data-status-wrap>
                        <button type="button" class="blog-status__trigger {{ $statusClass }}" data-status-toggle aria-expanded="false">
                            <span>{{ $statusLabel }}</span>
                            <span class="blog-status__caret">▼</span>
                        </button>
                        <div class="blog-status__panel" data-status-panel>
                            <form method="post" action="{{ route('admin.blog-posts.quick-status', $item) }}" class="grid">
                                @csrf
                                <div>
                                    <label>Status</label>
                                    <select name="quick_status">
                                        <option value="draft" @selected($statusValue === 'draft')>Draft</option>
                                        <option value="scheduled" @selected($statusValue === 'scheduled')>Scheduled</option>
                                        <option value="published" @selected($statusValue === 'published')>Publish Now</option>
                                    </select>
                                </div>
                                <div>
                                    <label>Publish Time (UK)</label>
                                    <input type="datetime-local" name="published_at" value="{{ optional($item->published_at)->timezone('Europe/London')->format('Y-m-d\TH:i') }}">
                                </div>
                                <div class="blog-status__meta">
                                    Use `Scheduled` to change live date/time, `Publish Now` for immediate live, or `Draft` to hide it from public pages and sitemap.
                                </div>
                                <div class="blog-status__actions">
                                    <button type="button" class="btn gray" data-status-close>Close</button>
                                    <button type="submit" class="btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="blog-actions">
                        <a class="btn" href="{{ route('admin.blog-posts.edit', $item) }}">Edit</a>
                        <form class="inline" method="post" action="{{ route('admin.blog-posts.destroy', $item) }}" onsubmit="return confirm('Delete this blog post?')">
                        @csrf @method('DELETE')
                            <button type="submit" class="btn red">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No blog posts found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px">{{ $posts->onEachSide(1)->links('vendor.pagination.admin') }}</div>
</div>

<script>
    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-status-toggle]');
        var close = event.target.closest('[data-status-close]');
        var wraps = document.querySelectorAll('[data-status-wrap]');

        if (toggle) {
            var wrap = toggle.closest('[data-status-wrap]');
            var isOpen = wrap.classList.contains('open');

            wraps.forEach(function (item) {
                item.classList.remove('open');
                var btn = item.querySelector('[data-status-toggle]');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            });

            if (!isOpen) {
                wrap.classList.add('open');
                toggle.setAttribute('aria-expanded', 'true');
            }

            return;
        }

        if (close) {
            var closeWrap = close.closest('[data-status-wrap]');
            if (closeWrap) {
                closeWrap.classList.remove('open');
                var closeBtn = closeWrap.querySelector('[data-status-toggle]');
                if (closeBtn) closeBtn.setAttribute('aria-expanded', 'false');
            }
            return;
        }

        wraps.forEach(function (item) {
            if (!item.contains(event.target)) {
                item.classList.remove('open');
                var btn = item.querySelector('[data-status-toggle]');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>
@endsection
