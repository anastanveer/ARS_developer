@extends('admin.layout', ['title' => 'Edit Project'])

@section('content')
<div class="top"><h1 style="margin:0">Edit Project</h1><a href="{{ route('admin.projects.show', $project) }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.projects.update', $project) }}" class="row3">
        @csrf @method('PUT')
        <div><label>Client</label>
            <select name="client_id" required>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected(old('client_id',$project->client_id)==$client->id)>{{ $client->name }}{{ $client->company ? ' - '.$client->company : '' }}</option>
                @endforeach
            </select>
        </div>
        <div><label>Project Title</label><input name="title" value="{{ old('title',$project->title) }}" required></div>
        <div><label>Project Type</label><input name="type" value="{{ old('type',$project->type) }}"></div>
        <div><label>Status</label>
            <select name="status" required>
                @foreach(['planning','in_progress','on_hold','delivered','closed'] as $status)
                    <option value="{{ $status }}" @selected(old('status',$project->status)===$status)>{{ str_replace('_',' ', ucfirst($status)) }}</option>
                @endforeach
            </select>
        </div>
        <div><label>Start Date</label><input type="date" name="start_date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}"></div>
        <div><label>Delivery Months</label><input type="number" name="delivery_months" min="1" max="36" value="{{ old('delivery_months',$project->delivery_months) }}"></div>
        <div><label>Budget</label><input type="number" step="0.01" name="budget_total" value="{{ old('budget_total',$project->budget_total) }}" required></div>
        <div><label>Currency</label><input name="currency" value="{{ old('currency',$project->currency) }}"></div>
        <div><label>Delivery Date</label><input type="date" name="delivery_date" value="{{ old('delivery_date', optional($project->delivery_date)->format('Y-m-d')) }}"></div>
        <div><label><input type="checkbox" style="width:auto" name="recalculate_delivery" value="1"> Recalculate delivery date from start + months</label></div>
        <div class="full"><label>Description</label><textarea name="description">{{ old('description',$project->description) }}</textarea></div>
        <div class="full"><button class="btn" type="submit">Update Project</button></div>
    </form>
</div>
@endsection
