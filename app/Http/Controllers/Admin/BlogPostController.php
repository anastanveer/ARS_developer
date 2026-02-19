<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin/blog-posts/index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin/blog-posts/create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title']);
        $data['featured_image'] = $this->handleImageUpload($request, $data['featured_image'] ?? null);
        $data['og_image'] = $data['og_image'] ?: $data['featured_image'];
        $data['twitter_image'] = $data['twitter_image'] ?: $data['featured_image'];

        BlogPost::create($data);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('admin/blog-posts/edit', compact('blogPost'));
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $blogPost->id);
        $data['featured_image'] = $this->handleImageUpload($request, $data['featured_image'] ?? $blogPost->featured_image);
        $data['og_image'] = $data['og_image'] ?: $data['featured_image'];
        $data['twitter_image'] = $data['twitter_image'] ?: $data['featured_image'];

        $blogPost->update($data);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $blogPost->delete();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post deleted.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:210'],
            'category' => ['nullable', 'string', 'max:120'],
            'author_name' => ['nullable', 'string', 'max:120'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'featured_image_alt' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'meta_robots' => ['nullable', 'string', 'max:80'],
            'canonical_url' => ['nullable', 'string', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string'],
            'twitter_image' => ['nullable', 'string', 'max:255'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['author_name'] = $data['author_name'] ?: 'ARS Developer Team';
        $data['published_at'] = $data['published_at'] ?: now();
        $data['meta_title'] = $data['meta_title'] ?: $data['title'];
        $data['meta_description'] = $data['meta_description'] ?: Str::limit(strip_tags((string) ($data['excerpt'] ?: $data['content'])), 160, '');

        return $data;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        if ($slug === '') {
            $slug = 'blog-post';
        }

        $base = $slug;
        $counter = 1;

        while (BlogPost::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function handleImageUpload(Request $request, ?string $existingPath = null): ?string
    {
        if (!$request->hasFile('image')) {
            return $existingPath;
        }

        $file = $request->file('image');
        $filename = now()->format('YmdHis') . '-' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $destination = public_path('uploads/blog');

        if (!is_dir($destination)) {
            @mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);

        return 'uploads/blog/' . $filename;
    }
}
