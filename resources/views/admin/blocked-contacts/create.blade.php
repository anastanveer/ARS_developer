@extends('admin.layout', ['title' => 'Add Blocked Contact'])

@section('content')
<div class="top"><h1 style="margin:0">Add Block Rule</h1><a href="{{ route('admin.blocked-contacts.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.blocked-contacts.store') }}" class="row">
        @csrf
        <div><label>Email</label><input type="email" name="email" value="{{ old('email') }}"></div>
        <div><label>IP</label><input name="ip" value="{{ old('ip') }}"></div>
        <div class="full"><label>Reason</label><input name="reason" value="{{ old('reason') }}"></div>
        <div class="full"><label><input type="checkbox" style="width:auto" name="is_active" value="1" checked> Active rule</label></div>
        <div class="full"><button class="btn" type="submit">Save Rule</button></div>
    </form>
</div>
@endsection
