<?php

namespace App\Http\Controllers;

use App\Models\ClientReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientReviewController extends Controller
{
    public function show(string $token): View
    {
        $review = ClientReview::query()
            ->with(['project.client', 'invoice'])
            ->where('review_token', $token)
            ->firstOrFail();

        return view('pages/client-review', compact('review'));
    }

    public function submit(Request $request, string $token): RedirectResponse
    {
        $review = ClientReview::query()->where('review_token', $token)->firstOrFail();

        if ($review->submitted_at) {
            return back()->with('success', 'Your review is already submitted. Thank you.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_title' => ['required', 'string', 'max:160'],
            'review_text' => ['required', 'string', 'min:30', 'max:2400'],
            'result_summary' => ['nullable', 'string', 'max:220'],
            'reviewer_name' => ['required', 'string', 'max:140'],
            'reviewer_email' => ['required', 'email', 'max:190'],
            'company_name' => ['nullable', 'string', 'max:180'],
        ]);

        $review->fill($data);
        $review->submitted_at = now();
        $review->submitted_ip = (string) $request->ip();
        $review->submitted_country = strtoupper(substr((string) $request->header('CF-IPCountry', ''), 0, 8));
        $review->is_approved = false;
        $review->approved_at = null;
        $review->approved_by_admin_user_id = null;
        $review->save();

        return back()->with('success', 'Thanks. Your review has been submitted and is pending approval.');
    }
}

