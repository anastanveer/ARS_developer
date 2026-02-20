@extends('admin.layout', ['title' => 'Client Reviews'])

@section('content')
<div class="top">
    <h1 style="margin:0">Client Reviews</h1>
</div>

<div class="card" style="margin-bottom:16px">
    <form method="get" class="row">
        <div>
            <label>Search</label>
            <input name="q" value="{{ request('q') }}" placeholder="Name, email, title, text...">
        </div>
        <div>
            <label>Status</label>
            <select name="status">
                <option value="">All</option>
                <option value="approved" @selected(request('status')==='approved')>Approved</option>
                <option value="pending" @selected(request('status')==='pending')>Pending</option>
                <option value="draft" @selected(request('status')==='draft')>Draft (email sent only)</option>
            </select>
        </div>
        <div style="display:flex;align-items:end;">
            <button class="btn" type="submit">Filter</button>
        </div>
    </form>
</div>

<div class="card">
    <table>
        <thead>
        <tr>
            <th>Review</th>
            <th>Project / Invoice</th>
            <th>Rating</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($reviews as $review)
            <tr>
                <td>
                    <strong>{{ $review->review_title ?: 'No title yet' }}</strong><br>
                    <span class="muted">{{ $review->reviewer_name ?: 'N/A' }} · {{ $review->reviewer_email ?: 'N/A' }}</span><br>
                    <span class="muted">{{ \Illuminate\Support\Str::limit((string) $review->review_text, 150) }}</span>
                </td>
                <td>
                    {{ $review->project?->title ?: '-' }}<br>
                    <span class="muted">{{ $review->invoice?->invoice_number ?: '-' }} / {{ $review->invoice?->client_invoice_number ?: '-' }}</span><br>
                    <span class="muted">Token: {{ $review->review_token }}</span>
                </td>
                <td>{{ $review->rating ? $review->rating . '/5' : '-' }}</td>
                <td>
                    @if($review->is_approved)
                        <span class="pill" style="background:#e8fff4;border-color:#bce9d1;color:#0d6f43">Approved</span>
                    @elseif($review->submitted_at)
                        <span class="pill" style="background:#fff8e6;border-color:#f2dfac;color:#835d00">Pending</span>
                    @else
                        <span class="pill">Draft</span>
                    @endif
                    <br><span class="muted">{{ optional($review->submitted_at)->format('d M Y H:i') ?: 'Not submitted' }}</span>
                </td>
                <td>
                    @if(!$review->is_approved && $review->submitted_at)
                        <form method="post" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                            @csrf
                            <button class="btn green" type="submit">Approve</button>
                        </form>
                    @endif
                    @if($review->is_approved)
                        <form method="post" action="{{ route('admin.reviews.unapprove', $review) }}" class="inline">
                            @csrf
                            <button class="btn gray" type="submit">Unapprove</button>
                        </form>
                    @endif
                    <form method="post" action="{{ route('admin.reviews.delete', $review) }}" class="inline" onsubmit="return confirm('Delete this review?')">
                        @csrf
                        <button class="btn red" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No reviews found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:12px">{{ $reviews->links() }}</div>
</div>
@endsection

