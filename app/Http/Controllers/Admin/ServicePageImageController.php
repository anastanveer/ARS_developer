<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ServicePageImages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicePageImageController extends Controller
{
    public function index(): View
    {
        $images = ServicePageImages::all();

        return view('admin.service-page-images.index', compact('images'));
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (array_keys(ServicePageImages::defaults()) as $slug) {
            $rules["images.$slug.image"] = ['required', 'string', 'max:255'];
            $rules["images.$slug.alt"] = ['nullable', 'string', 'max:255'];
        }

        $data = $request->validate($rules);
        ServicePageImages::save($data['images'] ?? []);

        return redirect()->route('admin.service-page-images.index')->with('success', 'Service images updated.');
    }
}
