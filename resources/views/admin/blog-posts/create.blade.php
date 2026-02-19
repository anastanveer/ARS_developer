@extends('admin.layout', ['title' => 'Add Blog Post'])

@section('content')
<div class="top"><h1 style="margin:0">Add Blog Post</h1><a href="{{ route('admin.blog-posts.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.blog-posts.store') }}" enctype="multipart/form-data" class="row">
        @csrf
        <div><label>Title</label><input name="title" required></div>
        <div><label>Slug (optional)</label><input name="slug"></div>
        <div><label>Category</label><input name="category" placeholder="SEO, CRM, Web Development"></div>
        <div><label>Author Name</label><input name="author_name" value="ARS Developer Team"></div>
        <div><label>Publish Date</label><input type="date" name="published_at" value="{{ now()->toDateString() }}"></div>
        <div><label>Sort Order</label><input type="number" name="sort_order" value="0"></div>

        <div><label>Featured Image URL</label><input name="featured_image" placeholder="uploads/blog/x.jpg or full url"></div>
        <div><label>Or Upload Featured Image</label><input type="file" name="image" accept="image/*"></div>
        <div class="full"><label>Image Alt Text</label><input name="featured_image_alt"></div>

        <div class="full"><label>Excerpt</label><textarea id="blog_excerpt_create" name="excerpt" rows="4" data-editor="compact"></textarea></div>
        <div class="full"><label>Content</label><textarea id="blog_content_create" name="content" rows="14" data-editor="full" placeholder="Full blog content (supports plain text or HTML)"></textarea></div>

        <div class="full"><h3 style="margin:6px 0 10px">SEO Meta</h3></div>
        <div><label>Meta Title</label><input name="meta_title"></div>
        <div><label>Meta Robots</label><input name="meta_robots" value="index, follow"></div>
        <div class="full"><label>Meta Description</label><textarea name="meta_description" rows="3"></textarea></div>
        <div class="full"><label>Meta Keywords</label><textarea name="meta_keywords" rows="3" placeholder="uk web development blog, crm automation, technical seo"></textarea></div>
        <div class="full"><label>Canonical URL (optional)</label><input name="canonical_url" placeholder="https://arsdeveloper.co.uk/blog/your-slug"></div>

        <div class="full"><h3 style="margin:6px 0 10px">Open Graph & Twitter</h3></div>
        <div><label>OG Title</label><input name="og_title"></div>
        <div><label>Twitter Title</label><input name="twitter_title"></div>
        <div class="full"><label>OG Description</label><textarea name="og_description" rows="3"></textarea></div>
        <div class="full"><label>Twitter Description</label><textarea name="twitter_description" rows="3"></textarea></div>
        <div><label>OG Image URL</label><input name="og_image"></div>
        <div><label>Twitter Image URL</label><input name="twitter_image"></div>

        <div class="full"><label><input type="checkbox" name="is_published" value="1" checked style="width:auto"> Published</label></div>

        <div class="full preview-box">
            <strong>Live SEO Preview</strong>
            <div id="preview-seo-title" style="margin-top:8px;font-size:18px;font-weight:700">Blog Title</div>
            <div id="preview-seo-url" class="muted">https://arsdeveloper.co.uk/blog/your-slug</div>
            <div id="preview-seo-desc" style="margin-top:8px">Meta description preview...</div>
        </div>
        <div class="full"><button class="btn" type="submit">Create Blog Post</button></div>
    </form>
</div>
<script>
document.addEventListener('input', function () {
    var title = document.querySelector('input[name="meta_title"]')?.value || document.querySelector('input[name="title"]')?.value || 'Blog Title';
    var slug = document.querySelector('input[name="slug"]')?.value || 'your-slug';
    var desc = document.querySelector('textarea[name="meta_description"]')?.value || document.querySelector('textarea[name="excerpt"]')?.value || 'Meta description preview...';
    document.getElementById('preview-seo-title').textContent = title;
    document.getElementById('preview-seo-url').textContent = 'https://arsdeveloper.co.uk/blog/' + slug;
    document.getElementById('preview-seo-desc').textContent = desc;
});
</script>
@endsection
