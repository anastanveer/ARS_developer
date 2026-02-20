<?php

namespace App\Http\Controllers;

use App\Models\ClientReview;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $approvedReviews = collect();
        if (Schema::hasTable('client_reviews')) {
            $approvedReviews = ClientReview::query()
                ->where('is_approved', true)
                ->whereNotNull('submitted_at')
                ->whereNotNull('rating')
                ->orderByDesc('approved_at')
                ->orderByDesc('submitted_at')
                ->limit(9)
                ->get();
        }

        return view('pages.index', compact('approvedReviews'));
    }
}
