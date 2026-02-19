@extends('admin.layout', ['title' => 'Clients'])

@section('content')
<div class="top">
    <h1 style="margin:0">Clients</h1>
    <a class="btn" href="{{ route('admin.clients.create') }}">Add Client</a>
</div>

<div class="card" style="margin-bottom:16px">
    <form method="get" class="row">
        <div><label>Search</label><input type="text" name="q" value="{{ request('q') }}" placeholder="Name, email, company"></div>
        <div style="align-self:end"><button class="btn" type="submit">Filter</button></div>
    </form>
</div>

<div class="card">
    <table>
        <thead><tr><th>#</th><th>Client</th><th>Contact</th><th>Country</th><th>Projects</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td><strong>{{ $client->name }}</strong><br><span class="muted">{{ $client->company ?: '-' }}</span></td>
                <td>{{ $client->email ?: '-' }}<br>{{ $client->phone ?: '-' }}</td>
                <td>{{ $client->country ?: '-' }}</td>
                <td>{{ $client->projects_count }}</td>
                <td>
                    <a class="btn" href="{{ route('admin.clients.edit', $client) }}">Edit</a>
                    <form class="inline" method="post" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('Delete this client?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn red">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No clients found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px">{{ $clients->links() }}</div>
</div>
@endsection
