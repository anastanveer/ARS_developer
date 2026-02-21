<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Client Review - {{ $review->project?->title ?: 'ARSDeveloper' }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="{{ route('review.show', ['token' => $review->review_token]) }}">
    <style>
        body{margin:0;background:#eef4ff;font-family:"DM Sans",Arial,sans-serif;color:#11284a}
        .wrap{max-width:860px;margin:0 auto;padding:26px 16px}
        .card{background:#fff;border:1px solid #dce7fa;border-radius:16px;padding:20px;box-shadow:0 12px 28px rgba(15,44,90,.08)}
        h1{margin:0 0 8px;font-size:30px}
        .muted{color:#607797}
        .flash{padding:10px 12px;border-radius:10px;margin-bottom:12px}
        .ok{background:#e8fff4;color:#0c6f43;border:1px solid #b7eed2}
        .er{background:#ffecec;color:#9d1d1d;border:1px solid #ffd0d0}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
        label{display:block;font-size:13px;font-weight:700;margin:4px 0}
        input,textarea,select{width:100%;padding:10px;border:1px solid #ccd8ef;border-radius:10px;font:14px "DM Sans",Arial,sans-serif}
        textarea{min-height:120px;resize:vertical}
        .full{grid-column:1/-1}
        .btn{border:0;border-radius:11px;background:#1e79d6;color:#fff;font-weight:700;padding:12px 16px;cursor:pointer}
        .stars{display:flex;gap:8px;flex-wrap:wrap}
        .stars label{font-size:24px;cursor:pointer;line-height:1}
        .review-lock{padding:14px;border:1px solid #d7e4fb;border-radius:12px;background:#f7fbff}
        @media (max-width:700px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="wrap">
    @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="flash er">{{ $errors->first() }}</div>@endif
    <div class="card">
        <h1>Share Your Review</h1>
        <p class="muted" style="margin-top:0;">
            Project: <strong>{{ $review->project?->title ?: 'N/A' }}</strong> |
            Invoice: <strong>{{ $review->invoice?->invoice_number ?: 'N/A' }}</strong>
        </p>

        @if($review->submitted_at)
            <div class="review-lock">
                Your review was submitted on {{ optional($review->submitted_at)->format('d M Y H:i') }} and is pending admin approval.
            </div>
        @else
            <form method="post" action="{{ route('review.submit', $review->review_token) }}" class="grid">
                @csrf
                <div>
                    <label>Your Name</label>
                    <input name="reviewer_name" value="{{ old('reviewer_name', $review->reviewer_name) }}" required>
                </div>
                <div>
                    <label>Business Email</label>
                    <input type="email" name="reviewer_email" value="{{ old('reviewer_email', $review->reviewer_email) }}" required>
                </div>
                <div class="full">
                    <label>Company Name</label>
                    <input name="company_name" value="{{ old('company_name', $review->company_name) }}">
                </div>
                <div class="full">
                    <label>Star Rating</label>
                    <select name="rating" required>
                        <option value="">Select rating</option>
                        @for($i=5; $i>=1; $i--)
                            <option value="{{ $i }}" @selected((int) old('rating') === $i)>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div class="full">
                    <label>Review Title</label>
                    <input name="review_title" value="{{ old('review_title') }}" required>
                </div>
                <div class="full">
                    <label>Review Details</label>
                    <textarea name="review_text" required>{{ old('review_text') }}</textarea>
                </div>
                <div class="full">
                    <label>Result Summary (optional)</label>
                    <input name="result_summary" value="{{ old('result_summary') }}" placeholder="Example: Better conversion and faster response time.">
                </div>
                <div class="full">
                    <button class="btn" type="submit">Submit Review</button>
                </div>
            </form>
        @endif
    </div>
</div>
</body>
</html>
