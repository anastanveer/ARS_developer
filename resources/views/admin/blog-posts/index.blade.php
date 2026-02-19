@extends('admin.layout', ['title' => 'Blog Posts'])

@section('content')
<div class="top">
    <h1 style="margin:0">Blog Posts</h1>
    <a class="btn" href="{{ route('admin.blog-posts.create') }}">Add Blog Post</a>
</div>

<div class="card">
    <table>
        <thead><tr><th>#</th><th>Title</th><th>Category</th><th>Published Date</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($posts as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>
                    <strong>{{ $item->title }}</strong>
                    <div class="muted" style="font-size:12px">/{{ $item->slug }}</div>
                </td>
                <td>{{ $item->category ?: '-' }}</td>
                <td>{{ optional($item->published_at)->format('d M Y') ?: '-' }}</td>
                <td>{{ $item->is_published ? 'Published' : 'Draft' }}</td>
                <td>
                    <a class="btn" href="{{ route('admin.blog-posts.edit', $item) }}">Edit</a>
                    <form class="inline" method="post" action="{{ route('admin.blog-posts.destroy', $item) }}" onsubmit="return confirm('Delete this blog post?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn red">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No blog posts found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px">{{ $posts->links() }}</div>
</div>
@endsection
