<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientReview;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientReviewController extends Controller
{
    public function index(Request $request): View
    {
        try {
            if (!Schema::hasTable('client_reviews')) {
                return view('admin.reviews.index', [
                    'reviews' => $this->emptyPaginator($request),
                ]);
            }

            $query = ClientReview::query()->with(['project.client', 'invoice', 'payment'])->latest();

            if ($request->filled('status')) {
                $status = (string) $request->input('status');
                if ($status === 'approved') {
                    $query->where('is_approved', true);
                } elseif ($status === 'pending') {
                    $query->whereNotNull('submitted_at')->where('is_approved', false);
                } elseif ($status === 'draft') {
                    $query->whereNull('submitted_at');
                }
            }

            if ($request->filled('q')) {
                $term = trim((string) $request->input('q'));
                $query->where(function ($q) use ($term) {
                    $q->where('reviewer_name', 'like', "%{$term}%")
                        ->orWhere('reviewer_email', 'like', "%{$term}%")
                        ->orWhere('review_title', 'like', "%{$term}%")
                        ->orWhere('review_text', 'like', "%{$term}%");
                });
            }

            $reviews = $query->paginate(20)->withQueryString();
        } catch (QueryException $e) {
            if (str_contains(strtolower($e->getMessage()), 'no such table')
                && str_contains(strtolower($e->getMessage()), 'client_reviews')) {
                $reviews = $this->emptyPaginator($request);
            } else {
                throw $e;
            }
        }

        return view('admin.reviews.index', compact('reviews'));
    }

    private function emptyPaginator(Request $request): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            collect(),
            0,
            20,
            LengthAwarePaginator::resolveCurrentPage(),
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }

    public function approve(Request $request, ClientReview $review): RedirectResponse
    {
        $review->is_approved = true;
        $review->approved_at = now();
        $review->approved_by_admin_user_id = (int) session('admin_user_id', 0) ?: null;
        $review->save();

        return back()->with('success', 'Review approved and now visible on site.');
    }

    public function unapprove(ClientReview $review): RedirectResponse
    {
        $review->is_approved = false;
        $review->approved_at = null;
        $review->approved_by_admin_user_id = null;
        $review->save();

        return back()->with('success', 'Review moved back to pending.');
    }

    public function destroy(ClientReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
