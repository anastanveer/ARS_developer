<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="bingbot" content="noindex, nofollow">
    <link rel="canonical" href="{{ request()->fullUrl() }}">
    <title>Admin Login</title>
    <style>
        *{box-sizing:border-box}
        body{
            margin:0;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            font-family:"DM Sans",Arial,sans-serif;
            background: radial-gradient(circle at 15% 10%, #1d7fff 0%, transparent 35%),
                        radial-gradient(circle at 85% 90%, #00a0b0 0%, transparent 30%),
                        linear-gradient(145deg, #08152e 0%, #0f2347 50%, #09162a 100%);
            color:#10213a;
            padding:20px;
        }
        .panel{
            width:100%;
            max-width:470px;
            border-radius:20px;
            border:1px solid rgba(255,255,255,.28);
            background:rgba(255,255,255,.93);
            backdrop-filter: blur(8px);
            box-shadow:0 30px 70px rgba(0,0,0,.28);
            overflow:hidden;
        }
        .panel__top{
            padding:26px 26px 20px;
            background:linear-gradient(135deg, #0d6efd 0%, #00a0b0 100%);
            color:#fff;
        }
        .panel__badge{
            display:inline-block;
            font-size:11px;
            letter-spacing:.08em;
            text-transform:uppercase;
            border:1px solid rgba(255,255,255,.4);
            border-radius:999px;
            padding:5px 10px;
            margin-bottom:10px;
        }
        .panel__title{margin:0;font-size:30px;line-height:1.1}
        .panel__sub{margin:7px 0 0;font-size:14px;opacity:.92}
        .panel__body{padding:24px 26px 26px}
        label{display:block;font-size:14px;font-weight:600;margin-bottom:6px;color:#233f64}
        .field{margin-bottom:14px}
        input{
            width:100%;
            height:48px;
            border:1px solid #c6d6ef;
            border-radius:12px;
            padding:0 14px;
            outline:none;
            font-size:15px;
            transition:border-color .2s, box-shadow .2s;
            background:#fff;
        }
        input:focus{
            border-color:#0d6efd;
            box-shadow:0 0 0 3px rgba(13,110,253,.12);
        }
        button{
            width:100%;
            height:50px;
            border:0;
            border-radius:12px;
            cursor:pointer;
            font-size:15px;
            font-weight:700;
            letter-spacing:.02em;
            color:#fff;
            background:linear-gradient(135deg, #0d6efd 0%, #00a0b0 100%);
        }
        .msg{padding:11px 12px;border-radius:10px;margin-bottom:14px;font-size:14px}
        .er{background:#ffe1e1;color:#9d1c1c}
        .demo{
            margin-top:14px;
            font-size:12px;
            color:#4b5f7d;
            background:#eef5ff;
            border:1px dashed #bfd3f3;
            border-radius:10px;
            padding:10px 12px;
            line-height:1.5;
        }
    </style>
</head>
<body>
<div class="panel">
    <div class="panel__top">
        <span class="panel__badge">ARS Secure Console</span>
        <h1 class="panel__title">Admin Login</h1>
        <p class="panel__sub">Sign in to manage leads, meetings, portfolio and campaigns.</p>
    </div>
    <div class="panel__body">
        @if(session('error'))<div class="msg er">{{ session('error') }}</div>@endif
        <form method="post" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="field">
                <label>Login As</label>
                <select name="role" required style="width:100%;height:48px;border:1px solid #c6d6ef;border-radius:12px;padding:0 14px;outline:none;font-size:15px;background:#fff;">
                    <option value="super_admin" @selected(old('role') === 'super_admin')>Super Admin</option>
                    <option value="advanced_admin" @selected(old('role') === 'advanced_admin')>Advanced Admin</option>
                    <option value="blog_seo_admin" @selected(old('role') === 'blog_seo_admin')>Blog SEO Admin</option>
                </select>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login to Dashboard</button>
        </form>
        <div class="demo">
            Super Admin: <strong>ars@gmail.com</strong> / <strong>1234567</strong><br>
            Blog SEO Admin: <strong>ars@gmail.com</strong> / <strong>1234567</strong> (select Blog SEO role)<br>
            Advanced Admin: <strong>arsdeveloper@gmail.com</strong> / <strong>1234567</strong>
        </div>
    </div>
</div>
</body>
</html>
