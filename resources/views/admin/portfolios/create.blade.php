@extends('admin.layout', ['title' => 'Add Portfolio'])

@section('content')
<div class="top"><h1 style="margin:0">Add Portfolio Item</h1><a href="{{ route('admin.portfolios.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.portfolios.store') }}" enctype="multipart/form-data" class="row">
        @csrf
        <div><label>Title</label><input name="title" required></div>
        <div><label>Slug (optional)</label><input name="slug"></div>
        <div><label>Category</label><input name="category"></div>
        <div><label>Client Name</label><input name="client_name"></div>
        <div><label>Project URL</label><input name="project_url" placeholder="https://..."></div>
        <div><label>Sort Order</label><input type="number" name="sort_order" value="0"></div>
        <div><label>Main Image URL</label><input name="image_path" placeholder="storage/portfolios/your-image.avif"></div>
        <div><label>Main Image Upload</label><input type="file" name="image" accept="image/*"></div>
        <div><label>Gallery Image 1 URL</label><input name="image_path_2" placeholder="storage/portfolios/your-image-2.avif"></div>
        <div><label>Gallery Image 1 Upload</label><input type="file" name="image_2" accept="image/*"></div>
        <div><label>Gallery Image 2 URL</label><input name="image_path_3" placeholder="storage/portfolios/your-image-3.avif"></div>
        <div><label>Gallery Image 2 Upload</label><input type="file" name="image_3" accept="image/*"></div>
        <div class="full"><label>Excerpt</label><textarea id="portfolio_excerpt_create" name="excerpt" rows="3" data-editor="compact"></textarea></div>
        <div class="full"><label>Description</label><textarea id="portfolio_description_create" name="description" rows="7" data-editor="full"></textarea></div>
        <div class="full"><label><input type="checkbox" name="is_published" value="1" checked style="width:auto"> Published</label></div>
        <div class="full preview-box">
            <strong>Live Preview</strong>
            <div id="preview-title" style="margin-top:8px;font-size:18px;font-weight:700">Portfolio Title</div>
            <div id="preview-meta" class="muted">Category | Client</div>
            <div id="preview-excerpt" style="margin-top:8px">Short excerpt will appear here...</div>
        </div>
        <div class="full"><button class="btn" type="submit">Create</button></div>
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
