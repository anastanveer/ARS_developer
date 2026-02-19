@extends('admin.layout', ['title' => 'Audit Lab'])

@section('content')
<div class="top">
    <h1 class="page-title">Audit Lab</h1>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('admin.audits.create') }}" class="btn alt">Create New Audit</a>
        <a href="{{ route('admin.audits.index') }}" class="btn gray">Reset</a>
    </div>
</div>

<form method="get" class="row3" style="margin-bottom:14px;">
    <div>
        <label>Search</label>
        <input type="text" name="q" value="{{ $search }}" placeholder="reference, business, website">
    </div>
    <div>
        <label>Risk</label>
        <select name="risk">
            <option value="">All risks</option>
            <option value="high" {{ $risk === 'high' ? 'selected' : '' }}>High</option>
            <option value="medium" {{ $risk === 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="low" {{ $risk === 'low' ? 'selected' : '' }}>Low</option>
        </select>
    </div>
    <div>
        <label>Grade</label>
        <select name="grade">
            <option value="">All grades</option>
            @foreach(['A','B','C','D','F'] as $gradeOption)
                <option value="{{ $gradeOption }}" {{ $grade === $gradeOption ? 'selected' : '' }}>{{ $gradeOption }}</option>
            @endforeach
        </select>
    </div>
    <div class="full">
        <button class="btn" type="submit">Apply Filters</button>
    </div>
</form>

<div class="grid" style="margin-bottom:14px">
    <div class="stat"><b>Total Reports</b><span>{{ number_format((int) $stats['total_reports']) }}</span></div>
    <div class="stat"><b>This Month</b><span>{{ number_format((int) $stats['this_month']) }}</span></div>
    <div class="stat"><b>Average Score</b><span>{{ number_format((float) $stats['avg_score'], 1) }}/100</span></div>
    <div class="stat"><b>Average Security</b><span>{{ number_format((float) $stats['avg_security'], 1) }}/100</span></div>
    <div class="stat"><b>High Risk (Score &lt; 60)</b><span>{{ number_format((int) $stats['high_risk_count']) }}</span></div>
    <div class="stat"><b>Enterprise Ready (90+)</b><span>{{ number_format((int) $stats['enterprise_count']) }}</span></div>
</div>

<div class="card" style="margin-bottom:14px;">
    <h3 style="margin:0 0 10px;">Benchmark Distribution</h3>
    <div class="grid">
        @foreach($benchmarks as $gradeKey => $count)
            <div class="preview-box" style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-weight:700;">Grade {{ $gradeKey }}</span>
                <span class="pill">{{ number_format((int) $count) }}</span>
            </div>
        @endforeach
    </div>
</div>

<div class="row" style="margin-bottom:14px;">
    <div class="card">
        <h3 style="margin:0 0 10px;">Competitor Benchmark (Live)</h3>
        <form id="benchmarkForm" class="row3">
            <div class="full">
                <label>Your Website</label>
                <input type="text" name="primary_url" placeholder="https://arsdeveloper.co.uk" required>
            </div>
            <div><label>Competitor 1</label><input type="text" name="competitor_1" placeholder="https://competitor-one.co.uk"></div>
            <div><label>Competitor 2</label><input type="text" name="competitor_2" placeholder="https://competitor-two.co.uk"></div>
            <div><label>Competitor 3</label><input type="text" name="competitor_3" placeholder="https://competitor-three.co.uk"></div>
            <div class="full"><button class="btn" type="submit">Run Benchmark</button></div>
        </form>
        <div id="benchmarkBox" class="preview-box" style="display:none;margin-top:10px;">
            <p id="benchmarkSummary" style="margin:0 0 8px;font-weight:700;"></p>
            <table id="benchmarkTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Website</th>
                        <th>Overall</th>
                        <th>Perf</th>
                        <th>SEO</th>
                        <th>UX</th>
                        <th>Security</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin:0 0 10px;">Recurring Audit Targets</h3>
        <form action="{{ route('admin.audits.targets.store') }}" method="post" class="row3">
            @csrf
            <div><label>Business Name</label><input type="text" name="business_name" placeholder="ARS Developer UK"></div>
            <div><label>Website URL</label><input type="text" name="website_url" placeholder="https://arsdeveloper.co.uk" required></div>
            <div><label>Frequency</label>
                <select name="frequency">
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
            <div class="full"><button class="btn alt" type="submit">Save Target</button></div>
        </form>
        <table style="margin-top:10px;">
            <thead>
                <tr>
                    <th>Website</th>
                    <th>Freq</th>
                    <th>Status</th>
                    <th>Next Run</th>
                    <th>Run</th>
                </tr>
            </thead>
            <tbody>
            @forelse($targets as $target)
                <tr>
                    <td>{{ $target->website_url }}</td>
                    <td>{{ ucfirst($target->frequency) }}</td>
                    <td>{{ ucfirst($target->status) }}</td>
                    <td>{{ optional($target->next_run_at)->format('d M Y H:i') ?: '-' }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.audits.targets.run', $target) }}" class="inline">
                            @csrf
                            <button class="btn gray" type="submit">Run Now</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No recurring targets yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row" style="margin-bottom:14px;">
    <div class="card">
        <h3 style="margin:0 0 10px;">Recent Scan History</h3>
        <table>
            <thead>
                <tr>
                    <th>When</th>
                    <th>Website</th>
                    <th>Overall</th>
                    <th>Grade</th>
                    <th>Risk</th>
                </tr>
            </thead>
            <tbody>
            @forelse($recentRuns as $run)
                <tr>
                    <td>{{ optional($run->scanned_at)->format('d M Y H:i') ?: '-' }}</td>
                    <td>{{ $run->website_url }}</td>
                    <td>{{ $run->overall_score }}</td>
                    <td>{{ $run->grade ?: '-' }}</td>
                    <td>{{ $run->risk_level ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No scan history found.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div id="trendBox" class="preview-box" style="margin-top:10px;">
            <b>Trend (Last {{ count($trend) }} runs):</b>
            <div id="trendLine" style="margin-top:6px;color:#4a5f82;">
                @foreach($trend as $point)
                    <span style="display:inline-block;margin-right:10px;">{{ $point['label'] }}: <b>{{ $point['overall'] }}</b></span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <h3 style="margin:0 0 10px;">Action Tracker (Fix Queue)</h3>
        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Due</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
            @forelse($openActions as $action)
                <tr>
                    <td>{{ $action->title }}</td>
                    <td>{{ ucfirst($action->severity) }}</td>
                    <td>{{ ucfirst($action->status) }}</td>
                    <td>{{ optional($action->due_date)->format('d M Y') ?: '-' }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.audits.actions.status', $action) }}" class="inline">
                            @csrf
                            <select name="status" style="min-width:120px;">
                                <option value="open" {{ $action->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $action->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ $action->status === 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            <button class="btn gray" type="submit">Save</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No open action items.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="top">
        <h3 style="margin:0">Saved Audit Reports</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Business</th>
                <th>Website</th>
                <th>Overall</th>
                <th>Grade</th>
                <th>Risk</th>
                <th>Security</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($audits as $audit)
                <tr>
                    <td>{{ $audit->reference }}</td>
                    <td>{{ $audit->business_name }}</td>
                    <td><a href="{{ $audit->website_url }}" target="_blank" rel="noopener">{{ $audit->website_url }}</a></td>
                    @php
                        $overall = (int) $audit->overall_score;
                        $gradeValue = $overall >= 90 ? 'A' : ($overall >= 80 ? 'B' : ($overall >= 70 ? 'C' : ($overall >= 60 ? 'D' : 'F')));
                        $riskValue = $overall < 60 ? 'High' : ($overall < 80 ? 'Medium' : 'Low');
                        $riskColor = $riskValue === 'High' ? '#a12828' : ($riskValue === 'Medium' ? '#a66a00' : '#0d8051');
                    @endphp
                    <td>{{ $overall }}/100</td>
                    <td><span class="pill">Grade {{ $gradeValue }}</span></td>
                    <td><span class="pill" style="color:{{ $riskColor }};">{{ $riskValue }}</span></td>
                    <td>{{ $audit->security_score ?: '-' }}</td>
                    <td>{{ optional($audit->created_at)->format('d M Y H:i') }}</td>
                    <td>
                        <a class="btn" href="{{ route('admin.audits.show', $audit) }}">Open</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9">No audit reports found for current filters.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:10px;">
        {{ $audits->links() }}
    </div>
</div>
@endsection

@push('admin_scripts')
<script>
(function () {
    var form = document.getElementById('benchmarkForm');
    if (!form) return;
    var box = document.getElementById('benchmarkBox');
    var summary = document.getElementById('benchmarkSummary');
    var tableBody = document.querySelector('#benchmarkTable tbody');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        var primary = (form.querySelector('input[name=\"primary_url\"]').value || '').trim();
        if (!primary) {
            alert('Primary website URL is required.');
            return;
        }

        var competitors = [
            (form.querySelector('input[name=\"competitor_1\"]').value || '').trim(),
            (form.querySelector('input[name=\"competitor_2\"]').value || '').trim(),
            (form.querySelector('input[name=\"competitor_3\"]').value || '').trim()
        ].filter(function (v) { return v.length > 0; });

        fetch('{{ route('admin.audits.benchmark') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                primary_url: primary,
                competitor_urls: competitors
            })
        })
        .then(function (res) { return res.json(); })
        .then(function (payload) {
            if (!payload || payload.ok !== true) throw new Error('Benchmark failed');
            box.style.display = 'block';
            summary.textContent = payload.summary || 'Benchmark completed.';
            tableBody.innerHTML = '';
            (payload.rows || []).forEach(function (row) {
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td>' + row.position + '</td>' +
                    '<td>' + row.label + '<br><small>' + row.website_url + '</small></td>' +
                    '<td>' + row.overall + '</td>' +
                    '<td>' + row.performance + '</td>' +
                    '<td>' + row.seo + '</td>' +
                    '<td>' + row.ux + '</td>' +
                    '<td>' + row.security + '</td>' +
                    '<td>' + row.grade + '</td>';
                tableBody.appendChild(tr);
            });
        })
        .catch(function () {
            alert('Benchmark request failed. Please check URLs and try again.');
        });
    });
})();
</script>
@endpush
