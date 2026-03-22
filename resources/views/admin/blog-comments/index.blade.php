@extends('admin.layout', ['title' => 'Blog Comments'])

@section('content')
<div class="top">
    <h1 style="margin:0">Blog Comments</h1>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn {{ $status === 'pending' ? '' : 'gray' }}" href="{{ route('admin.blog-comments.index', ['status' => 'pending']) }}">Pending</a>
        <a class="btn {{ $status === 'approved' ? '' : 'gray' }}" href="{{ route('admin.blog-comments.index', ['status' => 'approved']) }}">Approved</a>
        <a class="btn {{ $status === 'all' ? '' : 'gray' }}" href="{{ route('admin.blog-comments.index', ['status' => 'all']) }}">All</a>
    </div>
</div>

<div class="card">
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Comment</th>
            <th>Blog</th>
            <th>Contact</th>
            <th>Status</th>
            <th>Added</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($comments as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td style="min-width:320px">
                    <strong>{{ $item->full_name }}</strong>
                    <div class="muted" style="font-size:12px;margin-top:4px">{{ \Illuminate\Support\Str::limit($item->comment, 180) }}</div>
                    @if($item->newsletter_opt_in)
                        <div class="muted" style="font-size:12px;margin-top:6px">Newsletter opt-in: Yes</div>
                    @endif
                </td>
                <td>
                    @if($item->blogPost)
                        <strong>{{ $item->blogPost->title }}</strong>
                        <div class="muted" style="font-size:12px">/{{ $item->blogPost->slug }}</div>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <div>{{ $item->email }}</div>
                    @if($item->website)
                        <div class="muted" style="font-size:12px;word-break:break-word">{{ $item->website }}</div>
                    @endif
                </td>
                <td>{{ $item->is_approved ? 'Approved' : 'Pending' }}</td>
                <td>{{ $item->created_at?->format('d M Y H:i') }}</td>
                <td style="display:flex;gap:8px;flex-wrap:wrap">
                    @if(!$item->is_approved)
                        <form class="inline" method="post" action="{{ route('admin.blog-comments.approve', $item) }}">@csrf<button class="btn" type="submit">Approve</button></form>
                    @else
                        <form class="inline" method="post" action="{{ route('admin.blog-comments.unapprove', $item) }}">@csrf<button class="btn gray" type="submit">Unapprove</button></form>
                    @endif
                    <form class="inline" method="post" action="{{ route('admin.blog-comments.destroy', $item) }}" onsubmit="return confirm('Delete this comment?')">@csrf @method('DELETE')<button class="btn red" type="submit">Delete</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No blog comments found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px">{{ $comments->links() }}</div>
</div>
@endsection
