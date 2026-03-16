@extends('admin.layout', ['title' => 'Service Images'])

@php use App\Support\ServicePageImages; @endphp

@section('content')
<div class="top">
    <h1 style="margin:0">Service Page Images</h1>
    <span class="pill">Hero images</span>
</div>

<div class="card" style="margin-bottom:16px">
    <p class="muted" style="margin:0">These images appear at the top of each service detail page. Use local paths like <code>assets/images/services/service-web-development.svg</code> or upload your own image and paste the saved path here later.</p>
</div>

<div class="card">
    <form method="post" action="{{ route('admin.service-page-images.update') }}" class="row">
        @csrf
        @foreach($images as $slug => $item)
            <div class="full preview-box" style="display:grid;grid-template-columns:minmax(0,1fr) 280px;gap:16px;align-items:start">
                <div>
                    <h3 style="margin:0 0 12px">{{ $item['label'] }}</h3>
                    <div style="margin-bottom:12px">
                        <label>Image Path or URL</label>
                        <input name="images[{{ $slug }}][image]" value="{{ old("images.$slug.image", $item['image']) }}" placeholder="assets/images/services/service-{{ $slug }}.svg">
                    </div>
                    <div>
                        <label>Alt Text</label>
                        <input name="images[{{ $slug }}][alt]" value="{{ old("images.$slug.alt", $item['alt']) }}" placeholder="{{ $item['label'] }} service image">
                    </div>
                </div>
                <div>
                    <div class="muted" style="margin-bottom:8px;font-size:12px">Preview</div>
                    <img src="{{ ServicePageImages::toUrl(old("images.$slug.image", $item['image'])) }}" alt="{{ old("images.$slug.alt", $item['alt']) }}" style="width:100%;aspect-ratio:2/1;object-fit:cover;border-radius:14px;border:1px solid #d7e3f7;background:#0a1d3d">
                </div>
            </div>
        @endforeach
        <div class="full">
            <button class="btn" type="submit">Save Service Images</button>
        </div>
    </form>
</div>
@endsection
