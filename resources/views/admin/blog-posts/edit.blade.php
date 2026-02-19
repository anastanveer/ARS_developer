@extends('admin.layout', ['title' => 'Edit Blog Post'])

@section('content')
<div class="top"><h1 style="margin:0">Edit Blog Post</h1><a href="{{ route('admin.blog-posts.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.blog-posts.update', $blogPost) }}" enctype="multipart/form-data" class="row">
        @csrf @method('PUT')
        <div><label>Title</label><input name="title" required value="{{ old('title', $blogPost->title) }}"></div>
        <div><label>Slug</label><input name="slug" value="{{ old('slug', $blogPost->slug) }}"></div>
        <div><label>Category</label><input name="category" value="{{ old('category', $blogPost->category) }}"></div>
        <div><label>Author Name</label><input name="author_name" value="{{ old('author_name', $blogPost->author_name) }}"></div>
        <div><label>Publish Date</label><input type="date" name="published_at" value="{{ old('published_at', optional($blogPost->published_at)->toDateString()) }}"></div>
        <div><label>Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', $blogPost->sort_order) }}"></div>

        <div><label>Featured Image URL</label><input name="featured_image" value="{{ old('featured_image', $blogPost->featured_image) }}"></div>
        <div><label>Or Upload New Image</label><input type="file" name="image" accept="image/*"></div>
        <div class="full"><label>Image Alt Text</label><input name="featured_image_alt" value="{{ old('featured_image_alt', $blogPost->featured_image_alt) }}"></div>

        <div class="full"><label>Excerpt</label><textarea id="blog_excerpt_edit" name="excerpt" rows="4" data-editor="compact">{{ old('excerpt', $blogPost->excerpt) }}</textarea></div>
        <div class="full"><label>Content</label><textarea id="blog_content_edit" name="content" rows="14" data-editor="full">{{ old('content', $blogPost->content) }}</textarea></div>

        <div class="full"><h3 style="margin:6px 0 10px">SEO Meta</h3></div>
        <div><label>Meta Title</label><input name="meta_title" value="{{ old('meta_title', $blogPost->meta_title) }}"></div>
        <div><label>Meta Robots</label><input name="meta_robots" value="{{ old('meta_robots', $blogPost->meta_robots) }}"></div>
        <div class="full"><label>Meta Description</label><textarea name="meta_description" rows="3">{{ old('meta_description', $blogPost->meta_description) }}</textarea></div>
        <div class="full"><label>Meta Keywords</label><textarea name="meta_keywords" rows="3">{{ old('meta_keywords', $blogPost->meta_keywords) }}</textarea></div>
        <div class="full"><label>Canonical URL</label><input name="canonical_url" value="{{ old('canonical_url', $blogPost->canonical_url) }}"></div>

        <div class="full"><h3 style="margin:6px 0 10px">Open Graph & Twitter</h3></div>
        <div><label>OG Title</label><input name="og_title" value="{{ old('og_title', $blogPost->og_title) }}"></div>
        <div><label>Twitter Title</label><input name="twitter_title" value="{{ old('twitter_title', $blogPost->twitter_title) }}"></div>
        <div class="full"><label>OG Description</label><textarea name="og_description" rows="3">{{ old('og_description', $blogPost->og_description) }}</textarea></div>
        <div class="full"><label>Twitter Description</label><textarea name="twitter_description" rows="3">{{ old('twitter_description', $blogPost->twitter_description) }}</textarea></div>
        <div><label>OG Image URL</label><input name="og_image" value="{{ old('og_image', $blogPost->og_image) }}"></div>
        <div><label>Twitter Image URL</label><input name="twitter_image" value="{{ old('twitter_image', $blogPost->twitter_image) }}"></div>

        <div class="full"><label><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $blogPost->is_published)) style="width:auto"> Published</label></div>

        <div class="full preview-box">
            <strong>Live SEO Preview</strong>
            <div id="preview-seo-title" style="margin-top:8px;font-size:18px;font-weight:700">{{ old('meta_title', $blogPost->meta_title ?: $blogPost->title) }}</div>
            <div id="preview-seo-url" class="muted">https://arsdeveloper.co.uk/blog/{{ old('slug', $blogPost->slug) }}</div>
            <div id="preview-seo-desc" style="margin-top:8px">{{ old('meta_description', $blogPost->meta_description ?: $blogPost->excerpt) }}</div>
        </div>
        <div class="full"><button class="btn" type="submit">Save Changes</button></div>
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
