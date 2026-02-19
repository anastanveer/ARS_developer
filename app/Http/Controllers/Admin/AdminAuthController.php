<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (session('admin_authenticated')) {
            return $this->redirectByRole((string) session('admin_role'));
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', 'in:super_admin,advanced_admin,blog_seo_admin'],
        ]);

        $account = null;
        if (Schema::hasTable('admin_users')) {
            $account = AdminUser::query()
                ->where('email', $credentials['email'])
                ->where('role', $credentials['role'])
                ->where('is_active', true)
                ->first();
        }

        if ($account) {
            if (!Hash::check($credentials['password'], (string) $account->password)) {
                return back()->withInput($request->only('email', 'role'))->with('error', 'Invalid admin credentials.');
            }

            $request->session()->put('admin_authenticated', true);
            $request->session()->put('admin_email', $account->email);
            $request->session()->put('admin_role', $account->role);
            $request->session()->put('admin_name', $account->name);
            $request->session()->put('admin_user_id', $account->id);

            return $this->redirectByRole($account->role)->with('success', 'Welcome to admin panel.');
        }

        $legacyEmail = (string) env('ADMIN_PANEL_EMAIL', 'arsdeveloper@gmail.com');
        $legacyPassword = (string) env('ADMIN_PANEL_PASSWORD', '1234567');
        $passwordMatches = $credentials['password'] === $legacyPassword || Hash::check($credentials['password'], $legacyPassword);

        if ($credentials['email'] !== $legacyEmail || !$passwordMatches || $credentials['role'] !== AdminUser::ROLE_SUPER) {
            return back()->withInput($request->only('email', 'role'))->with('error', 'Invalid admin credentials.');
        }

        $request->session()->put('admin_authenticated', true);
        $request->session()->put('admin_email', $credentials['email']);
        $request->session()->put('admin_role', AdminUser::ROLE_SUPER);
        $request->session()->put('admin_name', 'Legacy Super Admin');
        $request->session()->forget('admin_user_id');

        return $this->redirectByRole(AdminUser::ROLE_SUPER)->with('success', 'Welcome to admin panel.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['admin_authenticated', 'admin_email', 'admin_role', 'admin_name', 'admin_user_id']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    private function redirectByRole(string $role): RedirectResponse
    {
        if ($role === AdminUser::ROLE_BLOG) {
            return redirect()->route('admin.blog-posts.index');
        }

        if ($role === '') {
            return redirect()->route('admin.login')->with('error', 'Please login with your admin role.');
        }

        return redirect()->route('admin.dashboard');
    }
}
