@extends('admin.layout', ['title' => 'Sales Analytics'])

@section('content')
<div class="top">
    <h1 class="page-title">Sales Analytics</h1>
</div>

<div class="grid" style="margin-bottom:14px">
    <div class="stat"><b>Total Tracked Sales</b><span>GBP {{ number_format($totals['sales'], 0) }}</span></div>
    <div class="stat"><b>Total Work Value</b><span>GBP {{ number_format($totals['work'], 0) }}</span></div>
    <div class="stat"><b>This Year Sales</b><span>GBP {{ number_format($thisYearSales, 0) }}</span></div>
    <div class="stat"><b>New Clients</b><span>{{ $totals['clients'] }}</span></div>
    <div class="stat"><b>Leads</b><span>{{ $totals['leads'] }}</span></div>
    <div class="stat"><b>Payments Logged</b><span>GBP {{ number_format($totals['payments_logged'], 0) }}</span></div>
</div>

<div class="row" style="margin-bottom:14px">
    <div class="card">
        <h3 style="margin-top:0">Add / Update Monthly Performance</h3>
        <form action="{{ route('admin.analytics.monthly.store') }}" method="post" class="row3">
            @csrf
            <div><label>Month</label><input type="month" name="month" required></div>
            <div><label>Sales Amount (GBP)</label><input type="number" step="0.01" min="0" name="sales_amount" required></div>
            <div><label>Work Value (GBP)</label><input type="number" step="0.01" min="0" name="work_value" required></div>
            <div><label>New Clients</label><input type="number" min="0" name="new_clients_count" required></div>
            <div><label>Leads Received</label><input type="number" min="0" name="leads_count" required></div>
            <div class="full"><label>Notes</label><textarea name="notes" placeholder="Month summary and campaign notes"></textarea></div>
            <div class="full"><button class="btn alt" type="submit">Save Monthly Data</button></div>
        </form>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Add Source Breakdown</h3>
        <form action="{{ route('admin.analytics.source.store') }}" method="post" class="row3">
            @csrf
            <div><label>Month Record</label>
                <select name="monthly_metric_id" required>
                    <option value="">Select month</option>
                    @foreach($metrics as $metric)
                        <option value="{{ $metric->id }}">{{ $metric->month->format('F Y') }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Source Name</label><input name="source_name" required placeholder="Google / Referral / Facebook"></div>
            <div><label>Leads</label><input type="number" min="0" name="leads_count" required></div>
            <div><label>Clients</label><input type="number" min="0" name="clients_count" required></div>
            <div><label>Sales (GBP)</label><input type="number" step="0.01" min="0" name="sales_amount" required></div>
            <div class="full"><button class="btn" type="submit">Add Source Metric</button></div>
        </form>

        @if($latestMetric)
            <div class="preview-box" style="margin-top:12px">
                <strong>{{ $latestMetric->month->format('F Y') }} Breakdown</strong>
                <table style="margin-top:8px">
                    <thead><tr><th>Source</th><th>Leads</th><th>Clients</th><th>Sales</th></tr></thead>
                    <tbody>
                    @forelse($latestSourceMetrics as $src)
                        <tr>
                            <td>{{ $src->source_name }}</td>
                            <td>{{ $src->leads_count }}</td>
                            <td>{{ $src->clients_count }}</td>
                            <td>GBP {{ number_format((float) $src->sales_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No source entries yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="row" style="margin-bottom:14px">
    <div class="card">
        <div class="top" style="margin-bottom:10px"><h3 style="margin:0">Sales by Month</h3></div>
        <div class="chart-wrap">
            <canvas id="analyticsSalesChart" class="chart-canvas" aria-label="Sales by month chart"></canvas>
            <div class="chart-legend">
                <span><i style="background:#1668ff"></i>Sales (GBP)</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="top" style="margin-bottom:10px"><h3 style="margin:0">Clients by Month</h3></div>
        <div class="chart-wrap">
            <canvas id="analyticsClientChart" class="chart-canvas" aria-label="Clients by month chart"></canvas>
            <div class="chart-legend">
                <span><i style="background:#f59e0b"></i>New Clients</span>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom:14px">
    <div class="card">
        <h3 style="margin-top:0">Top Countries (Leads)</h3>
        <table>
            <thead><tr><th>Country</th><th>Leads</th></tr></thead>
            <tbody>
            @forelse($autoLeadCountries as $country)
                <tr><td>{{ $country->country }}</td><td>{{ $country->total }}</td></tr>
            @empty
                <tr><td colspan="2">No country data yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Top Lead Sources</h3>
        <table>
            <thead><tr><th>Source</th><th>Leads</th></tr></thead>
            <tbody>
            @forelse($autoLeadSources as $source => $count)
                <tr><td>{{ $source }}</td><td>{{ $count }}</td></tr>
            @empty
                <tr><td colspan="2">No source data yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-bottom:14px">
    <h3 style="margin-top:0">System Trend (Last 12 Months)</h3>
    <div class="chart-wrap">
        <canvas id="analyticsSystemTrendChart" class="chart-canvas" aria-label="System trend chart"></canvas>
        <div class="chart-legend">
            <span><i style="background:#14b8a6"></i>Leads</span>
            <span><i style="background:#f59e0b"></i>Clients</span>
        </div>
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0">Monthly Entries Log</h3>
    <table>
        <thead><tr><th>Month</th><th>Sales</th><th>Work Value</th><th>Clients</th><th>Leads</th><th>Notes</th></tr></thead>
        <tbody>
        @forelse($metrics->sortByDesc('month') as $metric)
            <tr>
                <td>{{ $metric->month->format('F Y') }}</td>
                <td>GBP {{ number_format((float) $metric->sales_amount, 2) }}</td>
                <td>GBP {{ number_format((float) $metric->work_value, 2) }}</td>
                <td>{{ $metric->new_clients_count }}</td>
                <td>{{ $metric->leads_count }}</td>
                <td>{{ $metric->notes ?: '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="6">No monthly analytics added yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('admin_scripts')
<script>
(function () {
    function setupCanvas(id) {
        var canvas = document.getElementById(id);
        if (!canvas) return null;
        var dpr = window.devicePixelRatio || 1;
        var width = Math.max(320, canvas.getBoundingClientRect().width);
        canvas.width = width * dpr;
        canvas.height = 290 * dpr;
        var ctx = canvas.getContext('2d');
        ctx.scale(dpr, dpr);
        return {canvas: canvas, ctx: ctx, width: width, height: 290};
    }

    function drawGrid(ctx, w, h, pad) {
        var ih = h - pad.t - pad.b;
        ctx.strokeStyle = '#e4ebf8';
        ctx.lineWidth = 1;
        for (var i = 0; i < 4; i++) {
            var y = pad.t + (ih * i / 3);
            ctx.beginPath();
            ctx.moveTo(pad.l, y);
            ctx.lineTo(w - pad.r, y);
            ctx.stroke();
        }
    }

    function drawLine(id, labels, values, color) {
        var c = setupCanvas(id);
        if (!c || !labels.length) return;
        var ctx = c.ctx, w = c.width, h = c.height;
        var pad = {l: 40, r: 14, t: 16, b: 34};
        var iw = w - pad.l - pad.r, ih = h - pad.t - pad.b;
        var max = Math.max(1, Math.max.apply(null, values));
        var min = Math.min.apply(null, values);
        if (max === min) max = min + 1;
        ctx.clearRect(0, 0, w, h);
        drawGrid(ctx, w, h, pad);

        var points = values.map(function (v, i) {
            var x = pad.l + (iw * (labels.length === 1 ? 0.5 : i / (labels.length - 1)));
            var y = pad.t + ih - ((v - min) / (max - min)) * ih;
            return {x: x, y: y};
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
            ctx.fillText(label, x - 18, h - 11);
        });
    }

    function drawDualBars(id, labels, dataA, dataB, colorA, colorB) {
        var c = setupCanvas(id);
        if (!c || !labels.length) return;
        var ctx = c.ctx, w = c.width, h = c.height;
        var pad = {l: 34, r: 12, t: 16, b: 36};
        var iw = w - pad.l - pad.r, ih = h - pad.t - pad.b;
        var max = Math.max(1, Math.max.apply(null, dataA.concat(dataB)));
        var groupW = iw / labels.length;
        var barW = Math.min(16, groupW * 0.22);
        ctx.clearRect(0, 0, w, h);
        drawGrid(ctx, w, h, pad);

        labels.forEach(function (label, i) {
            var gx = pad.l + groupW * i + groupW / 2;
            var ha = (dataA[i] / max) * ih;
            var hb = (dataB[i] / max) * ih;
            ctx.fillStyle = colorA;
            ctx.fillRect(gx - barW - 2, h - pad.b - ha, barW, ha);
            ctx.fillStyle = colorB;
            ctx.fillRect(gx + 2, h - pad.b - hb, barW, hb);
            ctx.fillStyle = '#60759a';
            ctx.font = '11px DM Sans, Arial';
            ctx.fillText(label, gx - 17, h - 11);
        });
    }

    var sales = @json($salesByMonth);
    var clients = @json($clientsByMonth);
    var system = @json($monthlyAuto->values());

    function renderAll() {
        drawLine(
            'analyticsSalesChart',
            sales.map(function (x) { return x.label; }),
            sales.map(function (x) { return Number(x.value || 0); }),
            '#1668ff'
        );
        drawLine(
            'analyticsClientChart',
            clients.map(function (x) { return x.label; }),
            clients.map(function (x) { return Number(x.value || 0); }),
            '#f59e0b'
        );
        drawDualBars(
            'analyticsSystemTrendChart',
            system.map(function (x) { return x.label; }),
            system.map(function (x) { return Number(x.leads || 0); }),
            system.map(function (x) { return Number(x.clients || 0); }),
            '#14b8a6',
            '#f59e0b'
        );
    }

    renderAll();
    window.addEventListener('resize', renderAll);
})();
</script>
@endpush
