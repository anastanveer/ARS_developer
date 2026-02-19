@extends('admin.layout', ['title' => 'Admin Dashboard'])

@section('content')
<div class="top">
    <h1 class="page-title">Business Dashboard</h1>
    <a href="{{ route('admin.analytics.index') }}" class="btn alt">Open Sales Analytics</a>
</div>

<div class="grid" style="margin-bottom:14px">
    <div class="stat"><b>Total Leads</b><span>{{ $stats['total_leads'] }}</span></div>
    <div class="stat"><b>New Leads</b><span>{{ $stats['new_leads'] }}</span></div>
    <div class="stat"><b>Meetings</b><span>{{ $stats['meetings'] }}</span></div>
    <div class="stat"><b>Clients</b><span>{{ $stats['clients'] }}</span></div>
    <div class="stat"><b>Projects</b><span>{{ $stats['projects'] }}</span></div>
    <div class="stat"><b>Revenue</b><span>GBP {{ number_format((float) $stats['revenue'], 0) }}</span></div>
    <div class="stat"><b>Client Actions (7d)</b><span>{{ $stats['client_actions_7d'] }}</span></div>
</div>

<div class="row" style="margin-bottom:14px">
    <div class="card">
        <div class="top" style="margin-bottom:10px">
            <h3 style="margin:0">Monthly Sales Trend</h3>
            <span class="pill">Last Entries</span>
        </div>
        <div class="chart-wrap">
            <canvas id="dashboardSalesChart" class="chart-canvas" aria-label="Monthly sales chart"></canvas>
            <div class="chart-legend">
                <span><i style="background:#1668ff"></i>Sales (GBP)</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="top" style="margin-bottom:10px">
            <h3 style="margin:0">Leads vs Clients</h3>
            <span class="pill">Last 6 Months</span>
        </div>
        <div class="chart-wrap">
            <canvas id="dashboardLeadClientChart" class="chart-canvas" aria-label="Leads and clients chart"></canvas>
            <div class="chart-legend">
                <span><i style="background:#14b8a6"></i>Leads</span>
                <span><i style="background:#f59e0b"></i>Clients</span>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:14px">
    <div class="top">
        <h3 style="margin:0">Recent Client Activity</h3>
        <span class="pill">Portal + Client Inputs</span>
    </div>
    <table>
        <thead><tr><th>When</th><th>Client</th><th>Project</th><th>Activity</th><th>Detail</th><th>Meta</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($latestClientActivity as $item)
            <tr>
                <td>{{ optional($item['at'])->format('d M Y H:i') }}</td>
                <td>{{ $item['client'] }}</td>
                <td>{{ $item['project'] }}</td>
                <td>{{ $item['label'] }}</td>
                <td>{{ $item['detail'] }}</td>
                <td>{{ $item['meta'] }}</td>
                <td>
                    @if(!empty($item['project_id']))
                        <a class="btn" href="{{ route('admin.projects.show', $item['project_id']) }}">Open</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No client activity yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card" style="margin-bottom:14px">
    <div class="top">
        <h3 style="margin:0">Upcoming Meetings</h3>
    </div>
    <table>
        <thead><tr><th>Name</th><th>Email</th><th>Date</th><th>Slot</th><th>Timezone</th><th>Type</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($upcomingMeetings as $lead)
            <tr>
                <td>{{ $lead->name }}</td>
                <td>{{ $lead->email }}</td>
                <td>{{ optional($lead->meeting_date)->format('d M Y') }}</td>
                <td>{{ $lead->meeting_slot }}</td>
                <td>{{ $lead->meeting_timezone ?: 'Europe/London' }}</td>
                <td>{{ $lead->project_type }}</td>
                <td>{{ str_replace('_',' ', ucfirst($lead->status)) }}</td>
            </tr>
        @empty
            <tr><td colspan="7">No meeting bookings found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="row">
    <div class="card">
        <div class="top">
            <h3 style="margin:0">Latest Leads</h3>
        </div>
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Type</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($latestLeads as $lead)
                <tr>
                    <td>{{ $lead->name ?: '-' }}</td>
                    <td>{{ $lead->email }}</td>
                    <td>{{ strtoupper($lead->type) }}</td>
                    <td>{{ str_replace('_', ' ', $lead->status) }}</td>
                    <td><a class="btn" href="{{ route('admin.leads.show', $lead) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="5">No leads found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="top">
            <h3 style="margin:0">Latest Projects</h3>
        </div>
        <table>
            <thead><tr><th>Project</th><th>Client</th><th>Status</th><th>Budget</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($latestProjects as $project)
                <tr>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->client?->name ?: '-' }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($project->status)) }}</td>
                    <td>{{ $project->currency }} {{ number_format((float) $project->budget_total, 2) }}</td>
                    <td><a class="btn" href="{{ route('admin.projects.show', $project) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="5">No projects found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('admin_scripts')
<script>
(function () {
    function drawLineChart(canvasId, labels, values, color) {
        var canvas = document.getElementById(canvasId);
        if (!canvas || !labels.length || !values.length) return;
        var dpr = window.devicePixelRatio || 1;
        var rect = canvas.getBoundingClientRect();
        canvas.width = Math.max(320, rect.width) * dpr;
        canvas.height = 290 * dpr;
        var ctx = canvas.getContext('2d');
        ctx.scale(dpr, dpr);
        var w = Math.max(320, rect.width);
        var h = 290;
        var pad = {l: 40, r: 16, t: 16, b: 32};
        var iw = w - pad.l - pad.r;
        var ih = h - pad.t - pad.b;
        var max = Math.max.apply(null, values) || 1;
        var min = Math.min.apply(null, values) || 0;
        if (max === min) max = min + 1;

        ctx.clearRect(0, 0, w, h);
        ctx.strokeStyle = '#e4ebf8';
        ctx.lineWidth = 1;
        for (var g = 0; g < 4; g++) {
            var gy = pad.t + (ih * g / 3);
            ctx.beginPath();
            ctx.moveTo(pad.l, gy);
            ctx.lineTo(w - pad.r, gy);
            ctx.stroke();
        }

        var points = values.map(function (v, i) {
            var x = pad.l + (iw * (labels.length === 1 ? 0.5 : i / (labels.length - 1)));
            var y = pad.t + ih - ((v - min) / (max - min)) * ih;
            return {x: x, y: y, v: v};
        });

        var grad = ctx.createLinearGradient(0, pad.t, 0, h - pad.b);
        grad.addColorStop(0, color + '33');
        grad.addColorStop(1, color + '03');

        ctx.beginPath();
        points.forEach(function (p, i) { i === 0 ? ctx.moveTo(p.x, p.y) : ctx.lineTo(p.x, p.y); });
        ctx.lineTo(points[points.length - 1].x, h - pad.b);
        ctx.lineTo(points[0].x, h - pad.b);
        ctx.closePath();
        ctx.fillStyle = grad;
        ctx.fill();

        ctx.beginPath();
        points.forEach(function (p, i) { i === 0 ? ctx.moveTo(p.x, p.y) : ctx.lineTo(p.x, p.y); });
        ctx.strokeStyle = color;
        ctx.lineWidth = 2.5;
        ctx.stroke();

        ctx.fillStyle = '#60759a';
        ctx.font = '12px DM Sans, Arial';
        labels.forEach(function (label, i) {
            var x = pad.l + (iw * (labels.length === 1 ? 0.5 : i / (labels.length - 1)));
            ctx.fillText(label, x - 18, h - 10);
        });
    }

    function drawBarChart(canvasId, labels, valuesA, valuesB, colorA, colorB) {
        var canvas = document.getElementById(canvasId);
        if (!canvas || !labels.length) return;
        var dpr = window.devicePixelRatio || 1;
        var rect = canvas.getBoundingClientRect();
        canvas.width = Math.max(320, rect.width) * dpr;
        canvas.height = 290 * dpr;
        var ctx = canvas.getContext('2d');
        ctx.scale(dpr, dpr);
        var w = Math.max(320, rect.width), h = 290;
        var pad = {l: 34, r: 12, t: 16, b: 36};
        var iw = w - pad.l - pad.r, ih = h - pad.t - pad.b;
        var max = Math.max(1, Math.max.apply(null, valuesA.concat(valuesB)));
        var groupW = iw / labels.length;
        var barW = Math.min(20, groupW * 0.26);

        ctx.clearRect(0, 0, w, h);
        ctx.strokeStyle = '#e4ebf8';
        for (var g = 0; g < 4; g++) {
            var gy = pad.t + (ih * g / 3);
            ctx.beginPath(); ctx.moveTo(pad.l, gy); ctx.lineTo(w - pad.r, gy); ctx.stroke();
        }

        labels.forEach(function (label, i) {
            var gx = pad.l + groupW * i + groupW / 2;
            var ha = (valuesA[i] / max) * ih;
            var hb = (valuesB[i] / max) * ih;
            ctx.fillStyle = colorA;
            ctx.fillRect(gx - barW - 2, h - pad.b - ha, barW, ha);
            ctx.fillStyle = colorB;
            ctx.fillRect(gx + 2, h - pad.b - hb, barW, hb);
            ctx.fillStyle = '#60759a';
            ctx.font = '12px DM Sans, Arial';
            ctx.fillText(label, gx - 18, h - 12);
        });
    }

    var sales = @json($salesTrend->values());
    drawLineChart(
        'dashboardSalesChart',
        sales.map(function (x) { return x.label; }),
        sales.map(function (x) { return Number(x.value || 0); }),
        '#1668ff'
    );

    var lc = @json($monthlyLeadClient->values());
    drawBarChart(
        'dashboardLeadClientChart',
        lc.map(function (x) { return x.label; }),
        lc.map(function (x) { return Number(x.leads || 0); }),
        lc.map(function (x) { return Number(x.clients || 0); }),
        '#14b8a6',
        '#f59e0b'
    );

    window.addEventListener('resize', function () {
        drawLineChart(
            'dashboardSalesChart',
            sales.map(function (x) { return x.label; }),
            sales.map(function (x) { return Number(x.value || 0); }),
            '#1668ff'
        );
        drawBarChart(
            'dashboardLeadClientChart',
            lc.map(function (x) { return x.label; }),
            lc.map(function (x) { return Number(x.leads || 0); }),
            lc.map(function (x) { return Number(x.clients || 0); }),
            '#14b8a6',
            '#f59e0b'
        );
    });
})();
</script>
@endpush
