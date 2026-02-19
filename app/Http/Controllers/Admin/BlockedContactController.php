<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockedContactController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlockedContact::query()->latest();

        if ($request->filled('q')) {
            $term = trim((string) $request->input('q'));
            $query->where(function ($q) use ($term) {
                $q->where('email', 'like', "%{$term}%")
                    ->orWhere('ip', 'like', "%{$term}%")
                    ->orWhere('reason', 'like', "%{$term}%");
            });
        }

        $blockedContacts = $query->paginate(20)->withQueryString();

        return view('admin.blocked-contacts.index', compact('blockedContacts'));
    }

    public function create(): View
    {
        return view('admin.blocked-contacts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['nullable', 'email', 'max:180', 'required_without:ip'],
            'ip' => ['nullable', 'string', 'max:64', 'required_without:email'],
            'reason' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        BlockedContact::create($data);

        return redirect()->route('admin.blocked-contacts.index')->with('success', 'Blocked contact added.');
    }

    public function edit(BlockedContact $blockedContact): View
    {
        return view('admin.blocked-contacts.edit', compact('blockedContact'));
    }

    public function update(Request $request, BlockedContact $blockedContact): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['nullable', 'email', 'max:180', 'required_without:ip'],
            'ip' => ['nullable', 'string', 'max:64', 'required_without:email'],
            'reason' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $blockedContact->update($data);

        return redirect()->route('admin.blocked-contacts.index')->with('success', 'Blocked contact updated.');
    }

    public function destroy(BlockedContact $blockedContact): RedirectResponse
    {
        $blockedContact->delete();

        return redirect()->route('admin.blocked-contacts.index')->with('success', 'Blocked contact deleted.');
    }
}
