@extends('admin.layout', ['title' => 'Blocked Contacts'])

@section('content')
<div class="top">
    <h1 style="margin:0">Blocked Contacts</h1>
    <a class="btn" href="{{ route('admin.blocked-contacts.create') }}">Add Rule</a>
</div>

<div class="card" style="margin-bottom:16px">
    <form method="get" class="row">
        <div><label>Search</label><input type="text" name="q" value="{{ request('q') }}" placeholder="Email, IP, reason"></div>
        <div style="align-self:end"><button class="btn" type="submit">Filter</button></div>
    </form>
</div>

<div class="card">
    <table>
        <thead><tr><th>#</th><th>Email</th><th>IP</th><th>Reason</th><th>Active</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($blockedContacts as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->email ?: '-' }}</td>
                <td>{{ $row->ip ?: '-' }}</td>
                <td>{{ $row->reason ?: '-' }}</td>
                <td>{{ $row->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <a class="btn" href="{{ route('admin.blocked-contacts.edit', $row) }}">Edit</a>
                    <form class="inline" method="post" action="{{ route('admin.blocked-contacts.destroy', $row) }}" onsubmit="return confirm('Delete this rule?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn red">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No blocked contacts found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px">{{ $blockedContacts->links() }}</div>
</div>
@endsection
