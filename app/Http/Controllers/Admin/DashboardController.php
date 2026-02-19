<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\EmailLog;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\ProjectRequirement;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'meetings' => Lead::where('type', 'meeting')->count(),
            'portfolios' => Portfolio::count(),
            'active_coupons' => Coupon::where('is_active', true)->count(),
            'emails_sent' => EmailLog::where('status', 'sent')->count(),
            'clients' => Client::count(),
            'projects' => Project::count(),
            'revenue' => Payment::sum('amount'),
            'client_actions_7d' => ProjectRequirement::where('source', 'client')->where('created_at', '>=', now()->subDays(7))->count()
                + Payment::where(function ($q) {
                    $q->where('notes', 'like', 'Paid by client via portal.%')
                        ->orWhere('method', 'Portal Payment');
                })->where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $upcomingMeetings = Lead::query()
            ->where('type', 'meeting')
            ->whereNotNull('meeting_date')
            ->whereDate('meeting_date', '>=', now()->toDateString())
            ->whereNotIn('status', ['cancelled', 'meeting_completed', 'no_show', 'closed'])
            ->orderBy('meeting_date')
            ->orderBy('meeting_slot')
            ->limit(8)
            ->get();

        $latestLeads = Lead::latest()->limit(8)->get();
        $latestProjects = Project::with('client')->latest()->limit(8)->get();
        $driver = DB::connection()->getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', payment_date)"
            : "DATE_FORMAT(payment_date, '%Y-%m')";

        $salesTrend = Payment::query()
            ->selectRaw("$monthExpr as ym, SUM(amount) as total")
            ->whereNotNull('payment_date')
            ->groupBy('ym')
            ->orderBy('ym', 'asc')
            ->get()
            ->map(function ($row) {
                $ym = trim((string) ($row->ym ?? ''));
                $label = $ym !== '' ? Carbon::createFromFormat('Y-m', $ym)->format('M y') : 'N/A';

                return [
                    'label' => $label,
                    'value' => (float) $row->total,
                ];
            })
            ->take(-8)
            ->values();

        $monthlyLeadClient = collect(range(0, 5))->map(function (int $offset) {
            $monthDate = now()->subMonths(5 - $offset);
            $start = $monthDate->copy()->startOfMonth();
            $end = $monthDate->copy()->endOfMonth();

            return [
                'label' => $monthDate->format('M y'),
                'leads' => Lead::query()->whereBetween('created_at', [$start, $end])->count(),
                'clients' => Client::query()->whereBetween('created_at', [$start, $end])->count(),
            ];
        });

        $requirementActivity = ProjectRequirement::query()
            ->with(['project.client'])
            ->where('source', 'client')
            ->latest()
            ->limit(12)
            ->get()
            ->map(function (ProjectRequirement $item) {
                return [
                    'at' => $item->created_at,
                    'type' => 'requirement',
                    'label' => 'Client submitted requirement',
                    'detail' => $item->title,
                    'client' => $item->project?->client?->name ?: 'Client',
                    'project' => $item->project?->title ?: '-',
                    'project_id' => $item->project_id,
                    'meta' => strtoupper((string) $item->status),
                ];
            });

        $paymentActivity = Payment::query()
            ->with(['project.client', 'invoice'])
            ->where(function ($q) {
                $q->where('notes', 'like', 'Paid by client via portal.%')
                    ->orWhere('method', 'Portal Payment');
            })
            ->latest()
            ->limit(12)
            ->get()
            ->map(function (Payment $item) {
                return [
                    'at' => $item->created_at,
                    'type' => 'payment',
                    'label' => 'Client submitted payment',
                    'detail' => $item->invoice?->invoice_number ?: 'Payment update',
                    'client' => $item->project?->client?->name ?: 'Client',
                    'project' => $item->project?->title ?: '-',
                    'project_id' => $item->project_id,
                    'meta' => ($item->project?->currency ?: 'GBP') . ' ' . number_format((float) $item->amount, 2),
                ];
            });

        $latestClientActivity = $requirementActivity
            ->merge($paymentActivity)
            ->sortByDesc('at')
            ->take(12)
            ->values();

        return view('admin.dashboard', compact('stats', 'upcomingMeetings', 'latestLeads', 'latestProjects', 'salesTrend', 'monthlyLeadClient', 'latestClientActivity'));
    }
}
