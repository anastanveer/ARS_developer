@extends('admin.layout', ['title' => 'Add Client'])

@section('content')
<div class="top"><h1 style="margin:0">Add Client</h1><a href="{{ route('admin.clients.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.clients.store') }}" class="row">
        @csrf
        <div><label>Name</label><input name="name" value="{{ old('name') }}" required></div>
        <div><label>Company</label><input name="company" value="{{ old('company') }}"></div>
        <div><label>Email</label><input type="email" name="email" value="{{ old('email') }}"></div>
        <div><label>Phone</label><input name="phone" value="{{ old('phone') }}"></div>
        <div><label>Country</label><input name="country" value="{{ old('country') }}"></div>
        <div class="full"><label>Notes</label><textarea name="notes">{{ old('notes') }}</textarea></div>
        <div class="full"><button class="btn" type="submit">Save Client</button></div>
    </form>
</div>
@endsection
