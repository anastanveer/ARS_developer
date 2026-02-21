<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="bingbot" content="noindex, nofollow">
    <link rel="canonical" href="{{ request()->fullUrl() }}">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    <style>
        :root{
            --bg:#f2f6ff;
            --card:#ffffff;
            --line:#e3eaf8;
            --text:#112544;
            --muted:#61759a;
            --brand:#1668ff;
            --brand2:#16b7a3;
            --danger:#da3b52;
            --ok:#168f5d;
            --shadow:0 16px 34px rgba(25,52,105,.08);
            --radius:16px;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0}
        body{
            font-family:"DM Sans",Arial,sans-serif;
            color:var(--text);
            background:
                radial-gradient(circle at 10% 0%, #dce9ff 0%, transparent 28%),
                radial-gradient(circle at 90% 100%, #d9fff5 0%, transparent 24%),
                var(--bg);
        }
        .admin-shell{display:flex;min-height:100vh}

        .sidebar{
            width:270px;
            background:linear-gradient(170deg,#091933 0%,#0f2a57 55%,#144487 100%);
            color:#dce8ff;
            padding:20px 14px;
            position:sticky;
            top:0;
            height:100vh;
            overflow:auto;
            border-right:1px solid rgba(255,255,255,.08);
            transition:width .25s ease,padding .25s ease;
        }
        .sidebar.is-collapsed{
            width:84px;
            padding:20px 10px;
        }
        .brand{
            padding:4px 10px 14px;
            margin-bottom:10px;
            border-bottom:1px solid rgba(255,255,255,.14);
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:8px;
        }
        .brand h2{margin:0;font-size:24px;color:#fff;letter-spacing:.01em}
        .brand small{color:#95acda}
        .sidebar-toggle{
            border:1px solid rgba(255,255,255,.25);
            background:rgba(255,255,255,.12);
            color:#fff;
            border-radius:10px;
            width:34px;
            height:34px;
            cursor:pointer;
            font-size:16px;
        }
        .sidebar.is-collapsed .brand h2,
        .sidebar.is-collapsed .brand small,
        .sidebar.is-collapsed .nav-title,
        .sidebar.is-collapsed .nav-label{
            display:none;
        }
        .sidebar.is-collapsed .brand{
            justify-content:center;
            border-bottom:0;
            padding-bottom:0;
            margin-bottom:6px;
        }
        .sidebar.is-collapsed .sidebar-toggle{
            margin-top:2px;
        }
        .nav-title{
            font-size:11px;
            text-transform:uppercase;
            letter-spacing:.12em;
            color:#90a8d5;
            padding:11px 10px 8px;
        }
        .sidebar a{
            display:block;
            color:#dce8ff;
            text-decoration:none;
            padding:10px 12px;
            border-radius:12px;
            margin-bottom:6px;
            font-size:14px;
            border:1px solid transparent;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }
        .nav-ico{
            display:inline-flex;
            width:20px;
            justify-content:center;
            margin-right:8px;
            font-size:14px;
        }
        .sidebar.is-collapsed a{
            padding:10px;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        .sidebar.is-collapsed .nav-ico{
            margin-right:0;
            font-size:16px;
        }
        .sidebar a:hover,.sidebar a.active{
            background:rgba(22,183,163,.17);
            border-color:rgba(140,255,233,.28);
            color:#fff;
        }
        .logout{margin-top:12px}

        .main{flex:1;padding:24px}
        .admin-topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            margin-bottom:12px;
        }
        .admin-topbar__title{
            margin:0;
            color:#35527f;
            font-size:13px;
            font-weight:700;
            letter-spacing:.04em;
            text-transform:uppercase;
        }
        .bell-wrap{position:relative}
        .bell-btn{
            position:relative;
            width:42px;
            height:42px;
            border-radius:12px;
            border:1px solid #d3e1fa;
            background:#fff;
            color:#214f94;
            font-size:18px;
            cursor:pointer;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }
        .bell-count{
            position:absolute;
            top:-6px;
            right:-6px;
            min-width:20px;
            height:20px;
            border-radius:999px;
            padding:0 6px;
            background:#e63946;
            color:#fff;
            font-size:11px;
            font-weight:700;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border:2px solid #fff;
        }
        .bell-menu{
            position:absolute;
            top:50px;
            right:0;
            width:380px;
            max-width:calc(100vw - 30px);
            background:#fff;
            border:1px solid #d8e5fb;
            border-radius:14px;
            box-shadow:0 20px 35px rgba(16,43,92,.14);
            display:none;
            z-index:999;
            overflow:hidden;
        }
        .bell-menu.open{display:block}
        .bell-menu__head{
            padding:10px 12px;
            border-bottom:1px solid #e8eef9;
            background:#f7faff;
            font-size:13px;
            color:#365886;
            font-weight:700;
        }
        .bell-list{max-height:360px;overflow:auto}
        .bell-item{
            display:block;
            text-decoration:none;
            color:#173257;
            padding:10px 12px;
            border-bottom:1px solid #edf2fb;
        }
        .bell-item:hover{background:#f7fbff}
        .bell-item__label{display:block;font-size:13px;font-weight:700}
        .bell-item__meta{display:block;font-size:12px;color:#5f7699;margin-top:2px}
        .bell-item__time{display:block;font-size:11px;color:#8397b7;margin-top:4px}
        .bell-empty{
            padding:14px 12px;
            color:#6f82a1;
            font-size:13px;
        }
        .surface{
            background:rgba(255,255,255,.45);
            border:1px solid #dfe8fa;
            border-radius:20px;
            padding:16px;
            backdrop-filter:blur(3px);
            margin-bottom:16px;
        }

        .top{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:14px;flex-wrap:wrap}
        .page-title{margin:0;font-size:28px;letter-spacing:.01em}

        .card{
            background:var(--card);
            border:1px solid var(--line);
            border-radius:var(--radius);
            padding:16px;
            box-shadow:var(--shadow);
        }

        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px}
        .row{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
        .row3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
        .row .full,.row3 .full{grid-column:1/-1}

        .stat{
            background:linear-gradient(180deg,#fff 0%,#f9fbff 100%);
            border:1px solid var(--line);
            border-radius:14px;
            padding:14px;
        }
        .stat b{display:block;color:var(--muted);font-size:12px;letter-spacing:.05em;text-transform:uppercase;margin-bottom:8px}
        .stat span{font-size:24px;font-weight:700;color:#0e2e5f;line-height:1.15}

        .muted{color:var(--muted)}
        .pill{display:inline-block;background:#eef3ff;color:#2b4f8a;border:1px solid #ccdaf8;padding:3px 8px;border-radius:999px;font-size:12px}

        table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden}
        th,td{padding:10px;border-bottom:1px solid #edf2fb;text-align:left;vertical-align:top;font-size:14px}
        th{background:#f5f9ff;color:#3b5786;font-weight:700}

        .btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:6px;
            background:var(--brand);
            color:#fff;
            padding:10px 14px;
            border:none;
            border-radius:11px;
            text-decoration:none;
            cursor:pointer;
            font:700 14px/1 "DM Sans",Arial,sans-serif;
            transition:transform .15s ease, box-shadow .2s ease;
            box-shadow:0 10px 24px rgba(22,104,255,.25);
        }
        .btn:hover{transform:translateY(-1px)}
        .btn.gray{background:#6a7894;box-shadow:none}
        .btn.red{background:var(--danger);box-shadow:none}
        .btn.green{background:var(--ok);box-shadow:none}
        .btn.alt{background:linear-gradient(135deg,var(--brand) 0%,var(--brand2) 100%)}

        input,select,textarea{
            width:100%;
            padding:10px 11px;
            border:1px solid #ccd9f1;
            border-radius:11px;
            background:#fff;
            color:var(--text);
            font:14px "DM Sans",Arial,sans-serif;
            outline:none;
        }
        input:focus,select:focus,textarea:focus{border-color:#8ab0ff;box-shadow:0 0 0 3px rgba(22,104,255,.12)}
        textarea{min-height:120px;resize:vertical}
        form.inline{display:inline}

        .preview-box{border:1px dashed #bed0f4;background:#f7faff;padding:12px;border-radius:12px}
        .msg{padding:11px 12px;border-radius:11px;margin-bottom:14px;border:1px solid transparent}
        .ok{background:#e7fbef;color:#0f6a3b;border-color:#b9efce}
        .er{background:#ffe9e9;color:#981a1a;border-color:#ffcdcd}
        .chart-wrap{
            position:relative;
            background:linear-gradient(180deg,#ffffff 0%,#f6f9ff 100%);
            border:1px solid #dfe8fa;
            border-radius:14px;
            padding:10px;
        }
        .chart-canvas{
            width:100%;
            height:290px;
            display:block;
        }
        .chart-legend{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
            margin-top:8px;
            color:#5f7294;
            font-size:12px;
        }
        .chart-legend i{
            width:10px;
            height:10px;
            border-radius:50%;
            display:inline-block;
            margin-right:6px;
            vertical-align:middle;
        }

        @media (max-width:1100px){
            .sidebar{width:240px}
            .main{padding:16px}
        }
        @media (max-width:900px){
            .admin-shell{display:block}
            .sidebar{
                width:100%;
                height:auto;
                position:relative;
                border-right:0;
                border-bottom:1px solid rgba(255,255,255,.15);
                padding:12px;
                transition:none;
            }
            .sidebar.is-collapsed{
                width:100%;
                padding:12px;
            }
            .brand{margin-bottom:8px}
            .nav-title{padding:8px 6px 6px}
            .sidebar a{
                display:inline-flex;
                width:auto;
                margin:0 6px 6px 0;
            }
            .logout{margin-top:8px}
            .main{padding:12px}
            .page-title{font-size:24px}
            .row,.row3{grid-template-columns:1fr}
            .grid{grid-template-columns:repeat(2,minmax(0,1fr))}
        }
        @media (max-width:520px){
            .grid{grid-template-columns:1fr}
            .card{padding:14px}
            th,td{font-size:13px;padding:8px}
        }
    </style>
</head>
<body>
<div class="admin-shell">
    @php
        $adminRole = (string) session('admin_role', 'super_admin');
        $isBlogOnlyAdmin = $adminRole === 'blog_seo_admin';
    @endphp
    <aside class="sidebar">
        <div class="brand">
            <div>
                <h2>ARS Admin</h2>
                <small>{{ str_replace('_', ' ', ucfirst($adminRole)) }}</small>
            </div>
            <button id="sidebarToggleBtn" class="sidebar-toggle" type="button" aria-label="Toggle sidebar">☰</button>
        </div>

        @if(!$isBlogOnlyAdmin)
            <div class="nav-title">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span class="nav-ico">📊</span><span class="nav-label">Dashboard</span></a>
            <a href="{{ route('admin.analytics.index') }}" class="{{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}"><span class="nav-ico">📈</span><span class="nav-label">Sales Analytics</span></a>

            <div class="nav-title">Leads</div>
            <a href="{{ route('admin.leads.index') }}" class="{{ request()->routeIs('admin.leads.*') ? 'active' : '' }}"><span class="nav-ico">🧲</span><span class="nav-label">Leads & Meetings</span></a>
            <a href="{{ route('admin.blocked-contacts.index') }}" class="{{ request()->routeIs('admin.blocked-contacts.*') ? 'active' : '' }}"><span class="nav-ico">🛡️</span><span class="nav-label">Blocked Contacts</span></a>

            <div class="nav-title">Sales</div>
            <a href="{{ route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients.*') ? 'active' : '' }}"><span class="nav-ico">👥</span><span class="nav-label">Clients</span></a>
            <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"><span class="nav-ico">🗂️</span><span class="nav-label">Projects</span></a>
            <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"><span class="nav-ico">⭐</span><span class="nav-label">Client Reviews</span></a>
            <a href="{{ route('admin.operations.index') }}" class="{{ request()->routeIs('admin.operations.*') ? 'active' : '' }}"><span class="nav-ico">⚙️</span><span class="nav-label">Operations & Audit</span></a>
            <a href="{{ route('admin.finance.index') }}" class="{{ request()->routeIs('admin.finance.*') ? 'active' : '' }}"><span class="nav-ico">💷</span><span class="nav-label">Finance Control</span></a>
            <a href="{{ route('admin.audits.index') }}" class="{{ request()->routeIs('admin.audits.*') ? 'active' : '' }}"><span class="nav-ico">🧪</span><span class="nav-label">Audit Lab</span></a>
            <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"><span class="nav-ico">🏷️</span><span class="nav-label">Coupons</span></a>
        @endif

        <div class="nav-title">Content</div>
        @if(!$isBlogOnlyAdmin)
            <a href="{{ route('admin.portfolios.index') }}" class="{{ request()->routeIs('admin.portfolios.*') ? 'active' : '' }}"><span class="nav-ico">🧩</span><span class="nav-label">Portfolio</span></a>
        @endif
        <a href="{{ route('admin.blog-posts.index') }}" class="{{ request()->routeIs('admin.blog-posts.*') ? 'active' : '' }}"><span class="nav-ico">✍️</span><span class="nav-label">Blog Posts</span></a>
        @if(in_array($adminRole, ['super_admin', 'advanced_admin'], true))
            <div class="nav-title">Administration</div>
            <a href="{{ route('admin.admin-users.index') }}" class="{{ request()->routeIs('admin.admin-users.*') ? 'active' : '' }}"><span class="nav-ico">🔐</span><span class="nav-label">Admin Users</span></a>
        @endif

        <form class="logout" action="{{ route('admin.logout') }}" method="post">
            @csrf
            <button class="btn red" type="submit" style="width:100%">Logout</button>
        </form>
    </aside>

    <main class="main">
        @php
            $showOpsBell = !$isBlogOnlyAdmin;
            $adminBellActivity = collect();
            $adminBellNewCount = 0;

            if ($showOpsBell) {
                $adminRequirementActivity = \App\Models\ProjectRequirement::query()
                    ->with(['project.client'])
                    ->where('source', 'client')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'at' => $item->created_at,
                            'label' => 'New client requirement',
                            'detail' => $item->title,
                            'client' => $item->project?->client?->name ?: 'Client',
                            'project' => $item->project?->title ?: '-',
                            'project_id' => $item->project_id,
                            'type' => 'requirement',
                            'activity_id' => (int) $item->id,
                        ];
                    });

                $adminPaymentActivity = \App\Models\Payment::query()
                    ->with(['project.client', 'invoice'])
                    ->where(function ($q) {
                        $q->where('notes', 'like', 'Paid by client via portal.%')
                            ->orWhere('method', 'Portal Payment');
                    })
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'at' => $item->created_at,
                            'label' => 'Client payment received',
                            'detail' => ($item->invoice?->invoice_number ?: 'Payment') . ' - ' . ($item->project?->currency ?: 'GBP') . ' ' . number_format((float) $item->amount, 2),
                            'client' => $item->project?->client?->name ?: 'Client',
                            'project' => $item->project?->title ?: '-',
                            'project_id' => $item->project_id,
                            'type' => 'payment',
                            'activity_id' => (int) $item->id,
                        ];
                    });

                $adminReviewActivity = collect();
                if (\Illuminate\Support\Facades\Schema::hasTable('client_reviews')) {
                    $adminReviewActivity = \App\Models\ClientReview::query()
                        ->with(['project.client'])
                        ->whereNotNull('submitted_at')
                        ->where('is_approved', false)
                        ->latest('submitted_at')
                        ->limit(10)
                        ->get()
                        ->map(function ($item) {
                            return [
                                'at' => $item->submitted_at ?: $item->created_at,
                                'label' => 'New review submitted',
                                'detail' => $item->review_title ?: 'Client review pending approval',
                                'client' => $item->project?->client?->name ?: ($item->reviewer_name ?: 'Client'),
                                'project' => $item->project?->title ?: '-',
                                'project_id' => $item->project_id,
                                'type' => 'review',
                                'activity_id' => (int) $item->id,
                            ];
                        });
                }

                $adminBellActivity = $adminRequirementActivity
                    ->merge($adminPaymentActivity)
                    ->merge($adminReviewActivity)
                    ->sortByDesc('at')
                    ->take(12)
                    ->values();

                $adminUserId = (int) session('admin_user_id', 0);
                $readMap = collect();
                if ($adminUserId > 0 && $adminBellActivity->isNotEmpty()) {
                    $pairs = $adminBellActivity->map(fn ($i) => $i['type'].'#'.$i['activity_id'])->values();
                    $readMap = \App\Models\AdminNotificationRead::query()
                        ->where('admin_user_id', $adminUserId)
                        ->where(function ($q) use ($adminBellActivity) {
                            foreach ($adminBellActivity as $item) {
                                $q->orWhere(function ($sub) use ($item) {
                                    $sub->where('activity_type', $item['type'])
                                        ->where('activity_id', $item['activity_id']);
                                });
                            }
                        })
                        ->get()
                        ->keyBy(fn ($row) => $row->activity_type.'#'.$row->activity_id);
                }

                $adminBellActivity = $adminBellActivity->map(function ($item) use ($readMap) {
                    $key = $item['type'].'#'.$item['activity_id'];
                    $item['is_read'] = $readMap->has($key);
                    return $item;
                });

                $adminBellNewCount = $adminBellActivity->where('is_read', false)->count();
            }
        @endphp
        <div class="admin-topbar">
            <p class="admin-topbar__title">Operations Monitor · {{ session('admin_name', session('admin_email', 'Admin')) }}</p>
            @if($showOpsBell)
                <div class="bell-wrap">
                    <button id="adminBellBtn" class="bell-btn" type="button" aria-label="Client activity notifications">
                        <span aria-hidden="true">🔔</span>
                        @if($adminBellNewCount > 0)
                            <span class="bell-count">{{ $adminBellNewCount }}</span>
                        @endif
                    </button>
                    <div id="adminBellMenu" class="bell-menu" aria-label="Recent client activity">
                        <div class="bell-menu__head">
                            Recent Client Activity
                            @if($adminBellNewCount > 0)
                                <form method="post" action="{{ route('admin.notifications.mark-all') }}" style="float:right;margin:0;">
                                    @csrf
                                    <button type="submit" style="border:0;background:transparent;color:#1a66cf;font-size:12px;font-weight:700;cursor:pointer;">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        <div class="bell-list">
                            @forelse($adminBellActivity as $item)
                                <a class="bell-item" href="{{ !empty($item['project_id']) ? route('admin.notifications.open', ['type' => $item['type'], 'activityId' => $item['activity_id'], 'projectId' => $item['project_id']]) : route('admin.dashboard') }}" style="{{ empty($item['is_read']) ? 'background:#f4f9ff;' : '' }}">
                                    <span class="bell-item__label">{{ $item['label'] }}</span>
                                    <span class="bell-item__meta">{{ $item['client'] }} · {{ $item['project'] }}</span>
                                    <span class="bell-item__meta">{{ $item['detail'] }}</span>
                                    <span class="bell-item__time">{{ optional($item['at'])->diffForHumans() }}</span>
                                </a>
                            @empty
                                <div class="bell-empty">No recent client activity found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="surface">
            @if (isset($errors) && $errors->any())
                <div class="msg er">{{ $errors->first() }}</div>
            @endif
            @if(session('success'))<div class="msg ok">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="msg er">{{ session('error') }}</div>@endif
            @yield('content')
        </div>
    </main>
</div>
<script>
    (function () {
        var sidebar = document.querySelector('.sidebar');
        var sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        if (sidebar && sidebarToggleBtn) {
            if (localStorage.getItem('ars_admin_sidebar_collapsed') === '1' && window.innerWidth > 900) {
                sidebar.classList.add('is-collapsed');
            }
            sidebarToggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('is-collapsed');
                localStorage.setItem('ars_admin_sidebar_collapsed', sidebar.classList.contains('is-collapsed') ? '1' : '0');
            });
        }

        var btn = document.getElementById('adminBellBtn');
        var menu = document.getElementById('adminBellMenu');
        if (!btn || !menu) return;

        btn.addEventListener('click', function () {
            menu.classList.toggle('open');
        });

        document.addEventListener('click', function (event) {
            if (!menu.contains(event.target) && !btn.contains(event.target)) {
                menu.classList.remove('open');
            }
        });
    })();
</script>
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
    (function () {
        function initCkEditor() {
            if (!window.CKEDITOR) {
                return;
            }
            CKEDITOR.config.versionCheck = false;

            var fullFields = document.querySelectorAll('textarea[data-editor="full"]');
            fullFields.forEach(function (field) {
                if (field.id && !CKEDITOR.instances[field.id]) {
                    var editor = CKEDITOR.replace(field.id, {
                        height: 360,
                        allowedContent: true,
                        removeButtons: '',
                        extraAllowedContent: '*(*);*{*}',
                    });
                    editor.on('change', function () {
                        field.value = editor.getData();
                        field.dispatchEvent(new Event('input', { bubbles: true }));
                    });
                }
            });

            var compactFields = document.querySelectorAll('textarea[data-editor="compact"]');
            compactFields.forEach(function (field) {
                if (field.id && !CKEDITOR.instances[field.id]) {
                    var editor = CKEDITOR.replace(field.id, {
                        height: 180,
                        allowedContent: true,
                        extraAllowedContent: '*(*);*{*}',
                        toolbar: [
                            { name: 'document', items: ['Source'] },
                            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'RemoveFormat'] },
                            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Blockquote'] },
                            { name: 'links', items: ['Link', 'Unlink'] },
                            { name: 'clipboard', items: ['Undo', 'Redo'] },
                        ],
                    });
                    editor.on('change', function () {
                        field.value = editor.getData();
                        field.dispatchEvent(new Event('input', { bubbles: true }));
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initCkEditor);
    })();
</script>
@stack('admin_scripts')
</body>
</html>
