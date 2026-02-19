@extends('admin.layout', ['title' => 'Edit Portfolio'])

@section('content')
<div class="top"><h1 style="margin:0">Edit Portfolio Item</h1><a href="{{ route('admin.portfolios.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.portfolios.update', $portfolio) }}" enctype="multipart/form-data" class="row">
        @csrf @method('PUT')
        <div><label>Title</label><input name="title" required value="{{ old('title', $portfolio->title) }}"></div>
        <div><label>Slug</label><input name="slug" value="{{ old('slug', $portfolio->slug) }}"></div>
        <div><label>Category</label><input name="category" value="{{ old('category', $portfolio->category) }}"></div>
        <div><label>Client Name</label><input name="client_name" value="{{ old('client_name', $portfolio->client_name) }}"></div>
        <div><label>Project URL</label><input name="project_url" value="{{ old('project_url', $portfolio->project_url) }}"></div>
        <div><label>Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', $portfolio->sort_order) }}"></div>
        <div><label>Main Image URL</label><input name="image_path" placeholder="storage/portfolios/your-image.avif" value="{{ old('image_path', $portfolio->image_path) }}"></div>
        <div><label>Main Image Upload</label><input type="file" name="image" accept="image/*"></div>
        <div><label>Gallery Image 1 URL</label><input name="image_path_2" placeholder="storage/portfolios/your-image-2.avif" value="{{ old('image_path_2', $portfolio->image_path_2) }}"></div>
        <div><label>Gallery Image 1 Upload</label><input type="file" name="image_2" accept="image/*"></div>
        <div><label>Gallery Image 2 URL</label><input name="image_path_3" placeholder="storage/portfolios/your-image-3.avif" value="{{ old('image_path_3', $portfolio->image_path_3) }}"></div>
        <div><label>Gallery Image 2 Upload</label><input type="file" name="image_3" accept="image/*"></div>
        <div class="full"><label>Excerpt</label><textarea id="portfolio_excerpt_edit" name="excerpt" rows="3" data-editor="compact">{{ old('excerpt', $portfolio->excerpt) }}</textarea></div>
        <div class="full"><label>Description</label><textarea id="portfolio_description_edit" name="description" rows="7" data-editor="full">{{ old('description', $portfolio->description) }}</textarea></div>
        <div class="full"><label><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $portfolio->is_published)) style="width:auto"> Published</label></div>
        <div class="full preview-box">
            <strong>Live Preview</strong>
            <div id="preview-title" style="margin-top:8px;font-size:18px;font-weight:700">{{ old('title', $portfolio->title) ?: 'Portfolio Title' }}</div>
            <div id="preview-meta" class="muted">{{ old('category', $portfolio->category) ?: 'Category' }} | {{ old('client_name', $portfolio->client_name) ?: 'Client' }}</div>
            <div id="preview-excerpt" style="margin-top:8px">{{ old('excerpt', $portfolio->excerpt) ?: 'Short excerpt will appear here...' }}</div>
        </div>
        <div class="full"><button class="btn" type="submit">Save Changes</button></div>
    </form>
</div>
<script>
document.addEventListener('input', function () {
    var title = document.querySelector('input[name=\"title\"]')?.value || 'Portfolio Title';
    var category = document.querySelector('input[name=\"category\"]')?.value || 'Category';
    var client = document.querySelector('input[name=\"client_name\"]')?.value || 'Client';
    var excerpt = document.querySelector('textarea[name=\"excerpt\"]')?.value || 'Short excerpt will appear here...';
    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-meta').textContent = category + ' | ' + client;
    document.getElementById('preview-excerpt').textContent = excerpt;
});
</script>
@endsection
