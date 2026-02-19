<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $adminUsers = AdminUser::query()->orderByDesc('id')->get();

        return view('admin.admin-users.index', compact('adminUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'role' => ['required', Rule::in(AdminUser::roles())],
            'password' => ['required', 'string', 'min:7', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $exists = AdminUser::query()
            ->where('email', $data['email'])
            ->where('role', $data['role'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'This email already exists for the selected role.');
        }

        AdminUser::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return back()->with('success', 'Admin account created.');
    }

    public function update(Request $request, AdminUser $adminUser): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'role' => ['required', Rule::in(AdminUser::roles())],
            'password' => ['nullable', 'string', 'min:7', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $duplicate = AdminUser::query()
            ->where('id', '!=', $adminUser->id)
            ->where('email', $data['email'])
            ->where('role', $data['role'])
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'Another admin already uses this email with the same role.');
        }

        $adminUser->name = $data['name'];
        $adminUser->email = $data['email'];
        $adminUser->role = $data['role'];
        $adminUser->is_active = (bool) ($data['is_active'] ?? false);

        if (!empty($data['password'])) {
            $adminUser->password = Hash::make($data['password']);
        }

        $adminUser->save();

        return back()->with('success', 'Admin account updated.');
    }
}
