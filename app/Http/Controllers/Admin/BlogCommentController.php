<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogCommentController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', 'pending');

        $comments = BlogComment::query()
            ->with('blogPost:id,title,slug')
            ->when($status === 'approved', fn ($query) => $query->where('is_approved', true))
            ->when($status === 'pending', fn ($query) => $query->where('is_approved', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.blog-comments.index', compact('comments', 'status'));
    }

    public function approve(BlogComment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => true]);

        return back()->with('success', 'Comment approved.');
    }

    public function unapprove(BlogComment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => false]);

        return back()->with('success', 'Comment moved back to pending.');
    }

    public function destroy(BlogComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
