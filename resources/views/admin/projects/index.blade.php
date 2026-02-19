@extends('admin.layout', ['title' => 'Projects'])

@section('content')
<div class="top">
    <h1 style="margin:0">Projects</h1>
    <a class="btn" href="{{ route('admin.projects.create') }}">Create Project</a>
</div>

<div class="card" style="margin-bottom:16px">
    <form method="get" class="row3">
        <div><label>Search</label><input type="text" name="q" value="{{ request('q') }}" placeholder="Title, client, type"></div>
        <div><label>Status</label>
            <select name="status">
                <option value="">All</option>
                @foreach(['planning','in_progress','on_hold','delivered','closed'] as $status)
                    <option value="{{ $status }}" @selected(request('status')===$status)>{{ str_replace('_',' ', ucfirst($status)) }}</option>
                @endforeach
            </select>
        </div>
        <div style="align-self:end"><button class="btn" type="submit">Filter</button></div>
    </form>
</div>

<div class="card">
    <table>
        <thead><tr><th>#</th><th>Project</th><th>Client</th><th>Timeline</th><th>Budget</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td><strong>{{ $project->title }}</strong><br><span class="muted">{{ $project->type ?: '-' }}</span></td>
                <td>{{ $project->client?->name }}<br><span class="muted">{{ $project->client?->company }}</span></td>
                <td>{{ optional($project->start_date)->format('d M Y') ?: '-' }}<br>{{ optional($project->delivery_date)->format('d M Y') ?: '-' }}</td>
                <td>{{ $project->currency }} {{ number_format((float)$project->budget_total, 2) }}<br><span class="muted">Paid: {{ number_format((float)$project->paid_total, 2) }}</span></td>
                <td><span class="pill">{{ str_replace('_',' ', ucfirst($project->status)) }}</span></td>
                <td>
                    <a class="btn" href="{{ route('admin.projects.show', $project) }}">Open</a>
                    <a class="btn gray" href="{{ route('admin.projects.edit', $project) }}">Edit</a>
                    <form class="inline" method="post" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn red">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No projects found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px">{{ $projects->links() }}</div>
</div>
@endsection
