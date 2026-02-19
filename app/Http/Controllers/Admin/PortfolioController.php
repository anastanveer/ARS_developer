<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $portfolios = Portfolio::orderBy('sort_order')->orderByDesc('id')->paginate(20);

        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create(): View
    {
        return view('admin.portfolios.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title']);
        $data['image_path'] = $this->handleImageUpload($request, 'image', $data['image_path'] ?? null);
        $data['image_path_2'] = $this->handleImageUpload($request, 'image_2', $data['image_path_2'] ?? null);
        $data['image_path_3'] = $this->handleImageUpload($request, 'image_3', $data['image_path_3'] ?? null);

        Portfolio::create($data);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio item created.');
    }

    public function edit(Portfolio $portfolio): View
    {
        return view('admin.portfolios.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $portfolio->id);
        $data['image_path'] = $this->handleImageUpload($request, 'image', $data['image_path'] ?? $portfolio->image_path);
        $data['image_path_2'] = $this->handleImageUpload($request, 'image_2', $data['image_path_2'] ?? $portfolio->image_path_2);
        $data['image_path_3'] = $this->handleImageUpload($request, 'image_3', $data['image_path_3'] ?? $portfolio->image_path_3);

        $portfolio->update($data);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio item updated.');
    }

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio item deleted.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:120'],
            'client_name' => ['nullable', 'string', 'max:120'],
            'excerpt' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'image_path_2' => ['nullable', 'string', 'max:255'],
            'image_path_3' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'image_2' => ['nullable', 'image', 'max:4096'],
            'image_3' => ['nullable', 'image', 'max:4096'],
            'project_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        if ($slug === '') {
            $slug = 'portfolio';
        }

        $base = $slug;
        $counter = 1;

        while (Portfolio::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function handleImageUpload(Request $request, string $field, ?string $existingPath = null): ?string
    {
        if (!$request->hasFile($field)) {
            return $existingPath;
        }

        $file = $request->file($field);
        $filename = now()->format('YmdHis') . '-' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $storedPath = $file->storeAs('portfolios', $filename, 'public');

        return 'storage/' . ltrim((string) $storedPath, '/');
    }
}
