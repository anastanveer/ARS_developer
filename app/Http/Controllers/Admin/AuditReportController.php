<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditActionItem;
use App\Models\AuditReport;
use App\Models\AuditScanRun;
use App\Models\AuditTarget;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuditReportController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $risk = trim((string) $request->query('risk', ''));
        $grade = trim((string) $request->query('grade', ''));

        $query = AuditReport::query()->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', '%' . $search . '%')
                    ->orWhere('business_name', 'like', '%' . $search . '%')
                    ->orWhere('website_url', 'like', '%' . $search . '%')
                    ->orWhere('recipient_name', 'like', '%' . $search . '%')
                    ->orWhere('recipient_email', 'like', '%' . $search . '%');
            });
        }

        if ($risk !== '') {
            if ($risk === 'high') {
                $query->where('overall_score', '<', 60);
            } elseif ($risk === 'medium') {
                $query->whereBetween('overall_score', [60, 79]);
            } elseif ($risk === 'low') {
                $query->where('overall_score', '>=', 80);
            }
        }

        if ($grade !== '') {
            if ($grade === 'A') {
                $query->where('overall_score', '>=', 90);
            } elseif ($grade === 'B') {
                $query->whereBetween('overall_score', [80, 89]);
            } elseif ($grade === 'C') {
                $query->whereBetween('overall_score', [70, 79]);
            } elseif ($grade === 'D') {
                $query->whereBetween('overall_score', [60, 69]);
            } elseif ($grade === 'F') {
                $query->where('overall_score', '<', 60);
            }
        }

        $audits = $query->paginate(20)->withQueryString();

        $stats = [
            'total_reports' => AuditReport::query()->count(),
            'this_month' => AuditReport::query()->where('created_at', '>=', now()->startOfMonth())->count(),
            'avg_score' => (float) (AuditReport::query()->avg('overall_score') ?: 0),
            'high_risk_count' => AuditReport::query()->where('overall_score', '<', 60)->count(),
            'enterprise_count' => AuditReport::query()->where('overall_score', '>=', 90)->count(),
            'avg_security' => (float) (AuditReport::query()->avg('security_score') ?: 0),
        ];

        $benchmarks = [
            'A' => AuditReport::query()->where('overall_score', '>=', 90)->count(),
            'B' => AuditReport::query()->whereBetween('overall_score', [80, 89])->count(),
            'C' => AuditReport::query()->whereBetween('overall_score', [70, 79])->count(),
            'D' => AuditReport::query()->whereBetween('overall_score', [60, 69])->count(),
            'F' => AuditReport::query()->where('overall_score', '<', 60)->count(),
        ];
        $recentRuns = AuditScanRun::query()
            ->orderByDesc('scanned_at')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $targets = AuditTarget::query()
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        $openActions = AuditActionItem::query()
            ->whereIn('status', ['open', 'in_progress'])
            ->orderByRaw("CASE severity WHEN 'critical' THEN 0 WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        $trend = AuditScanRun::query()
            ->whereNotNull('scanned_at')
            ->orderByDesc('scanned_at')
            ->limit(14)
            ->get()
            ->sortBy('scanned_at')
            ->values()
            ->map(fn (AuditScanRun $run) => [
                'label' => optional($run->scanned_at)->format('d M'),
                'overall' => (int) $run->overall_score,
            ]);

        return view('admin.audits.index', compact(
            'audits',
            'stats',
            'benchmarks',
            'search',
            'risk',
            'grade',
            'recentRuns',
            'targets',
            'openActions',
            'trend'
        ));
    }

    public function create(): View
    {
        return view('admin.audits.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:180'],
            'website_url' => ['required', 'string', 'max:255'],
            'recipient_name' => ['nullable', 'string', 'max:180'],
            'recipient_email' => ['nullable', 'email', 'max:180'],
            'overall_score' => ['required', 'integer', 'min:1', 'max:100'],
            'performance_score' => ['nullable', 'integer', 'min:1', 'max:100'],
            'seo_score' => ['nullable', 'integer', 'min:1', 'max:100'],
            'ux_score' => ['nullable', 'integer', 'min:1', 'max:100'],
            'security_score' => ['nullable', 'integer', 'min:1', 'max:100'],
            'summary' => ['nullable', 'string', 'max:2500'],
            'strengths' => ['nullable', 'string', 'max:2500'],
            'issues' => ['nullable', 'string', 'max:2500'],
            'recommendations' => ['nullable', 'string', 'max:9000'],
            'estimated_timeline' => ['nullable', 'string', 'max:120'],
        ]);

        $website = trim((string) $data['website_url']);
        if (!preg_match('~^https?://~i', $website)) {
            $website = 'https://' . $website;
        }

        $audit = AuditReport::query()->create([
            ...$data,
            'website_url' => $website,
            'reference' => $this->buildReference(),
            'created_by_admin_user_id' => (int) $request->session()->get('admin_user_id', 0) ?: null,
        ]);

        if ((string) $request->input('action') === 'download') {
            return redirect()->route('admin.audits.pdf', $audit)->with('success', 'Audit saved. PDF download is ready.');
        }

        return redirect()->route('admin.audits.show', $audit)->with('success', 'Audit report created successfully.');
    }

    public function show(AuditReport $audit): View
    {
        $shareText = $this->buildShareMessage($audit);

        return view('admin.audits.show', compact('audit', 'shareText'));
    }

    public function downloadPdf(AuditReport $audit): Response
    {
        $lines = $this->buildPdfLines($audit);
        $binary = $this->buildPdfBinary($lines);
        $namePart = Str::slug($audit->business_name ?: 'audit-report');
        $fileName = ($audit->reference ?: 'audit-report') . '-' . $namePart . '.pdf';

        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length' => (string) strlen($binary),
        ]);
    }

    public function liveScan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'website_url' => ['required', 'string', 'max:255'],
        ]);

        $url = $this->normalizeUrl((string) $data['website_url']);
        $security = $this->buildSecuritySnapshot($url);

        return response()->json([
            'ok' => true,
            'scanned_url' => $url,
            'security_score' => $security['security_score'],
            'risk_level' => $security['risk_level'],
            'grade' => $security['grade'],
            'response_time_ms' => $security['response_time_ms'],
            'checks' => $security['checks'],
            'alerts' => $security['alerts'],
        ]);
    }

    public function deepScan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'website_url' => ['required', 'string', 'max:255'],
        ]);

        $url = $this->normalizeUrl((string) $data['website_url']);
        $analysis = $this->analyzeUrl($url);

        if ($request->boolean('save_run', true)) {
            $this->persistScanRun($analysis, (int) $request->session()->get('admin_user_id', 0), null, null);
        }

        return response()->json($analysis);
    }

    public function benchmark(Request $request): JsonResponse
    {
        $data = $request->validate([
            'primary_url' => ['required', 'string', 'max:255'],
            'competitor_urls' => ['nullable', 'array', 'max:3'],
            'competitor_urls.*' => ['nullable', 'string', 'max:255'],
        ]);

        $urls = [trim((string) $data['primary_url'])];
        foreach ((array) ($data['competitor_urls'] ?? []) as $candidate) {
            $candidate = trim((string) $candidate);
            if ($candidate !== '') {
                $urls[] = $candidate;
            }
        }

        $urls = array_values(array_unique(array_map([$this, 'normalizeUrl'], $urls)));
        $rows = [];

        foreach ($urls as $index => $url) {
            $analysis = $this->analyzeUrl($url);
            $rows[] = [
                'position' => 0,
                'label' => $index === 0 ? 'Your Website' : ('Competitor ' . $index),
                'website_url' => $url,
                'overall' => (int) ($analysis['scores']['overall'] ?? 0),
                'performance' => (int) ($analysis['scores']['performance'] ?? 0),
                'seo' => (int) ($analysis['scores']['seo'] ?? 0),
                'ux' => (int) ($analysis['scores']['ux'] ?? 0),
                'security' => (int) ($analysis['scores']['security'] ?? 0),
                'grade' => (string) ($analysis['grade'] ?? '-'),
                'risk' => (string) ($analysis['risk_level'] ?? '-'),
            ];
        }

        usort($rows, static fn ($a, $b) => ($b['overall'] <=> $a['overall']));
        foreach ($rows as $idx => $row) {
            $rows[$idx]['position'] = $idx + 1;
        }

        return response()->json([
            'ok' => true,
            'rows' => $rows,
            'summary' => 'Benchmark generated for ' . count($rows) . ' websites.',
        ]);
    }

    public function storeTarget(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'business_name' => ['nullable', 'string', 'max:180'],
            'website_url' => ['required', 'string', 'max:255'],
            'frequency' => ['required', 'in:weekly,monthly'],
            'status' => ['nullable', 'in:active,paused'],
        ]);

        $url = $this->normalizeUrl((string) $data['website_url']);
        $frequency = (string) $data['frequency'];
        $status = (string) ($data['status'] ?? 'active');

        AuditTarget::query()->updateOrCreate(
            ['website_url' => $url],
            [
                'business_name' => trim((string) ($data['business_name'] ?? '')),
                'frequency' => $frequency,
                'status' => $status,
                'next_run_at' => $status === 'active'
                    ? ($frequency === 'monthly' ? now()->addMonth() : now()->addWeek())
                    : null,
                'created_by_admin_user_id' => (int) $request->session()->get('admin_user_id', 0) ?: null,
            ]
        );

        return back()->with('success', 'Recurring target saved successfully.');
    }

    public function runTargetNow(AuditTarget $target, Request $request): RedirectResponse
    {
        $analysis = $this->analyzeUrl((string) $target->website_url);
        $this->persistScanRun(
            $analysis,
            (int) $request->session()->get('admin_user_id', 0),
            $target->id,
            $target->business_name
        );

        $target->last_run_at = now();
        $target->next_run_at = $target->status === 'active'
            ? ($target->frequency === 'monthly' ? now()->addMonth() : now()->addWeek())
            : null;
        $target->save();

        return back()->with('success', 'Target scanned and history updated.');
    }

    public function updateActionStatus(Request $request, AuditActionItem $action): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,done'],
        ]);

        $action->status = (string) $data['status'];
        $action->save();

        return back()->with('success', 'Action status updated.');
    }

    public function trendData(Request $request): JsonResponse
    {
        $website = trim((string) $request->query('website_url', ''));
        $query = AuditScanRun::query()->whereNotNull('scanned_at')->orderBy('scanned_at');
        if ($website !== '') {
            $query->where('website_url', $this->normalizeUrl($website));
        }

        $runs = $query->limit(40)->get();

        return response()->json([
            'ok' => true,
            'labels' => $runs->map(fn ($r) => optional($r->scanned_at)->format('d M H:i'))->values(),
            'overall' => $runs->pluck('overall_score')->map(fn ($v) => (int) $v)->values(),
            'performance' => $runs->pluck('performance_score')->map(fn ($v) => (int) $v)->values(),
            'seo' => $runs->pluck('seo_score')->map(fn ($v) => (int) $v)->values(),
            'ux' => $runs->pluck('ux_score')->map(fn ($v) => (int) $v)->values(),
            'security' => $runs->pluck('security_score')->map(fn ($v) => (int) $v)->values(),
        ]);
    }

    private function scoreToGrade(int $score): string
    {
        if ($score >= 90) {
            return 'A';
        }
        if ($score >= 80) {
            return 'B';
        }
        if ($score >= 70) {
            return 'C';
        }
        if ($score >= 60) {
            return 'D';
        }

        return 'F';
    }

    private function analyzeUrl(string $url): array
    {
        $security = $this->buildSecuritySnapshot($url);
        $mobilePageSpeed = $this->fetchPageSpeedSnapshot($url, 'mobile');
        $desktopPageSpeed = $this->fetchPageSpeedSnapshot($url, 'desktop');
        $technicalSeo = $this->runTechnicalSeoChecks($url);
        $ssl = $this->inspectSslCertificate($url);

        $performanceRaw = $this->averageScore([
            $mobilePageSpeed['scores']['performance'] ?? null,
            $desktopPageSpeed['scores']['performance'] ?? null,
        ]);

        if ($performanceRaw === null) {
            $responseMs = (int) ($security['response_time_ms'] ?? 0);
            $performanceRaw = $responseMs > 0 && $responseMs <= 900 ? 82 : ($responseMs <= 1500 ? 72 : 58);
        }

        $seoPagespeed = $this->averageScore([
            $mobilePageSpeed['scores']['seo'] ?? null,
            $desktopPageSpeed['scores']['seo'] ?? null,
        ]);
        $seoRaw = $this->averageScore([$seoPagespeed, $technicalSeo['score'] ?? null]);
        if ($seoRaw === null) {
            $seoRaw = (float) ($technicalSeo['score'] ?? 65);
        }

        $uxRaw = $this->averageScore([
            $mobilePageSpeed['scores']['accessibility'] ?? null,
            $desktopPageSpeed['scores']['accessibility'] ?? null,
            $mobilePageSpeed['scores']['best_practices'] ?? null,
            $desktopPageSpeed['scores']['best_practices'] ?? null,
        ]);
        if ($uxRaw === null) {
            $uxRaw = 70;
        }

        $performanceScore = max(1, min(100, (int) round($performanceRaw)));
        $seoScore = max(1, min(100, (int) round($seoRaw)));
        $uxScore = max(1, min(100, (int) round($uxRaw)));
        $securityScore = max(1, min(100, (int) ($security['security_score'] ?? 1)));

        $overallScore = max(1, min(100, (int) round(
            ($performanceScore * 0.34) +
            ($seoScore * 0.26) +
            ($uxScore * 0.20) +
            ($securityScore * 0.20)
        )));

        $riskLevel = $overallScore < 60 ? 'High' : ($overallScore < 80 ? 'Medium' : 'Low');
        $grade = $this->scoreToGrade($overallScore);
        $timeline = $this->estimateTimeline($overallScore, $securityScore);

        $alerts = array_merge($security['alerts'] ?? [], $technicalSeo['alerts'] ?? []);
        if (!$mobilePageSpeed['ok'] && !empty($mobilePageSpeed['error'])) {
            $alerts[] = 'Google PageSpeed mobile scan: ' . $mobilePageSpeed['error'];
        }
        if (!$desktopPageSpeed['ok'] && !empty($desktopPageSpeed['error'])) {
            $alerts[] = 'Google PageSpeed desktop scan: ' . $desktopPageSpeed['error'];
        }
        if (($ssl['available'] ?? false) && isset($ssl['days_left']) && (int) $ssl['days_left'] < 21) {
            $alerts[] = 'SSL certificate expires soon (' . $ssl['days_left'] . ' days left).';
        }

        $strengths = [];
        if ($performanceScore >= 85) {
            $strengths[] = 'Strong performance baseline detected.';
        }
        if ($seoScore >= 85) {
            $strengths[] = 'SEO signal quality is in healthy range.';
        }
        if ($securityScore >= 85) {
            $strengths[] = 'Security headers and transport posture are strong.';
        }
        if (($technicalSeo['flags']['has_robots'] ?? false) && ($technicalSeo['flags']['has_sitemap'] ?? false)) {
            $strengths[] = 'Technical SEO foundation includes robots.txt and sitemap.xml.';
        }
        if ($strengths === []) {
            $strengths[] = 'Core website baseline exists and can be upgraded with targeted execution.';
        }

        $priorityIssues = [];
        if ($performanceScore < 80) {
            $priorityIssues[] = 'Performance score below 80 impacts conversion and paid traffic ROI.';
        }
        if ($seoScore < 80) {
            $priorityIssues[] = 'SEO readiness is below competitive benchmark for UK search intent.';
        }
        if ($securityScore < 80) {
            $priorityIssues[] = 'Security posture below trusted business threshold.';
        }
        if (($technicalSeo['flags']['has_noindex'] ?? false)) {
            $priorityIssues[] = 'Noindex signal detected and may block visibility.';
        }
        if (($ssl['available'] ?? false) && isset($ssl['days_left']) && (int) $ssl['days_left'] < 30) {
            $priorityIssues[] = 'SSL certificate renewal window is close.';
        }

        $recommendations = [
            'Execute high-priority fixes in first sprint: security headers, cookie hardening, and mixed content removal.',
            'Improve loading sequence for above-the-fold content and reduce JS/CSS blocking assets.',
            'Align metadata, canonical, H1 structure, robots, and sitemap for technical SEO stability.',
            'Run post-fix validation after deployment and track monthly trend in Audit Lab.',
        ];
        if ($overallScore < 70) {
            $recommendations[] = 'Adopt a 6-8 week stabilization plan before heavy paid acquisition campaigns.';
        }

        return [
            'ok' => true,
            'scanned_url' => $url,
            'scores' => [
                'overall' => $overallScore,
                'performance' => $performanceScore,
                'seo' => $seoScore,
                'ux' => $uxScore,
                'security' => $securityScore,
            ],
            'grade' => $grade,
            'risk_level' => $riskLevel,
            'estimated_timeline' => $timeline,
            'pagespeed' => [
                'mobile' => $mobilePageSpeed,
                'desktop' => $desktopPageSpeed,
            ],
            'security' => $security,
            'technical_seo' => $technicalSeo,
            'ssl' => $ssl,
            'alerts' => array_values(array_unique($alerts)),
            'strengths' => $strengths,
            'priority_issues' => $priorityIssues,
            'recommendations' => $recommendations,
            'search_console_note' => 'Google Search Console property data requires verified ownership. This module includes public Google PageSpeed + live technical checks without leaving admin panel.',
        ];
    }

    private function persistScanRun(array $analysis, int $adminId, ?int $targetId = null, ?string $businessName = null): AuditScanRun
    {
        $run = AuditScanRun::query()->create([
            'audit_target_id' => $targetId,
            'business_name' => $businessName,
            'website_url' => (string) ($analysis['scanned_url'] ?? ''),
            'overall_score' => (int) ($analysis['scores']['overall'] ?? 0),
            'performance_score' => (int) ($analysis['scores']['performance'] ?? 0),
            'seo_score' => (int) ($analysis['scores']['seo'] ?? 0),
            'ux_score' => (int) ($analysis['scores']['ux'] ?? 0),
            'security_score' => (int) ($analysis['scores']['security'] ?? 0),
            'grade' => (string) ($analysis['grade'] ?? ''),
            'risk_level' => (string) ($analysis['risk_level'] ?? ''),
            'response_time_ms' => (int) ($analysis['security']['response_time_ms'] ?? 0),
            'findings_json' => $analysis,
            'scanned_at' => now(),
            'created_by_admin_user_id' => $adminId > 0 ? $adminId : null,
        ]);

        foreach ((array) ($analysis['priority_issues'] ?? []) as $issue) {
            $text = trim((string) $issue);
            if ($text === '') {
                continue;
            }

            $severity = str_contains(strtolower($text), 'security') ? 'critical' : 'high';
            AuditActionItem::query()->create([
                'audit_scan_run_id' => $run->id,
                'title' => Str::limit($text, 180),
                'details' => $text,
                'severity' => $severity,
                'status' => 'open',
                'due_date' => now()->addDays($severity === 'critical' ? 5 : 10)->toDateString(),
                'created_by_admin_user_id' => $adminId > 0 ? $adminId : null,
            ]);
        }

        return $run;
    }

    private function normalizeUrl(string $websiteUrl): string
    {
        $url = trim($websiteUrl);
        if (!preg_match('~^https?://~i', $url)) {
            $url = 'https://' . $url;
        }

        return $url;
    }

    private function buildSecuritySnapshot(string $url): array
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 8,
                'ignore_errors' => true,
                'header' => "User-Agent: ARS-Audit-Lab/1.0\r\n",
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $scanStart = microtime(true);
        $headersRaw = @get_headers($url, true, $context);
        $html = @file_get_contents($url, false, $context);
        $responseMs = (int) round((microtime(true) - $scanStart) * 1000);

        $headers = [];
        if (is_array($headersRaw)) {
            foreach ($headersRaw as $key => $value) {
                if (is_int($key)) {
                    continue;
                }
                $headers[strtolower((string) $key)] = is_array($value) ? trim((string) end($value)) : trim((string) $value);
            }
        }

        $scheme = (string) parse_url($url, PHP_URL_SCHEME);
        $isHttps = strtolower($scheme) === 'https';
        $hasCsp = isset($headers['content-security-policy']) && $headers['content-security-policy'] !== '';
        $hasHsts = isset($headers['strict-transport-security']) && $headers['strict-transport-security'] !== '';
        $hasXfo = isset($headers['x-frame-options']) && $headers['x-frame-options'] !== '';
        $hasNosniff = isset($headers['x-content-type-options']) && stripos((string) $headers['x-content-type-options'], 'nosniff') !== false;
        $hasReferrer = isset($headers['referrer-policy']) && $headers['referrer-policy'] !== '';
        $serverHeader = (string) ($headers['server'] ?? '');

        $htmlText = is_string($html) ? $html : '';
        $hasMixedContentRisk = stripos($htmlText, 'src="http://') !== false
            || stripos($htmlText, 'src=\'http://') !== false
            || stripos($htmlText, 'href="http://') !== false
            || stripos($htmlText, "href='http://") !== false;
        $hasForm = stripos($htmlText, '<form') !== false;
        $hasCsrfHint = stripos($htmlText, 'csrf') !== false || stripos($htmlText, '_token') !== false;
        $cookiesHeader = strtolower((string) ($headers['set-cookie'] ?? ''));
        $secureCookie = $cookiesHeader === '' || stripos($cookiesHeader, 'secure') !== false;
        $httpOnlyCookie = $cookiesHeader === '' || stripos($cookiesHeader, 'httponly') !== false;
        $samesiteCookie = $cookiesHeader === '' || stripos($cookiesHeader, 'samesite') !== false;
        $isSlow = $responseMs > 1200;

        $score = 100;
        $deductions = [];

        if (!$isHttps) {
            $score -= 20;
            $deductions[] = 'Website is not loaded on HTTPS.';
        }
        if (!$hasHsts) {
            $score -= 12;
            $deductions[] = 'HSTS header missing.';
        }
        if (!$hasCsp) {
            $score -= 12;
            $deductions[] = 'Content-Security-Policy header missing.';
        }
        if (!$hasXfo) {
            $score -= 8;
            $deductions[] = 'X-Frame-Options header missing.';
        }
        if (!$hasNosniff) {
            $score -= 8;
            $deductions[] = 'X-Content-Type-Options nosniff missing.';
        }
        if (!$hasReferrer) {
            $score -= 5;
            $deductions[] = 'Referrer-Policy header missing.';
        }
        if ($serverHeader !== '') {
            $score -= 5;
            $deductions[] = 'Server signature exposed: ' . $serverHeader;
        }
        if ($hasMixedContentRisk) {
            $score -= 10;
            $deductions[] = 'Mixed content URLs detected (http resources on page).';
        }
        if ($hasForm && !$hasCsrfHint) {
            $score -= 6;
            $deductions[] = 'Form found with no visible CSRF hint in markup.';
        }
        if (!$secureCookie) {
            $score -= 6;
            $deductions[] = 'Secure cookie attribute missing.';
        }
        if (!$httpOnlyCookie) {
            $score -= 6;
            $deductions[] = 'HttpOnly cookie attribute missing.';
        }
        if (!$samesiteCookie) {
            $score -= 4;
            $deductions[] = 'SameSite cookie attribute missing.';
        }
        if ($isSlow) {
            $score -= 5;
            $deductions[] = 'Slow response time detected (' . $responseMs . ' ms).';
        }

        $score = max(0, min(100, $score));
        $risk = $score < 60 ? 'High' : ($score < 80 ? 'Medium' : 'Low');

        return [
            'security_score' => $score,
            'risk_level' => $risk,
            'grade' => $this->scoreToGrade($score),
            'response_time_ms' => $responseMs,
            'checks' => [
                ['label' => 'HTTPS Enabled', 'pass' => $isHttps],
                ['label' => 'HSTS Header', 'pass' => $hasHsts],
                ['label' => 'CSP Header', 'pass' => $hasCsp],
                ['label' => 'X-Frame-Options', 'pass' => $hasXfo],
                ['label' => 'X-Content-Type-Options', 'pass' => $hasNosniff],
                ['label' => 'Referrer-Policy', 'pass' => $hasReferrer],
                ['label' => 'Server Header Hidden', 'pass' => $serverHeader === ''],
                ['label' => 'Mixed Content Risk', 'pass' => !$hasMixedContentRisk],
                ['label' => 'Form CSRF Markup Hint', 'pass' => !$hasForm || $hasCsrfHint],
                ['label' => 'Secure Cookie Attribute', 'pass' => $secureCookie],
                ['label' => 'HttpOnly Cookie Attribute', 'pass' => $httpOnlyCookie],
                ['label' => 'SameSite Cookie Attribute', 'pass' => $samesiteCookie],
                ['label' => 'Response Time < 1200ms', 'pass' => !$isSlow],
            ],
            'alerts' => $deductions,
        ];
    }

    private function fetchPageSpeedSnapshot(string $url, string $strategy): array
    {
        $apiKey = trim((string) config('services.google.pagespeed_api_key', ''));
        $query = [
            'url' => $url,
            'strategy' => $strategy,
        ];

        if ($apiKey !== '') {
            $query['key'] = $apiKey;
        }

        try {
            $response = Http::timeout(30)->get('https://www.googleapis.com/pagespeedonline/v5/runPagespeed', $query);
            if (!$response->ok()) {
                return [
                    'ok' => false,
                    'strategy' => $strategy,
                    'error' => 'HTTP ' . $response->status() . ' from Google PageSpeed API',
                ];
            }

            $payload = (array) $response->json();
            $lighthouse = (array) ($payload['lighthouseResult'] ?? []);
            $categories = (array) ($lighthouse['categories'] ?? []);
            $audits = (array) ($lighthouse['audits'] ?? []);
            $loadingMetrics = (array) ($payload['loadingExperience']['metrics'] ?? []);

            return [
                'ok' => true,
                'strategy' => $strategy,
                'scores' => [
                    'performance' => $this->scoreFromPageSpeedCategory($categories, 'performance'),
                    'accessibility' => $this->scoreFromPageSpeedCategory($categories, 'accessibility'),
                    'best_practices' => $this->scoreFromPageSpeedCategory($categories, 'best-practices'),
                    'seo' => $this->scoreFromPageSpeedCategory($categories, 'seo'),
                ],
                'metrics' => [
                    'fcp' => (string) (($audits['first-contentful-paint']['displayValue'] ?? '') ?: '-'),
                    'lcp' => (string) (($audits['largest-contentful-paint']['displayValue'] ?? '') ?: '-'),
                    'speed_index' => (string) (($audits['speed-index']['displayValue'] ?? '') ?: '-'),
                    'tbt' => (string) (($audits['total-blocking-time']['displayValue'] ?? '') ?: '-'),
                    'cls' => (string) (($audits['cumulative-layout-shift']['displayValue'] ?? '') ?: '-'),
                    'inp' => (string) (($audits['interaction-to-next-paint']['displayValue'] ?? '') ?: '-'),
                ],
                'core_web_vitals' => [
                    'lcp_percentile' => $loadingMetrics['LARGEST_CONTENTFUL_PAINT_MS']['percentile'] ?? null,
                    'cls_percentile' => $loadingMetrics['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'] ?? null,
                    'inp_percentile' => $loadingMetrics['EXPERIMENTAL_INTERACTION_TO_NEXT_PAINT']['percentile'] ?? null,
                ],
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'strategy' => $strategy,
                'error' => 'Google PageSpeed request failed: ' . $e->getMessage(),
            ];
        }
    }

    private function scoreFromPageSpeedCategory(array $categories, string $key): ?int
    {
        $raw = $categories[$key]['score'] ?? null;
        if (!is_numeric($raw)) {
            return null;
        }

        return max(1, min(100, (int) round(((float) $raw) * 100)));
    }

    private function runTechnicalSeoChecks(string $url): array
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 12,
                'ignore_errors' => true,
                'header' => "User-Agent: ARS-Audit-Lab/1.0\r\n",
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $html = @file_get_contents($url, false, $context);
        if (!is_string($html) || trim($html) === '') {
            return [
                'score' => 40,
                'checks' => [],
                'alerts' => ['Unable to fetch page HTML for technical SEO checks.'],
                'flags' => [
                    'has_robots' => false,
                    'has_sitemap' => false,
                    'has_noindex' => false,
                ],
            ];
        }

        $title = '';
        if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches) === 1) {
            $title = trim(strip_tags((string) ($matches[1] ?? '')));
        }

        $metaDescription = $this->extractMetaContent($html, 'description');
        $metaRobots = strtolower($this->extractMetaContent($html, 'robots'));
        $hasNoindex = str_contains($metaRobots, 'noindex');

        $titleLen = mb_strlen($title);
        $descLen = mb_strlen($metaDescription);
        $h1Count = (int) preg_match_all('/<h1\b[^>]*>/i', $html);
        $hasCanonical = preg_match('/<link[^>]*rel=["\']canonical["\'][^>]*href=["\'][^"\']+["\']/i', $html) === 1
            || preg_match('/<link[^>]*href=["\'][^"\']+["\'][^>]*rel=["\']canonical["\']/i', $html) === 1;
        $hasStructuredData = preg_match('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>/i', $html) === 1;
        $hasOgTitle = preg_match('/<meta[^>]*property=["\']og:title["\'][^>]*content=["\'][^"\']+["\']/i', $html) === 1
            || preg_match('/<meta[^>]*content=["\'][^"\']+["\'][^>]*property=["\']og:title["\']/i', $html) === 1;
        $hasLang = preg_match('/<html[^>]*\slang=["\'][a-zA-Z\-]+["\']/i', $html) === 1;
        $hasRobotsFile = $this->pathReachable($url, '/robots.txt');
        $hasSitemap = $this->pathReachable($url, '/sitemap.xml');

        $score = 100;
        $alerts = [];

        if ($titleLen === 0) {
            $score -= 18;
            $alerts[] = 'Title tag missing.';
        } elseif ($titleLen < 30 || $titleLen > 65) {
            $score -= 6;
            $alerts[] = 'Title length should be 30-65 characters.';
        }

        if ($descLen === 0) {
            $score -= 14;
            $alerts[] = 'Meta description missing.';
        } elseif ($descLen < 70 || $descLen > 160) {
            $score -= 6;
            $alerts[] = 'Meta description length should be 70-160 characters.';
        }

        if ($h1Count === 0) {
            $score -= 10;
            $alerts[] = 'No H1 heading found.';
        } elseif ($h1Count > 1) {
            $score -= 5;
            $alerts[] = 'Multiple H1 headings found.';
        }

        if (!$hasCanonical) {
            $score -= 8;
            $alerts[] = 'Canonical tag missing.';
        }
        if (!$hasStructuredData) {
            $score -= 6;
            $alerts[] = 'Structured data (JSON-LD) missing.';
        }
        if (!$hasOgTitle) {
            $score -= 5;
            $alerts[] = 'Open Graph title tag missing.';
        }
        if (!$hasLang) {
            $score -= 4;
            $alerts[] = 'HTML lang attribute missing.';
        }
        if (!$hasRobotsFile) {
            $score -= 6;
            $alerts[] = 'robots.txt not reachable.';
        }
        if (!$hasSitemap) {
            $score -= 5;
            $alerts[] = 'sitemap.xml not reachable.';
        }
        if ($hasNoindex) {
            $score -= 25;
            $alerts[] = 'Noindex meta directive detected.';
        }

        $score = max(0, min(100, $score));

        return [
            'score' => $score,
            'checks' => [
                ['label' => 'Title Tag Present', 'pass' => $titleLen > 0],
                ['label' => 'Meta Description Present', 'pass' => $descLen > 0],
                ['label' => 'Single H1 Structure', 'pass' => $h1Count === 1],
                ['label' => 'Canonical Tag', 'pass' => $hasCanonical],
                ['label' => 'Structured Data (JSON-LD)', 'pass' => $hasStructuredData],
                ['label' => 'Open Graph Tags', 'pass' => $hasOgTitle],
                ['label' => 'HTML Lang Attribute', 'pass' => $hasLang],
                ['label' => 'robots.txt Reachable', 'pass' => $hasRobotsFile],
                ['label' => 'sitemap.xml Reachable', 'pass' => $hasSitemap],
                ['label' => 'Noindex Not Present', 'pass' => !$hasNoindex],
            ],
            'alerts' => $alerts,
            'flags' => [
                'has_robots' => $hasRobotsFile,
                'has_sitemap' => $hasSitemap,
                'has_noindex' => $hasNoindex,
            ],
        ];
    }

    private function extractMetaContent(string $html, string $name): string
    {
        $escaped = preg_quote($name, '/');
        $patterns = [
            '/<meta[^>]*name=["\']' . $escaped . '["\'][^>]*content=["\']([^"\']*)["\'][^>]*>/i',
            '/<meta[^>]*content=["\']([^"\']*)["\'][^>]*name=["\']' . $escaped . '["\'][^>]*>/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $match) === 1) {
                return trim((string) ($match[1] ?? ''));
            }
        }

        return '';
    }

    private function pathReachable(string $url, string $path): bool
    {
        $parts = parse_url($url);
        $scheme = (string) ($parts['scheme'] ?? '');
        $host = (string) ($parts['host'] ?? '');
        if ($scheme === '' || $host === '') {
            return false;
        }

        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $target = $scheme . '://' . $host . $port . $path;
        $headers = @get_headers($target);
        if (!is_array($headers) || !isset($headers[0])) {
            return false;
        }

        if (preg_match('/\s(\d{3})\s/', (string) $headers[0], $match) !== 1) {
            return false;
        }

        $statusCode = (int) ($match[1] ?? 0);
        return $statusCode >= 200 && $statusCode < 400;
    }

    private function inspectSslCertificate(string $url): array
    {
        $host = (string) parse_url($url, PHP_URL_HOST);
        if ($host === '') {
            return [
                'available' => false,
                'error' => 'Unable to resolve hostname for SSL inspection.',
            ];
        }

        if (!function_exists('openssl_x509_parse')) {
            return [
                'available' => false,
                'error' => 'OpenSSL extension is not available on server.',
            ];
        }

        $ctx = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $client = @stream_socket_client('ssl://' . $host . ':443', $errno, $errstr, 8, STREAM_CLIENT_CONNECT, $ctx);
        if (!$client) {
            return [
                'available' => false,
                'error' => 'SSL handshake failed: ' . $errstr,
            ];
        }

        $params = stream_context_get_params($client);
        fclose($client);

        $certificate = $params['options']['ssl']['peer_certificate'] ?? null;
        if ($certificate === null) {
            return [
                'available' => false,
                'error' => 'No SSL certificate found.',
            ];
        }

        $parsed = @openssl_x509_parse($certificate);
        if (!is_array($parsed) || empty($parsed['validTo_time_t'])) {
            return [
                'available' => false,
                'error' => 'Unable to parse SSL certificate.',
            ];
        }

        $validTo = (int) $parsed['validTo_time_t'];
        $daysLeft = (int) floor(($validTo - time()) / 86400);
        $issuer = (string) ($parsed['issuer']['CN'] ?? '');

        return [
            'available' => true,
            'expires_at' => Carbon::createFromTimestamp($validTo)->toDateString(),
            'days_left' => $daysLeft,
            'issuer' => $issuer,
        ];
    }

    private function averageScore(array $values): ?float
    {
        $valid = array_values(array_filter($values, static fn ($value) => is_numeric($value)));
        if ($valid === []) {
            return null;
        }

        return array_sum(array_map(static fn ($value) => (float) $value, $valid)) / count($valid);
    }

    private function estimateTimeline(int $overallScore, int $securityScore): string
    {
        $combined = (int) round(($overallScore + $securityScore) / 2);
        if ($combined >= 90) {
            return '1-2 weeks';
        }
        if ($combined >= 80) {
            return '2-4 weeks';
        }
        if ($combined >= 70) {
            return '4-6 weeks';
        }
        return '6-8 weeks';
    }

    private function buildReference(): string
    {
        $prefix = 'AUD-' . now()->format('Ymd');
        $count = (int) AuditReport::query()->whereDate('created_at', now()->toDateString())->count() + 1;

        return $prefix . '-' . str_pad((string) $count, 3, '0', STR_PAD_LEFT);
    }

    private function buildShareMessage(AuditReport $audit): string
    {
        $recipient = trim((string) ($audit->recipient_name ?: 'there'));
        $issuerLegalName = (string) config('company.legal_name', 'ARS Developer Ltd');
        $issuerCompanyNumber = (string) config('company.company_number', '17039150');
        $issuerRegisteredIn = (string) config('company.registered_in', 'England & Wales');

        return "Hello {$recipient},\n\n"
            . "Your website audit is ready for {$audit->business_name}.\n"
            . "Website: {$audit->website_url}\n"
            . "Overall score: {$audit->overall_score}/100\n\n"
            . "Top next step:\n"
            . ($audit->recommendations ?: 'Start with technical fixes, UX clarity updates, and core SEO improvements.')
            . "\n\n"
            . "Regards,\nDirector\n{$issuerLegalName}\nCompany No: {$issuerCompanyNumber}\nRegistered in {$issuerRegisteredIn}";
    }

    private function buildPdfLines(AuditReport $audit): array
    {
        $generatedAt = Carbon::now()->format('d M Y H:i');
        $recipient = $audit->recipient_name ?: 'Not provided';
        $recipientEmail = $audit->recipient_email ?: 'Not provided';
        $issuerLegalName = (string) config('company.legal_name', 'ARS Developer Ltd');
        $issuerCompanyNumber = (string) config('company.company_number', '17039150');
        $issuerRegisteredIn = (string) config('company.registered_in', 'England & Wales');

        $lines = [
            strtoupper($issuerLegalName) . ' - WEBSITE AUDIT REPORT',
            'Reference: ' . $audit->reference,
            'Generated: ' . $generatedAt,
            '',
            'Client / Recipient: ' . $recipient,
            'Recipient Email: ' . $recipientEmail,
            'Business Name: ' . $audit->business_name,
            'Website: ' . $audit->website_url,
            '',
            'AUDIT SCORE SUMMARY',
            'Overall Score: ' . $audit->overall_score . '/100',
            'Performance Score: ' . ($audit->performance_score ?: 'N/A'),
            'SEO Score: ' . ($audit->seo_score ?: 'N/A'),
            'UX Score: ' . ($audit->ux_score ?: 'N/A'),
            'Security Score: ' . ($audit->security_score ?: 'N/A'),
            'Estimated Timeline: ' . ($audit->estimated_timeline ?: 'To be confirmed after implementation review'),
            '',
            'EXECUTIVE SUMMARY',
        ];

        $lines = array_merge($lines, $this->wrapText((string) ($audit->summary ?: 'No summary added.'), 92));
        $lines[] = '';
        $lines[] = 'KEY STRENGTHS';
        $lines = array_merge($lines, $this->wrapText((string) ($audit->strengths ?: 'No strengths section added.'), 92));
        $lines[] = '';
        $lines[] = 'PRIORITY ISSUES';
        $lines = array_merge($lines, $this->wrapText((string) ($audit->issues ?: 'No issue section added.'), 92));
        $lines[] = '';
        $lines[] = 'RECOMMENDED ACTION PLAN';
        $lines = array_merge($lines, $this->wrapText((string) ($audit->recommendations ?: 'No recommendations added.'), 92));
        $lines[] = '';
        $lines[] = 'Prepared by ' . $issuerLegalName;
        $lines[] = 'Company No: ' . $issuerCompanyNumber;
        $lines[] = 'Registered in ' . $issuerRegisteredIn;

        return $lines;
    }

    private function wrapText(string $text, int $maxChars = 92): array
    {
        $text = str_replace(["\r\n", "\r"], "\n", trim($text));
        if ($text === '') {
            return [''];
        }

        $wrapped = [];
        foreach (explode("\n", $text) as $paragraph) {
            $paragraph = trim($paragraph);
            if ($paragraph === '') {
                $wrapped[] = '';
                continue;
            }

            $line = '';
            foreach (preg_split('/\s+/', $paragraph) as $word) {
                $word = trim((string) $word);
                if ($word === '') {
                    continue;
                }

                $candidate = $line === '' ? $word : ($line . ' ' . $word);
                if (mb_strlen($candidate) > $maxChars && $line !== '') {
                    $wrapped[] = $line;
                    $line = $word;
                } else {
                    $line = $candidate;
                }
            }

            if ($line !== '') {
                $wrapped[] = $line;
            }
        }

        return $wrapped;
    }

    private function buildPdfBinary(array $lines): string
    {
        $maxLinesPerPage = 48;
        $chunks = array_chunk($lines, $maxLinesPerPage);
        if ($chunks === []) {
            $chunks = [['No data']];
        }

        $objects = [];
        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[3] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

        $kids = [];
        $nextObjectNumber = 4;

        foreach ($chunks as $chunk) {
            $pageObject = $nextObjectNumber;
            $contentObject = $nextObjectNumber + 1;
            $kids[] = $pageObject . ' 0 R';

            $stream = $this->buildPageContentStream($chunk);

            $objects[$pageObject] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] '
                . '/Resources << /Font << /F1 3 0 R >> >> '
                . '/Contents ' . $contentObject . ' 0 R >>';

            $objects[$contentObject] = "<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream";

            $nextObjectNumber += 2;
        }

        $objects[2] = '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . count($kids) . ' >>';
        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0 => 0];

        foreach ($objects as $num => $content) {
            $offsets[$num] = strlen($pdf);
            $pdf .= $num . " 0 obj\n" . $content . "\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $size = max(array_keys($objects)) + 1;

        $pdf .= "xref\n0 " . $size . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i < $size; $i++) {
            $offset = $offsets[$i] ?? 0;
            $pdf .= sprintf('%010d 00000 n ', $offset) . "\n";
        }

        $pdf .= "trailer\n";
        $pdf .= "<< /Size " . $size . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xrefOffset . "\n%%EOF";

        return $pdf;
    }

    private function buildPageContentStream(array $lines): string
    {
        $commands = [
            'BT',
            '/F1 11 Tf',
            '14 TL',
            '40 804 Td',
        ];

        foreach ($lines as $line) {
            if (trim((string) $line) === '') {
                $commands[] = 'T*';
                continue;
            }

            $safeLine = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', (string) $line);
            $safeLine = $safeLine === false ? (string) $line : $safeLine;
            $safeLine = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $safeLine);

            $commands[] = '(' . $safeLine . ') Tj';
            $commands[] = 'T*';
        }

        $commands[] = 'ET';

        return implode("\n", $commands);
    }
}
