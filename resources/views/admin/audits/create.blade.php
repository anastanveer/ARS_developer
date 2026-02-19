@extends('admin.layout', ['title' => 'Create Audit'])

@section('content')
<div class="top">
    <h1 class="page-title">Create Deep Audit Report</h1>
    <a href="{{ route('admin.audits.index') }}" class="btn gray">Back to Audit Lab</a>
</div>

<div class="row" style="margin-bottom:14px">
    <div class="card">
        <div class="top">
            <h3 style="margin:0">Live Security Scan</h3>
        </div>
        <p class="muted" style="margin:6px 0 12px;">Run live attack-surface and header check before finalizing audit scores.</p>
        <div class="row3">
            <div class="full">
                <label>Website URL</label>
                <input type="text" id="liveAuditUrl" placeholder="https://example.co.uk">
            </div>
            <div>
                <button type="button" id="runLiveAuditBtn" class="btn alt">Run Live Scan</button>
            </div>
            <div>
                <button type="button" id="runDeepAuditBtn" class="btn">Run Deep Audit (Google + Technical)</button>
            </div>
        </div>
        <div id="liveAuditResult" class="preview-box" style="margin-top:12px;display:none;">
            <p id="liveAuditSummary" style="margin:0 0 8px;font-weight:700;"></p>
            <p id="liveAuditMeta" style="margin:0 0 10px;color:#5f7390;font-size:13px;"></p>
            <ul id="liveAuditChecks" style="margin:0 0 8px;padding-left:18px;"></ul>
            <p style="margin:0;font-size:13px;color:#5f7390;">Security score auto-filled in form when scan completes.</p>
        </div>
        <div id="deepAuditResult" class="preview-box" style="margin-top:12px;display:none;">
            <p id="deepAuditSummary" style="margin:0 0 8px;font-weight:700;"></p>
            <p id="deepAuditMeta" style="margin:0 0 10px;color:#5f7390;font-size:13px;"></p>
            <ul id="deepAuditList" style="margin:0;padding-left:18px;"></ul>
        </div>
    </div>

    <div class="card">
        <div class="top">
            <h3 style="margin:0">Score Guidance</h3>
        </div>
        <div class="preview-box">
            <p style="margin:0 0 8px;"><b>90-100:</b> Enterprise-ready with low-risk attack surface.</p>
            <p style="margin:0 0 8px;"><b>75-89:</b> Good baseline, needs hardening in selected areas.</p>
            <p style="margin:0;"><b>Below 75:</b> Immediate technical and security fixes recommended.</p>
        </div>
    </div>
</div>

<div class="card">
    <form action="{{ route('admin.audits.store') }}" method="post" class="row3">
        @csrf
        <div><label>Business Name</label><input type="text" name="business_name" value="{{ old('business_name') }}" required></div>
        <div><label>Website URL</label><input type="text" name="website_url" value="{{ old('website_url') }}" required></div>
        <div><label>Recipient Name</label><input type="text" name="recipient_name" value="{{ old('recipient_name') }}"></div>
        <div><label>Recipient Email</label><input type="email" name="recipient_email" value="{{ old('recipient_email') }}"></div>
        <div><label>Overall Score</label><input type="number" name="overall_score" min="1" max="100" value="{{ old('overall_score', 82) }}" required></div>
        <div><label>Performance Score</label><input type="number" name="performance_score" min="1" max="100" value="{{ old('performance_score', 80) }}"></div>
        <div><label>SEO Score</label><input type="number" name="seo_score" min="1" max="100" value="{{ old('seo_score', 78) }}"></div>
        <div><label>UX Score</label><input type="number" name="ux_score" min="1" max="100" value="{{ old('ux_score', 81) }}"></div>
        <div><label>Security Score</label><input id="securityScoreInput" type="number" name="security_score" min="1" max="100" value="{{ old('security_score', 79) }}"></div>
        <div><label>Estimated Timeline</label><input type="text" name="estimated_timeline" value="{{ old('estimated_timeline', '2-4 weeks') }}"></div>
        <div class="full"><label>Executive Summary</label><textarea name="summary">{{ old('summary') }}</textarea></div>
        <div class="full"><label>Strengths</label><textarea name="strengths">{{ old('strengths') }}</textarea></div>
        <div class="full"><label>Priority Issues</label><textarea name="issues">{{ old('issues') }}</textarea></div>
        <div class="full"><label>Recommendations</label><textarea name="recommendations">{{ old('recommendations') }}</textarea></div>
        <div class="full" style="display:flex;gap:10px;flex-wrap:wrap;">
            <button class="btn gray" type="button" id="generateDraftBtn">Generate Smart Draft</button>
            <button class="btn" type="submit" name="action" value="save">Save Audit</button>
            <button class="btn alt" type="submit" name="action" value="download">Save & Download PDF</button>
        </div>
    </form>
</div>
@endsection

@push('admin_scripts')
<script>
(function () {
    var urlInput = document.getElementById('liveAuditUrl');
    var runBtn = document.getElementById('runLiveAuditBtn');
    var deepBtn = document.getElementById('runDeepAuditBtn');
    var resultBox = document.getElementById('liveAuditResult');
    var deepResultBox = document.getElementById('deepAuditResult');
    var summaryNode = document.getElementById('liveAuditSummary');
    var deepSummaryNode = document.getElementById('deepAuditSummary');
    var metaNode = document.getElementById('liveAuditMeta');
    var deepMetaNode = document.getElementById('deepAuditMeta');
    var checksNode = document.getElementById('liveAuditChecks');
    var deepListNode = document.getElementById('deepAuditList');
    var securityScoreInput = document.getElementById('securityScoreInput');
    var generateDraftBtn = document.getElementById('generateDraftBtn');
    var overallInput = document.querySelector('input[name="overall_score"]');
    var performanceInput = document.querySelector('input[name="performance_score"]');
    var seoInput = document.querySelector('input[name="seo_score"]');
    var uxInput = document.querySelector('input[name="ux_score"]');
    var timelineInput = document.querySelector('input[name="estimated_timeline"]');
    var summaryInput = document.querySelector('textarea[name="summary"]');
    var strengthsInput = document.querySelector('textarea[name="strengths"]');
    var issuesInput = document.querySelector('textarea[name="issues"]');
    var recommendationsInput = document.querySelector('textarea[name="recommendations"]');
    var businessInput = document.querySelector('input[name="business_name"]');
    var websiteInput = document.querySelector('input[name="website_url"]');
    var lastScanPayload = null;
    if (!urlInput || !runBtn) return;

    function normalizeUrlInput(value) {
        var v = (value || '').trim();
        if (!v) return '';
        if (!/^https?:\/\//i.test(v)) {
            v = 'https://' + v;
        }
        return v;
    }

    function syncScanUrlFromForm() {
        if (!websiteInput) return;
        var lower = (websiteInput.value || '').trim();
        var upper = (urlInput.value || '').trim();
        if (lower && !upper) {
            urlInput.value = lower;
        }
    }

    if (websiteInput) {
        websiteInput.addEventListener('input', function () {
            if (!urlInput.value.trim()) {
                urlInput.value = websiteInput.value;
            }
        });
        websiteInput.addEventListener('blur', function () {
            if (websiteInput.value.trim()) {
                websiteInput.value = normalizeUrlInput(websiteInput.value);
            }
            if (!urlInput.value.trim() && websiteInput.value.trim()) {
                urlInput.value = websiteInput.value;
            }
        });
    }

    urlInput.addEventListener('blur', function () {
        if (urlInput.value.trim()) {
            urlInput.value = normalizeUrlInput(urlInput.value);
        }
        if (websiteInput && !websiteInput.value.trim() && urlInput.value.trim()) {
            websiteInput.value = urlInput.value;
        }
    });

    runBtn.addEventListener('click', function () {
        syncScanUrlFromForm();
        var url = normalizeUrlInput(urlInput.value);
        if (!url) {
            alert('Please enter website URL first.');
            return;
        }
        urlInput.value = url;
        if (websiteInput && !websiteInput.value.trim()) {
            websiteInput.value = url;
        }

        runBtn.disabled = true;
        runBtn.textContent = 'Scanning...';

        fetch('{{ route('admin.audits.live-scan') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ website_url: url })
        })
        .then(function (res) { return res.json(); })
        .then(function (payload) {
            lastScanPayload = payload;
            resultBox.style.display = 'block';
            var score = Number(payload.security_score || 0);
            summaryNode.textContent = 'Security Score: ' + score + '/100 | Risk: ' + (payload.risk_level || 'Unknown') + ' | Grade: ' + (payload.grade || '-');
            if (metaNode) {
                metaNode.textContent = 'Scanned URL: ' + (payload.scanned_url || '-') + ' | Response time: ' + (payload.response_time_ms || '-') + ' ms';
            }
            checksNode.innerHTML = '';

            (payload.checks || []).forEach(function (item) {
                var li = document.createElement('li');
                li.textContent = (item.pass ? 'PASS: ' : 'RISK: ') + item.label;
                li.style.color = item.pass ? '#0d8051' : '#a12828';
                checksNode.appendChild(li);
            });

            (payload.alerts || []).forEach(function (line) {
                var li = document.createElement('li');
                li.textContent = 'Alert: ' + line;
                li.style.color = '#a12828';
                checksNode.appendChild(li);
            });

            if (securityScoreInput && score > 0) {
                securityScoreInput.value = score;
            }
        })
        .catch(function () {
            alert('Live scan failed. Check URL and try again.');
        })
        .finally(function () {
            runBtn.disabled = false;
            runBtn.textContent = 'Run Live Scan';
        });
    });

    if (deepBtn) {
        deepBtn.addEventListener('click', function () {
            syncScanUrlFromForm();
            var url = normalizeUrlInput(urlInput.value);
            if (!url) {
                alert('Please enter website URL first.');
                return;
            }
            urlInput.value = url;
            if (websiteInput && !websiteInput.value.trim()) {
                websiteInput.value = url;
            }

            deepBtn.disabled = true;
            deepBtn.textContent = 'Deep scanning...';

            fetch('{{ route('admin.audits.deep-scan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ website_url: url })
            })
            .then(function (res) { return res.json(); })
            .then(function (payload) {
                if (!payload || payload.ok !== true) {
                    throw new Error('Deep scan failed.');
                }

                var scores = payload.scores || {};
                var securityPayload = payload.security || {};
                var mobile = (payload.pagespeed && payload.pagespeed.mobile) || {};
                var desktop = (payload.pagespeed && payload.pagespeed.desktop) || {};

                deepResultBox.style.display = 'block';
                deepSummaryNode.textContent =
                    'Overall ' + (scores.overall || '-') + '/100 | Grade ' + (payload.grade || '-') + ' | Risk ' + (payload.risk_level || '-');
                deepMetaNode.textContent =
                    'Timeline: ' + (payload.estimated_timeline || '-') +
                    ' | Security response: ' + ((securityPayload.response_time_ms || '-') + ' ms');
                deepListNode.innerHTML = '';

                function addDeepLine(text, color) {
                    var li = document.createElement('li');
                    li.textContent = text;
                    if (color) li.style.color = color;
                    deepListNode.appendChild(li);
                }

                addDeepLine('Performance: ' + (scores.performance || '-') + ' | SEO: ' + (scores.seo || '-') + ' | UX: ' + (scores.ux || '-') + ' | Security: ' + (scores.security || '-'));
                addDeepLine('Mobile (PSI): Perf ' + (((mobile.scores || {}).performance) || '-') + ' | SEO ' + (((mobile.scores || {}).seo) || '-'));
                addDeepLine('Desktop (PSI): Perf ' + (((desktop.scores || {}).performance) || '-') + ' | SEO ' + (((desktop.scores || {}).seo) || '-'));

                if (payload.ssl && payload.ssl.available) {
                    addDeepLine('SSL expires: ' + (payload.ssl.expires_at || '-') + ' (' + (payload.ssl.days_left || '-') + ' days left)');
                } else if (payload.ssl && payload.ssl.error) {
                    addDeepLine('SSL check: ' + payload.ssl.error, '#a66a00');
                }

                (payload.alerts || []).slice(0, 6).forEach(function (line) {
                    addDeepLine('Alert: ' + line, '#a12828');
                });

                if (overallInput && scores.overall) overallInput.value = scores.overall;
                if (performanceInput && scores.performance) performanceInput.value = scores.performance;
                if (seoInput && scores.seo) seoInput.value = scores.seo;
                if (uxInput && scores.ux) uxInput.value = scores.ux;
                if (securityScoreInput && scores.security) securityScoreInput.value = scores.security;
                if (timelineInput && payload.estimated_timeline) timelineInput.value = payload.estimated_timeline;

                if (summaryInput && !summaryInput.value.trim()) {
                    summaryInput.value = 'Deep live audit completed for ' + (payload.scanned_url || url) + '. Overall grade ' + (payload.grade || '-') + ' with ' + (payload.risk_level || '-') + ' risk profile.';
                }
                if (strengthsInput && !strengthsInput.value.trim() && Array.isArray(payload.strengths)) {
                    strengthsInput.value = payload.strengths.join('\n');
                }
                if (issuesInput && !issuesInput.value.trim() && Array.isArray(payload.priority_issues)) {
                    issuesInput.value = payload.priority_issues.join('\n');
                }
                if (recommendationsInput && !recommendationsInput.value.trim() && Array.isArray(payload.recommendations)) {
                    recommendationsInput.value = payload.recommendations.join('\n');
                }

                if (payload.search_console_note) {
                    addDeepLine(payload.search_console_note, '#4a5f82');
                }

                if (securityPayload && Array.isArray(securityPayload.checks)) {
                    lastScanPayload = { checks: securityPayload.checks };
                }
            })
            .catch(function (err) {
                alert((err && err.message) ? err.message : 'Deep scan failed. Try again.');
            })
            .finally(function () {
                deepBtn.disabled = false;
                deepBtn.textContent = 'Run Deep Audit (Google + Technical)';
            });
        });
    }

    function scoreBandText(score) {
        if (score >= 90) return 'enterprise-level baseline';
        if (score >= 80) return 'strong baseline with selective hardening required';
        if (score >= 70) return 'usable baseline but requires medium-priority fixes';
        if (score >= 60) return 'high-risk baseline requiring immediate improvements';
        return 'critical-risk baseline requiring urgent remediation';
    }

    function buildTimeline(overallScore, securityScore) {
        var avg = Math.round((overallScore + securityScore) / 2);
        if (avg >= 90) return '1-2 weeks';
        if (avg >= 80) return '2-4 weeks';
        if (avg >= 70) return '4-6 weeks';
        return '6-8 weeks';
    }

    if (generateDraftBtn) {
        generateDraftBtn.addEventListener('click', function () {
            var overall = Number((overallInput && overallInput.value) || 0);
            var performance = Number((performanceInput && performanceInput.value) || 0);
            var seo = Number((seoInput && seoInput.value) || 0);
            var ux = Number((uxInput && uxInput.value) || 0);
            var security = Number((securityScoreInput && securityScoreInput.value) || 0);
            var businessName = (businessInput && businessInput.value.trim()) || 'this business';
            var websiteUrl = (websiteInput && websiteInput.value.trim()) || 'the website';

            var passChecks = [];
            var failChecks = [];
            (lastScanPayload && lastScanPayload.checks ? lastScanPayload.checks : []).forEach(function (item) {
                if (item.pass) passChecks.push(item.label);
                else failChecks.push(item.label);
            });

            if (summaryInput && !summaryInput.value.trim()) {
                summaryInput.value =
                    businessName + ' (' + websiteUrl + ') currently shows a ' + scoreBandText(overall) +
                    '. Priority should be conversion-safe performance hardening, technical SEO clarity, and security headers standardization to reduce risk and improve lead trust.';
            }

            if (strengthsInput && !strengthsInput.value.trim()) {
                var strengths = [];
                if (performance >= 80) strengths.push('Good baseline performance score (' + performance + '/100).');
                if (seo >= 80) strengths.push('Strong SEO foundation (' + seo + '/100).');
                if (ux >= 80) strengths.push('Clear UX quality baseline (' + ux + '/100).');
                if (passChecks.length > 0) strengths.push('Live scan passed: ' + passChecks.slice(0, 4).join(', ') + '.');
                strengthsInput.value = strengths.length ? strengths.join('\n') : 'Core structure exists and can be scaled with targeted improvements.';
            }

            if (issuesInput && !issuesInput.value.trim()) {
                var issueLines = [];
                if (failChecks.length > 0) issueLines.push('Live scan risks: ' + failChecks.join(', ') + '.');
                if (security < 75) issueLines.push('Security score below recommended threshold (' + security + '/100).');
                if (seo < 75) issueLines.push('SEO score indicates crawl/indexation or structure weaknesses.');
                if (ux < 75) issueLines.push('UX friction likely impacts trust and conversion flow.');
                issuesInput.value = issueLines.length ? issueLines.join('\n') : 'No major issue block added.';
            }

            if (recommendationsInput && !recommendationsInput.value.trim()) {
                recommendationsInput.value = [
                    '1) Fix critical security headers and cookie attributes first (CSP, HSTS, XFO, nosniff, SameSite/HttpOnly/Secure).',
                    '2) Improve loading path for above-the-fold content and reduce heavy render blocking assets.',
                    '3) Re-structure key pages for UK intent SEO (service pages, schema, metadata consistency).',
                    '4) Simplify CTA journey and forms to reduce decision friction and improve lead conversion.',
                    '5) Re-run security and performance validation after deployment and monitor monthly.'
                ].join('\n');
            }

            if (timelineInput && !timelineInput.value.trim()) {
                timelineInput.value = buildTimeline(overall, security);
            }
        });
    }
})();
</script>
@endpush
