@extends('admin.layout')

@section('content')
    <div class="top">
        <h1 class="page-title">System Logs Monitor</h1>
        <span class="pill">Date-wise issue tracking + refresh</span>
    </div>

    <div class="card" style="margin-bottom:14px;">
        <form method="get" action="{{ route('admin.logs.index') }}" class="row3" style="align-items:end;">
            <div>
                <label for="log_date"><b>Log Date</b></label>
                <select id="log_date" name="date">
                    @foreach($availableDates as $date)
                        <option value="{{ $date }}" @selected($selectedDate === $date)>{{ $date }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="limit"><b>Rows</b></label>
                <select id="limit" name="limit">
                    @foreach([100, 250, 500, 1000] as $rowLimit)
                        <option value="{{ $rowLimit }}" @selected($limit === $rowLimit)>{{ $rowLimit }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="btn" type="submit">Load Logs</button>
                <a class="btn gray" href="{{ route('admin.logs.index', ['date' => $selectedDate, 'limit' => $limit, 'refresh' => 1]) }}">Refresh Now</a>
            </div>
        </form>
        <small class="muted" style="display:block;margin-top:10px;">
            Nightly digest cron available via <code>logs:daily-digest</code>. Keep server cron active for Laravel scheduler.
        </small>
    </div>

    <div class="grid" style="margin-bottom:14px;">
        <div class="stat">
            <b>Total Entries</b>
            <span>{{ number_format((int) ($digest['total'] ?? 0)) }}</span>
        </div>
        <div class="stat">
            <b>First Seen</b>
            <span style="font-size:16px">{{ $digest['first_seen'] ?? '-' }}</span>
        </div>
        <div class="stat">
            <b>Last Seen</b>
            <span style="font-size:16px">{{ $digest['last_seen'] ?? '-' }}</span>
        </div>
    </div>

    <div class="row3" style="margin-bottom:14px;">
        <div class="card">
            <h3 style="margin-top:0">By Level</h3>
            <table>
                <thead><tr><th>Level</th><th>Count</th></tr></thead>
                <tbody>
                @forelse(($digest['by_level'] ?? []) as $level => $count)
                    <tr><td>{{ $level }}</td><td>{{ $count }}</td></tr>
                @empty
                    <tr><td colspan="2">No data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <h3 style="margin-top:0">By Type</h3>
            <table>
                <thead><tr><th>Type</th><th>Count</th></tr></thead>
                <tbody>
                @forelse(($digest['by_type'] ?? []) as $type => $count)
                    <tr><td>{{ ucfirst($type) }}</td><td>{{ $count }}</td></tr>
                @empty
                    <tr><td colspan="2">No data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <h3 style="margin-top:0">By Channel</h3>
            <table>
                <thead><tr><th>Channel</th><th>Count</th></tr></thead>
                <tbody>
                @forelse(($digest['by_channel'] ?? []) as $channel => $count)
                    <tr><td>{{ $channel }}</td><td>{{ $count }}</td></tr>
                @empty
                    <tr><td colspan="2">No data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Latest Entries ({{ $selectedDate }})</h3>
        <table>
            <thead>
            <tr>
                <th style="width:180px;">Timestamp</th>
                <th style="width:100px;">Level</th>
                <th style="width:120px;">Channel</th>
                <th>Message</th>
                <th style="width:120px;">File</th>
            </tr>
            </thead>
            <tbody>
            @forelse($entries as $entry)
                <tr>
                    <td>{{ $entry['timestamp'] ?? ($entry['date'] . ' ' . ($entry['time'] ?? '')) }}</td>
                    <td><span class="pill">{{ $entry['level'] ?? '-' }}</span></td>
                    <td>{{ $entry['channel'] ?? '-' }}</td>
                    <td style="word-break:break-word">{{ $entry['message'] ?? '-' }}</td>
                    <td>{{ $entry['source_file'] ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No log entries found for this date.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection

