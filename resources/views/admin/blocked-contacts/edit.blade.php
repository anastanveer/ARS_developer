@extends('admin.layout', ['title' => 'Edit Blocked Contact'])

@section('content')
<div class="top"><h1 style="margin:0">Edit Block Rule</h1><a href="{{ route('admin.blocked-contacts.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.blocked-contacts.update', $blockedContact) }}" class="row">
        @csrf @method('PUT')
        <div><label>Email</label><input type="email" name="email" value="{{ old('email', $blockedContact->email) }}"></div>
        <div><label>IP</label><input name="ip" value="{{ old('ip', $blockedContact->ip) }}"></div>
        <div class="full"><label>Reason</label><input name="reason" value="{{ old('reason', $blockedContact->reason) }}"></div>
        <div class="full"><label><input type="checkbox" style="width:auto" name="is_active" value="1" @checked(old('is_active', $blockedContact->is_active))> Active rule</label></div>
        <div class="full"><button class="btn" type="submit">Update Rule</button></div>
    </form>
</div>
@endsection
