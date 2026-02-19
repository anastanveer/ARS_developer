@extends('admin.layout', ['title' => 'Coupons'])

@section('content')
<div class="top">
    <h1 style="margin:0">Coupons</h1>
    <a class="btn" href="{{ route('admin.coupons.create') }}">Add Coupon</a>
</div>

<div class="card">
    <table>
        <thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Expires</th><th>Active</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($coupons as $coupon)
            <tr>
                <td><strong>{{ $coupon->code }}</strong><br>{{ $coupon->title }}</td>
                <td>{{ strtoupper($coupon->discount_type) }}</td>
                <td>{{ $coupon->discount_type === 'percent' ? $coupon->discount_value.'%' : $coupon->currency.' '.$coupon->discount_value }}</td>
                <td>{{ $coupon->expires_at?->format('d M Y H:i') ?: '-' }}</td>
                <td>{{ $coupon->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <a class="btn" href="{{ route('admin.coupons.edit', $coupon) }}">Edit</a>
                    <form class="inline" method="post" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete this coupon?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn red">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No coupons found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px">{{ $coupons->links() }}</div>
</div>
@endsection
