@extends('admin.layout', ['title' => 'Audit Details'])

@section('content')
@php
    $overall = (int) $audit->overall_score;
    $performance = (int) ($audit->performance_score ?? 0);
    $seo = (int) ($audit->seo_score ?? 0);
    $ux = (int) ($audit->ux_score ?? 0);
    $security = (int) ($audit->security_score ?? 0);
    $grade = $overall >= 90 ? 'A' : ($overall >= 80 ? 'B' : ($overall >= 70 ? 'C' : ($overall >= 60 ? 'D' : 'F')));
    $risk = $overall < 60 ? 'High' : ($overall < 80 ? 'Medium' : 'Low');
    $riskColor = $risk === 'High' ? '#a12828' : ($risk === 'Medium' ? '#a66a00' : '#0d8051');
@endphp
<div class="top">
    <h1 class="page-title">Audit {{ $audit->reference }}</h1>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('admin.audits.pdf', $audit) }}" class="btn alt">Download PDF</a>
        <a href="{{ route('admin.audits.create') }}" class="btn">Create Another</a>
        <a href="{{ route('admin.audits.index') }}" class="btn gray">Back</a>
    </div>
</div>

<div class="grid" style="margin-bottom:14px">
    <div class="stat"><b>Overall</b><span>{{ $overall }}/100</span></div>
    <div class="stat"><b>Grade</b><span>{{ $grade }}</span></div>
    <div class="stat"><b>Risk</b><span style="color:{{ $riskColor }};">{{ $risk }}</span></div>
    <div class="stat"><b>Performance</b><span>{{ $audit->performance_score ?: '-' }}</span></div>
    <div class="stat"><b>SEO</b><span>{{ $audit->seo_score ?: '-' }}</span></div>
    <div class="stat"><b>UX</b><span>{{ $audit->ux_score ?: '-' }}</span></div>
    <div class="stat"><b>Security</b><span>{{ $audit->security_score ?: '-' }}</span></div>
    <div class="stat"><b>Timeline</b><span>{{ $audit->estimated_timeline ?: '-' }}</span></div>
</div>

<div class="card" style="margin-bottom:14px;">
    <h3 style="margin-top:0;">Quality Matrix</h3>
    <div class="grid">
        <div class="preview-box">
            <p style="margin:0 0 6px;"><b>Performance</b> {{ $performance }}/100</p>
            <div style="height:8px;background:#e6eefc;border-radius:999px;overflow:hidden;"><div style="height:100%;width:{{ max(0, min(100, $performance)) }}%;background:#1668ff;"></div></div>
        </div>
        <div class="preview-box">
            <p style="margin:0 0 6px;"><b>SEO</b> {{ $seo }}/100</p>
            <div style="height:8px;background:#e6eefc;border-radius:999px;overflow:hidden;"><div style="height:100%;width:{{ max(0, min(100, $seo)) }}%;background:#16b7a3;"></div></div>
        </div>
        <div class="preview-box">
            <p style="margin:0 0 6px;"><b>UX</b> {{ $ux }}/100</p>
            <div style="height:8px;background:#e6eefc;border-radius:999px;overflow:hidden;"><div style="height:100%;width:{{ max(0, min(100, $ux)) }}%;background:#4d6cfa;"></div></div>
        </div>
        <div class="preview-box">
            <p style="margin:0 0 6px;"><b>Security</b> {{ $security }}/100</p>
            <div style="height:8px;background:#e6eefc;border-radius:999px;overflow:hidden;"><div style="height:100%;width:{{ max(0, min(100, $security)) }}%;background:{{ $security >= 80 ? '#168f5d' : '#da3b52' }};"></div></div>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom:14px">
    <div class="card">
        <h3 style="margin-top:0;">Business Details</h3>
        <p><b>Business:</b> {{ $audit->business_name }}</p>
        <p><b>Website:</b> <a href="{{ $audit->website_url }}" target="_blank" rel="noopener">{{ $audit->website_url }}</a></p>
        <p><b>Recipient:</b> {{ $audit->recipient_name ?: '-' }}</p>
        <p><b>Recipient Email:</b> {{ $audit->recipient_email ?: '-' }}</p>
    </div>
    <div class="card">
        <h3 style="margin-top:0;">Client Share Text</h3>
        <textarea readonly style="min-height:220px;">{{ $shareText }}</textarea>
    </div>
</div>

<div class="card" style="margin-bottom:14px">
    <h3 style="margin-top:0;">Executive Summary</h3>
    <p style="white-space:pre-line;">{{ $audit->summary ?: 'No summary added.' }}</p>
</div>

<div class="row">
    <div class="card">
        <h3 style="margin-top:0;">Strengths</h3>
        <p style="white-space:pre-line;">{{ $audit->strengths ?: 'No strengths section added.' }}</p>
    </div>
    <div class="card">
        <h3 style="margin-top:0;">Priority Issues</h3>
        <p style="white-space:pre-line;">{{ $audit->issues ?: 'No issue section added.' }}</p>
    </div>
</div>

<div class="card" style="margin-top:14px;">
    <h3 style="margin-top:0;">Recommendations</h3>
    <p style="white-space:pre-line;">{{ $audit->recommendations ?: 'No recommendations added.' }}</p>
</div>
@endsection
