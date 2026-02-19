<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('admin_authenticated', false)) {
            return redirect()->route('admin.login')->with('error', 'Please login to access admin panel.');
        }

        if (!$request->session()->has('admin_role')) {
            $request->session()->put('admin_role', AdminUser::ROLE_SUPER);
        }

        $adminUserId = (int) $request->session()->get('admin_user_id', 0);
        if ($adminUserId > 0 && Schema::hasTable('admin_users')) {
            $adminUser = AdminUser::query()->find($adminUserId);
            if (!$adminUser || !$adminUser->is_active) {
                $request->session()->forget(['admin_authenticated', 'admin_email', 'admin_role', 'admin_name', 'admin_user_id']);
                return redirect()->route('admin.login')->with('error', 'Your admin account is inactive or unavailable.');
            }

            $request->session()->put('admin_role', $adminUser->role);
            $request->session()->put('admin_name', $adminUser->name);
        }

        return $next($request);
    }
}
