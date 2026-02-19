<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotificationRead;
use App\Models\Payment;
use App\Models\ProjectRequirement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{
    public function open(Request $request, string $type, int $activityId, int $projectId): RedirectResponse
    {
        $adminUserId = (int) $request->session()->get('admin_user_id', 0);
        if ($adminUserId > 0 && in_array($type, ['requirement', 'payment'], true)) {
            AdminNotificationRead::query()->updateOrCreate(
                [
                    'admin_user_id' => $adminUserId,
                    'activity_type' => $type,
                    'activity_id' => $activityId,
                ],
                ['read_at' => now()]
            );
        }

        return redirect()->route('admin.projects.show', $projectId);
    }

    public function markAll(Request $request): RedirectResponse
    {
        $adminUserId = (int) $request->session()->get('admin_user_id', 0);
        if ($adminUserId <= 0) {
            return back()->with('error', 'Admin user not linked for notification sync.');
        }

        $requirements = ProjectRequirement::query()
            ->where('source', 'client')
            ->latest()
            ->limit(200)
            ->pluck('id')
            ->map(fn ($id) => [
                'admin_user_id' => $adminUserId,
                'activity_type' => 'requirement',
                'activity_id' => (int) $id,
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $payments = Payment::query()
            ->where(function ($q) {
                $q->where('notes', 'like', 'Paid by client via portal.%')
                    ->orWhere('method', 'Portal Payment');
            })
            ->latest()
            ->limit(200)
            ->pluck('id')
            ->map(fn ($id) => [
                'admin_user_id' => $adminUserId,
                'activity_type' => 'payment',
                'activity_id' => (int) $id,
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $rows = $requirements->merge($payments)->values()->all();
        if (!empty($rows)) {
            DB::table('admin_notification_reads')->upsert(
                $rows,
                ['admin_user_id', 'activity_type', 'activity_id'],
                ['read_at', 'updated_at']
            );
        }

        return back()->with('success', 'All client notifications marked as read.');
    }
}
