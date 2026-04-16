<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Services\RecaptchaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogCommentController extends Controller
{
    public function store(Request $request, string $slug, RecaptchaService $recaptcha): RedirectResponse
    {
        $post = BlogPost::query()
            ->live()
            ->where('slug', $slug)
            ->firstOrFail();

        // Silent spam trap for bots filling hidden fields.
        if (trim((string) $request->input('company_name', '')) !== '') {
            return redirect()->to(route('blog.show', $post->slug) . '#blog-comments')
                ->with('comment_success', 'Thanks. Your comment has been received for review.');
        }

        $payload = $request->all();
        $payload['website'] = $this->normalizeWebsite((string) ($payload['website'] ?? ''));

        $validator = Validator::make($payload, [
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'website' => ['nullable', 'url', 'max:255'],
            'comment' => ['required', 'string', 'min:8', 'max:5000'],
            'g-recaptcha-response' => ['required', 'string'],
            'newsletter_opt_in' => ['nullable', 'boolean'],
        ], [
            'full_name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your business email address.',
            'email.email' => 'Please enter a valid email address.',
            'website.url' => 'Please enter a valid website URL.',
            'comment.required' => 'Please add your comment before submitting.',
            'comment.min' => 'Please write a slightly longer comment so we can review it properly.',
            'g-recaptcha-response.required' => 'Please complete the Google reCAPTCHA check.',
        ]);

        if ($validator->fails()) {
            return redirect()->to(route('blog.show', $post->slug) . '#blog-comments')
                ->withErrors($validator)
                ->withInput();
        }

        $verification = $recaptcha->verify((string) $request->input('g-recaptcha-response'), $request->ip());

        if (!$verification['success']) {
            return redirect()->to(route('blog.show', $post->slug) . '#blog-comments')
                ->withErrors([
                    'g-recaptcha-response' => $verification['message'] ?? 'Please complete the Google reCAPTCHA check.',
                ])
                ->withInput();
        }

        BlogComment::create([
            'blog_post_id' => $post->id,
            'full_name' => trim((string) $payload['full_name']),
            'email' => trim((string) $payload['email']),
            'website' => $payload['website'] ?: null,
            'comment' => trim((string) $payload['comment']),
            'newsletter_opt_in' => $request->boolean('newsletter_opt_in'),
            'is_approved' => false,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        return redirect()->to(route('blog.show', $post->slug) . '#blog-comments')
            ->with('comment_success', 'Thanks. Your comment has been submitted and will appear after review.');
    }

    private function normalizeWebsite(string $website): string
    {
        $website = trim($website);
        if ($website === '') {
            return '';
        }

        if (!preg_match('#^https?://#i', $website)) {
            $website = 'https://' . $website;
        }

        return $website;
    }
}
