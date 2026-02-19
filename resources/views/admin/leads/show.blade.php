@extends('admin.layout', ['title' => 'Lead Detail'])

@section('content')
<div class="top">
    <h1 style="margin:0">Lead #{{ $lead->id }}</h1>
    <a href="{{ route('admin.leads.index') }}" class="btn gray">Back</a>
</div>

<div class="card" style="margin-bottom:16px">
    <div class="row">
        <div><strong>Name:</strong> {{ $lead->name ?: '-' }}</div>
        <div><strong>Email:</strong> {{ $lead->email }}</div>
        <div><strong>Phone:</strong> {{ $lead->phone ?: '-' }}</div>
        <div><strong>Company:</strong> {{ $lead->company ?: '-' }}</div>
        <div><strong>Country:</strong> {{ $lead->country ?: '-' }}</div>
        <div><strong>IP:</strong> {{ $lead->ip ?: '-' }}</div>
        <div><strong>Type:</strong> {{ strtoupper($lead->type) }}</div>
        <div><strong>Project:</strong> {{ $lead->project_type ?: '-' }}</div>
        <div><strong>Meeting Date:</strong> {{ optional($lead->meeting_date)->format('d M Y') ?: '-' }}</div>
        <div><strong>Meeting Slot:</strong> {{ $lead->meeting_slot ?: '-' }}</div>
        <div><strong>Meeting Timezone:</strong> {{ $lead->meeting_timezone ?: '-' }}</div>
        <div><strong>Meeting Token:</strong> {{ $lead->meeting_token ?: '-' }}</div>
        <div><strong>Coupon Code:</strong> {{ $lead->coupon_code ?: '-' }}</div>
        <div><strong>Coupon Validated:</strong> {{ isset($lead->coupon_validated) ? ($lead->coupon_validated ? 'Yes' : 'No') : '-' }}</div>
        <div><strong>Coupon Discount:</strong> {{ isset($lead->coupon_discount) && $lead->coupon_discount !== null ? 'GBP '.number_format((float) $lead->coupon_discount, 2) : '-' }}</div>
        <div><strong>Final Quote Preview:</strong> {{ isset($lead->quote_final_preview) && $lead->quote_final_preview !== null ? 'GBP '.number_format((float) $lead->quote_final_preview, 2) : '-' }}</div>
        <div class="full"><strong>Subject:</strong> {{ $lead->subject ?: '-' }}</div>
        <div class="full"><strong>Message:</strong><br>{{ $lead->message ?: '-' }}</div>
        <div class="full"><strong>Blocked:</strong> {{ $lead->is_blocked ? 'Yes' : 'No' }} {{ $lead->blocked_reason ? '('.$lead->blocked_reason.')' : '' }}</div>
    </div>
</div>

@if($lead->type === 'meeting' && $lead->meeting_token)
<div class="card" style="margin-bottom:16px">
    <h3 style="margin-top:0">Client Self-Service Links</h3>
    <div class="row">
        <div><a class="btn" href="{{ route('meeting.confirmation', ['token' => $lead->meeting_token]) }}" target="_blank">Confirmation Page</a></div>
        <div><a class="btn gray" href="{{ route('meeting.manage', ['token' => $lead->meeting_token]) }}" target="_blank">Manage Booking</a></div>
        <div><a class="btn red" href="{{ route('meeting.cancel', ['token' => $lead->meeting_token]) }}" target="_blank">Cancel (One Click)</a></div>
    </div>
</div>
@endif

<div class="card" style="margin-bottom:16px">
    <h3 style="margin-top:0">Update Status</h3>
    <form method="post" action="{{ route('admin.leads.status', $lead) }}" class="row">
        @csrf
        <div>
            <select name="status">
                @foreach($lead->statusChoices() as $status)
                    <option value="{{ $status }}" @selected($lead->status===$status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                @endforeach
            </select>
        </div>
        <div><button class="btn green" type="submit">Save Status</button></div>
    </form>
</div>

<div class="card" style="margin-bottom:16px">
    <h3 style="margin-top:0">Spam / Abuse Controls</h3>
    <form method="post" action="{{ route('admin.leads.block', $lead) }}" class="row">
        @csrf
        <div><label><input type="checkbox" style="width:auto" name="block_email" value="1" checked> Block this email</label></div>
        <div><label><input type="checkbox" style="width:auto" name="block_ip" value="1"> Block this IP</label></div>
        <div class="full"><label>Reason</label><input name="reason" placeholder="Spam / abuse / bot request"></div>
        <div class="full"><button class="btn red" type="submit">Block Contact</button></div>
    </form>
</div>

<div class="card" style="margin-bottom:16px">
    <h3 style="margin-top:0">Send Follow-up / Coupon Email</h3>
    <form method="post" action="{{ route('admin.leads.send-email', $lead) }}" class="row">
        @csrf
        <div>
            <label>Email Type</label>
            <select name="kind" required>
                <option value="follow_up">Follow Up</option>
                <option value="coupon">Coupon</option>
                <option value="custom">Custom</option>
            </select>
        </div>
        <div>
            <label>Coupon (Optional)</label>
            <select name="coupon_id">
                <option value="">No Coupon</option>
                @foreach(\App\Models\Coupon::where('is_active', true)->orderBy('code')->get() as $coupon)
                    <option value="{{ $coupon->id }}">{{ $coupon->code }} ({{ $coupon->discount_type === 'percent' ? $coupon->discount_value.'%' : $coupon->currency.' '.$coupon->discount_value }})</option>
                @endforeach
            </select>
        </div>
        <div class="full">
            <label>Subject</label>
            <input type="text" name="subject" required value="Project Follow-up from ARSDeveloper">
        </div>
        <div class="full">
            <label>Message</label>
            <textarea name="body" rows="7" placeholder="Write follow-up details, proposal notes, next steps..."></textarea>
        </div>
        <div class="full"><button class="btn" type="submit">Send Email</button></div>
    </form>
</div>

<div class="card">
    <h3 style="margin-top:0">Email History</h3>
    <table>
        <thead><tr><th>Date</th><th>Kind</th><th>Subject</th><th>Status</th><th>Error</th></tr></thead>
        <tbody>
        @forelse($emailLogs as $log)
            <tr>
                <td>{{ $log->created_at?->format('d M Y H:i') }}</td>
                <td>{{ strtoupper($log->kind) }}</td>
                <td>{{ $log->subject }}</td>
                <td>{{ strtoupper($log->status) }}</td>
                <td>{{ $log->error_message ?: '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No email logs yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
