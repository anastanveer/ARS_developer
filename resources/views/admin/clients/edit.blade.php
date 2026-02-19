@extends('admin.layout', ['title' => 'Edit Client'])

@section('content')
<div class="top"><h1 style="margin:0">Edit Client</h1><a href="{{ route('admin.clients.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.clients.update', $client) }}" class="row">
        @csrf @method('PUT')
        <div><label>Name</label><input name="name" value="{{ old('name', $client->name) }}" required></div>
        <div><label>Company</label><input name="company" value="{{ old('company', $client->company) }}"></div>
        <div><label>Email</label><input type="email" name="email" value="{{ old('email', $client->email) }}"></div>
        <div><label>Phone</label><input name="phone" value="{{ old('phone', $client->phone) }}"></div>
        <div><label>Country</label><input name="country" value="{{ old('country', $client->country) }}"></div>
        <div class="full"><label>Notes</label><textarea name="notes">{{ old('notes', $client->notes) }}</textarea></div>
        <div class="full"><button class="btn" type="submit">Update Client</button></div>
    </form>
</div>
@endsection
