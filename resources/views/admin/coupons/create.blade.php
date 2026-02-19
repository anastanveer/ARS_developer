@extends('admin.layout', ['title' => 'Add Coupon'])

@section('content')
<div class="top"><h1 style="margin:0">Add Coupon</h1><a href="{{ route('admin.coupons.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.coupons.store') }}" class="row">
        @csrf
        <div><label>Code</label><input name="code" required placeholder="WELCOME10"></div>
        <div><label>Title</label><input name="title"></div>
        <div><label>Discount Type</label><select name="discount_type"><option value="percent">Percent</option><option value="fixed">Fixed</option></select></div>
        <div><label>Discount Value</label><input name="discount_value" type="number" step="0.01" value="10" required></div>
        <div><label>Currency</label><input name="currency" value="GBP"></div>
        <div><label>Usage Limit</label><input name="usage_limit" type="number" min="1"></div>
        <div><label>Expires At</label><input type="datetime-local" name="expires_at"></div>
        <div><label><input type="checkbox" name="is_active" value="1" checked style="width:auto"> Active</label></div>
        <div class="full"><label>Notes</label><textarea name="notes" rows="5"></textarea></div>
        <div class="full"><button class="btn" type="submit">Create Coupon</button></div>
    </form>
</div>
@endsection
