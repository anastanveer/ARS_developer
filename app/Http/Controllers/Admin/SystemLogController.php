<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SystemLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemLogController extends Controller
{
    public function __construct(private readonly SystemLogService $logService)
    {
    }

    public function index(Request $request): View
    {
        $availableDates = $this->logService->collectAvailableDates(21);
        $selectedDate = (string) $request->query('date', now()->toDateString());
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = now()->toDateString();
        }

        if ($request->boolean('refresh')) {
            $freshDigest = $this->logService->generateDigest($selectedDate);
            $this->logService->writeDigest($freshDigest);
        }

        $digest = $this->logService->readDigest($selectedDate);
        if (!$digest) {
            $digest = $this->logService->generateDigest($selectedDate);
            $this->logService->writeDigest($digest);
        }

        $limit = (int) $request->query('limit', 250);
        $limit = max(50, min($limit, 1000));
        $entries = $this->logService->collectEntriesByDate($selectedDate, $limit);

        return view('admin.logs.index', compact(
            'availableDates',
            'selectedDate',
            'digest',
            'entries',
            'limit'
        ));
    }
}

