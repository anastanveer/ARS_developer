@extends('admin.layout', ['title' => 'Leads'])

@section('content')
<div class="top">
    <h1 style="margin:0">Leads & Meetings</h1>
</div>

<div class="card" style="margin-bottom:16px">
    <form method="get" class="row">
        <div><label>Search</label><input type="text" name="q" value="{{ request('q') }}" placeholder="Name, email, phone"></div>
        <div><label>Type</label>
            <select name="type">
                <option value="">All</option>
                @foreach(['contact','meeting','newsletter'] as $type)
                    <option value="{{ $type }}" @selected(request('type')===$type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div><label>Status</label>
            <select name="status">
                <option value="">All</option>
                @foreach(\App\Models\Lead::statusOptions() as $status)
                    <option value="{{ $status }}" @selected(request('status')===$status)>{{ str_replace('_',' ', ucfirst($status)) }}</option>
                @endforeach
            </select>
        </div>
        <div style="align-self:end"><button class="btn" type="submit">Filter</button></div>
    </form>
</div>

<div class="card">
    <table>
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Geo</th><th>Type</th><th>Meeting</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($leads as $lead)
            <tr>
                <td>{{ $lead->id }}</td>
                <td>{{ $lead->name ?: '-' }}</td>
                <td>{{ $lead->email }}</td>
                <td>{{ $lead->country ?: '-' }}<br><span class="muted">{{ $lead->ip ?: '-' }}</span></td>
                <td>{{ strtoupper($lead->type) }}</td>
                <td>
                    @if($lead->meeting_date)
                        {{ $lead->meeting_date->format('d M Y') }}<br>{{ $lead->meeting_slot }}
                        @if($lead->meeting_timezone)<br><span class="muted">{{ $lead->meeting_timezone }}</span>@endif
                    @else
                        -
                    @endif
                </td>
                <td>{{ str_replace('_', ' ', $lead->status) }}@if($lead->is_blocked)<br><span class="pill">Blocked</span>@endif</td>
                <td><a class="btn" href="{{ route('admin.leads.show', $lead) }}">Open</a></td>
            </tr>
        @empty
            <tr><td colspan="8">No leads found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px">{{ $leads->links() }}</div>
</div>
@endsection
