@extends('admin.layout', ['title' => 'Admin Users'])

@section('content')
<div class="top">
    <h1 style="margin:0">Admin Users & Roles</h1>
    <a class="btn" href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
</div>

<div class="card" style="margin-bottom:16px">
    <h3 style="margin-top:0">Create New Admin</h3>
    <form method="post" action="{{ route('admin.admin-users.store') }}" class="row3">
        @csrf
        <div><label>Name</label><input name="name" required placeholder="Admin name"></div>
        <div><label>Email</label><input type="email" name="email" required placeholder="admin@example.com"></div>
        <div><label>Role</label>
            <select name="role" required>
                <option value="super_admin">Super Admin</option>
                <option value="advanced_admin">Advanced Admin</option>
                <option value="blog_seo_admin">Blog SEO Admin</option>
            </select>
        </div>
        <div><label>Password</label><input type="password" name="password" required placeholder="Min 7 chars"></div>
        <div><label>Status</label>
            <select name="is_active">
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="full"><button class="btn" type="submit">Create Admin Account</button></div>
    </form>
</div>

<div class="card">
    <h3 style="margin-top:0">Existing Admin Accounts</h3>
    <table>
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Password</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($adminUsers as $admin)
            <tr>
                <td>
                    <form method="post" action="{{ route('admin.admin-users.update', $admin) }}" class="row3" style="margin:0;display:block;">
                        @csrf
                        <input name="name" value="{{ $admin->name }}" required>
                </td>
                <td><input type="email" name="email" value="{{ $admin->email }}" required></td>
                <td>
                    <select name="role" required>
                        <option value="super_admin" @selected($admin->role==='super_admin')>Super Admin</option>
                        <option value="advanced_admin" @selected($admin->role==='advanced_admin')>Advanced Admin</option>
                        <option value="blog_seo_admin" @selected($admin->role==='blog_seo_admin')>Blog SEO Admin</option>
                    </select>
                </td>
                <td>
                    <select name="is_active">
                        <option value="1" @selected($admin->is_active)>Active</option>
                        <option value="0" @selected(!$admin->is_active)>Inactive</option>
                    </select>
                </td>
                <td><input type="password" name="password" placeholder="Leave blank to keep"></td>
                <td>
                    <button class="btn green" type="submit">Save</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No admin accounts found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
