@extends('admin.layout', ['title' => 'Edit Coupon'])

@section('content')
<div class="top"><h1 style="margin:0">Edit Coupon</h1><a href="{{ route('admin.coupons.index') }}" class="btn gray">Back</a></div>
<div class="card">
    <form method="post" action="{{ route('admin.coupons.update', $coupon) }}" class="row">
        @csrf @method('PUT')
        <div><label>Code</label><input name="code" required value="{{ old('code', $coupon->code) }}"></div>
        <div><label>Title</label><input name="title" value="{{ old('title', $coupon->title) }}"></div>
        <div><label>Discount Type</label><select name="discount_type"><option value="percent" @selected(old('discount_type', $coupon->discount_type)==='percent')>Percent</option><option value="fixed" @selected(old('discount_type', $coupon->discount_type)==='fixed')>Fixed</option></select></div>
        <div><label>Discount Value</label><input name="discount_value" type="number" step="0.01" value="{{ old('discount_value', $coupon->discount_value) }}" required></div>
        <div><label>Currency</label><input name="currency" value="{{ old('currency', $coupon->currency) }}"></div>
        <div><label>Usage Limit</label><input name="usage_limit" type="number" min="1" value="{{ old('usage_limit', $coupon->usage_limit) }}"></div>
        <div><label>Expires At</label><input type="datetime-local" name="expires_at" value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d\TH:i')) }}"></div>
        <div><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active)) style="width:auto"> Active</label></div>
        <div class="full"><label>Notes</label><textarea name="notes" rows="5">{{ old('notes', $coupon->notes) }}</textarea></div>
        <div class="full"><button class="btn" type="submit">Save Coupon</button></div>
    </form>
</div>
@endsection
