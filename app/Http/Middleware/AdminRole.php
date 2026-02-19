<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response|RedirectResponse
    {
        $role = (string) $request->session()->get('admin_role', '');

        if ($role === '') {
            $request->session()->forget(['admin_authenticated', 'admin_email', 'admin_role', 'admin_name', 'admin_user_id']);
            return redirect()->route('admin.login')->with('error', 'Your session expired. Please login again.');
        }

        if (!in_array($role, $roles, true)) {
            if ($role === AdminUser::ROLE_BLOG) {
                return redirect()->route('admin.blog-posts.index')->with('error', 'You are not authorized to access this area.');
            }

            return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to access this area.');
        }

        return $next($request);
    }
}
