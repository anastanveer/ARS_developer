@extends('admin.layout', ['title' => 'Create Project'])

@section('content')
<div class="top"><h1 style="margin:0">Create Project</h1><a href="{{ route('admin.projects.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.projects.store') }}" class="row3">
        @csrf
        <div><label>Client</label>
            <select name="client_id" required>
                <option value="">Select client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected(old('client_id')==$client->id)>{{ $client->name }}{{ $client->company ? ' - '.$client->company : '' }}</option>
                @endforeach
            </select>
        </div>
        <div><label>Project Title</label><input name="title" value="{{ old('title') }}" required></div>
        <div><label>Project Type</label><input name="type" value="{{ old('type') }}" placeholder="Website, CRM, Ecommerce"></div>
        <div><label>Status</label>
            <select name="status" required>
                @foreach(['planning','in_progress','on_hold','delivered','closed'] as $status)
                    <option value="{{ $status }}" @selected(old('status','planning')===$status)>{{ str_replace('_',' ', ucfirst($status)) }}</option>
                @endforeach
            </select>
        </div>
        <div><label>Start Date</label><input type="date" name="start_date" value="{{ old('start_date') }}"></div>
        <div><label>Delivery Months</label><input type="number" name="delivery_months" min="1" max="36" value="{{ old('delivery_months',3) }}"></div>
        <div><label>Budget</label><input type="number" step="0.01" name="budget_total" value="{{ old('budget_total') }}" required></div>
        <div><label>Currency</label><input name="currency" value="{{ old('currency','GBP') }}"></div>
        <div><label>Delivery Date (optional override)</label><input type="date" name="delivery_date" value="{{ old('delivery_date') }}"></div>
        <div class="full"><label>Description</label><textarea name="description">{{ old('description') }}</textarea></div>
        <div class="full"><button class="btn" type="submit">Create Project</button></div>
    </form>
</div>
@endsection
