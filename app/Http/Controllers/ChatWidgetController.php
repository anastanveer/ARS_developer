<?php

namespace App\Http\Controllers;

use App\Mail\ChatAdminNotificationMail;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChatWidgetController extends Controller
{
    public function __construct(private readonly WhatsAppService $whatsAppService)
    {
    }

    public function bootstrap(Request $request): JsonResponse
    {
        $token = trim((string) $request->query('token', ''));
        $conversation = $token !== ''
            ? ChatConversation::query()->where('public_token', $token)->first()
            : null;

        if (!$conversation) {
            return response()->json([
                'conversation' => null,
                'messages' => [$this->welcomePayload()],
            ]);
        }

        $conversation->messages()
            ->whereIn('sender_type', ['admin', 'system'])
            ->where('is_read_by_visitor', false)
            ->update(['is_read_by_visitor' => true]);

        return response()->json([
            'conversation' => $this->conversationPayload($conversation),
            'messages' => $conversation->messages->map(fn (ChatMessage $message) => $this->messagePayload($message))->all(),
        ]);
    }

    public function conversation(string $token): JsonResponse
    {
        $conversation = ChatConversation::query()
            ->where('public_token', $token)
            ->firstOrFail();

        $conversation->messages()
            ->whereIn('sender_type', ['admin', 'system'])
            ->where('is_read_by_visitor', false)
            ->update(['is_read_by_visitor' => true]);

        return response()->json([
            'conversation' => $this->conversationPayload($conversation),
            'messages' => $conversation->messages->map(fn (ChatMessage $message) => $this->messagePayload($message))->all(),
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'newsletter_opt_in' => ['nullable', 'boolean'],
            'page_url' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your business email.',
            'email.email' => 'Please enter a valid business email.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $conversation = ChatConversation::query()->create([
            'public_token' => (string) Str::uuid(),
            'name' => $this->nullableString($request->input('name')),
            'email' => $this->nullableString($request->input('email')),
            'newsletter_opt_in' => (bool) $request->boolean('newsletter_opt_in'),
            'preferred_channel' => 'chat',
            'status' => 'open',
            'source_page' => $this->nullableString($request->input('page_url')),
            'visitor_ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        $conversation->messages()->create([
            'sender_type' => 'system',
            'sender_name' => config('company.legal_name', 'ARSDeveloper'),
            'body' => 'Hi, welcome to ARSDeveloper. Share your question and our team will reply shortly.',
            'channel' => 'chat',
            'is_read_by_admin' => true,
            'is_read_by_visitor' => true,
        ]);

        return response()->json([
            'success' => true,
            'conversation' => $this->conversationPayload($conversation->fresh('messages')),
            'messages' => $conversation->fresh('messages')->messages->map(fn (ChatMessage $message) => $this->messagePayload($message))->all(),
        ]);
    }

    public function message(Request $request): JsonResponse
    {
        $token = trim((string) $request->input('token', ''));
        $conversation = $token !== ''
            ? ChatConversation::query()->where('public_token', $token)->first()
            : null;

        $hasExistingContact = $conversation && ($conversation->email || $conversation->phone);

        $validator = Validator::make($request->all(), [
            'token' => ['nullable', 'string', 'max:80'],
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['nullable', 'string', 'max:2000'],
            'preferred_channel' => ['nullable', 'in:chat,whatsapp'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'page_url' => ['nullable', 'string', 'max:500'],
        ], [
            'image.image' => 'Only image files can be uploaded.',
            'image.max' => 'The image size must be 4MB or less.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $message = trim((string) $request->input('message'));
            if ($message === '' && !$request->hasFile('image')) {
                $validator->errors()->add('message', 'Please enter a message or upload an image.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $preferredChannel = (string) ($request->input('preferred_channel') ?: ($conversation?->preferred_channel ?: 'chat'));

        if ($preferredChannel === 'whatsapp' && $this->normalizePhone($request->input('phone') ?? $conversation?->phone ?? '') === '') {
            return response()->json([
                'success' => false,
                'message' => 'A WhatsApp number is required for WhatsApp replies.',
            ], 422);
        }

        if (!$conversation) {
            $conversation = ChatConversation::query()->create([
                'public_token' => (string) Str::uuid(),
                'name' => $this->nullableString($request->input('name')),
                'email' => $this->nullableString($request->input('email')),
                'newsletter_opt_in' => false,
                'phone' => $this->normalizePhone($request->input('phone')),
                'preferred_channel' => $preferredChannel,
                'status' => 'open',
                'source_page' => $this->nullableString($request->input('page_url')),
                'visitor_ip' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            ]);

            $conversation->messages()->create([
                'sender_type' => 'system',
                'sender_name' => config('company.legal_name', 'ARSDeveloper'),
                'body' => 'Hi, welcome to ARSDeveloper. Share your question and our team will reply shortly.',
                'channel' => 'chat',
                'is_read_by_admin' => true,
                'is_read_by_visitor' => true,
            ]);
        } else {
            $conversation->fill([
                'name' => $conversation->name ?: $this->nullableString($request->input('name')),
                'email' => $conversation->email ?: $this->nullableString($request->input('email')),
                'phone' => $conversation->phone ?: $this->normalizePhone($request->input('phone')),
                'preferred_channel' => $preferredChannel,
                'source_page' => $this->nullableString($request->input('page_url')) ?: $conversation->source_page,
                'status' => 'open',
                'closed_at' => null,
            ])->save();
        }

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('image')) {
            $attachmentPath = $request->file('image')->store('chat-widget', 'public');
            $attachmentName = $request->file('image')->getClientOriginalName();
        }

        $messageBody = $this->nullableString($request->input('message')) ?? ($attachmentPath ? '[image]' : '');

        $message = $conversation->messages()->create([
            'sender_type' => 'visitor',
            'sender_name' => $conversation->name ?: 'Website Visitor',
            'body' => $messageBody,
            'channel' => $preferredChannel === 'whatsapp' ? 'whatsapp' : 'chat',
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_read_by_admin' => false,
            'is_read_by_visitor' => true,
        ]);

        $conversation->forceFill([
            'preferred_channel' => $preferredChannel,
            'last_visitor_message_at' => now(),
            'admin_typing_at' => null,
            'source_page' => $this->nullableString($request->input('page_url')) ?: $conversation->source_page,
        ])->save();

        $this->sendAdminNotification($conversation, $message);
        $this->sendAdminWhatsAppNotification($conversation, $message);
        $this->sendVisitorWhatsAppAcknowledgement($conversation, $message, $preferredChannel);

        return response()->json([
            'success' => true,
            'conversation' => $this->conversationPayload($conversation->fresh('messages')),
            'messages' => $conversation->fresh('messages')->messages->map(fn (ChatMessage $item) => $this->messagePayload($item))->all(),
        ]);
    }

    protected function sendAdminNotification(ChatConversation $conversation, ChatMessage $message): void
    {
        $adminEmail = trim((string) config('contact.inbox_email', 'info@arsdeveloper.co.uk'));
        if ($adminEmail === '') {
            return;
        }

        try {
            Mail::to($adminEmail)->send(new ChatAdminNotificationMail($conversation, $message));
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    protected function sendAdminWhatsAppNotification(ChatConversation $conversation, ChatMessage $message): void
    {
        $adminRecipient = trim((string) config('whatsapp.admin_recipient', ''));
        if ($adminRecipient === '') {
            return;
        }

        $summary = 'New website chat from ' . ($conversation->name ?: 'Visitor')
            . '. Channel: ' . strtoupper((string) $conversation->preferred_channel)
            . '. Contact: ' . ($conversation->phone ?: ($conversation->email ?: 'No direct contact'))
            . '. Message: ' . ($message->body ?: '[image only]');

        $this->whatsAppService->sendTextMessage($adminRecipient, $summary);
    }

    protected function sendVisitorWhatsAppAcknowledgement(ChatConversation $conversation, ChatMessage $message, string $preferredChannel): void
    {
        if ($preferredChannel !== 'whatsapp' || !$conversation->phone) {
            return;
        }

        $alreadyAcknowledged = $conversation->messages()
            ->where('sender_type', 'system')
            ->where('channel', 'whatsapp')
            ->exists();

        if ($alreadyAcknowledged) {
            return;
        }

        $ack = 'Thanks for messaging ARS Developer Ltd. We received your website chat and our team will continue with you here on WhatsApp shortly.';
        $result = $this->whatsAppService->sendTextMessage($conversation->phone, $ack);

        if (($result['success'] ?? false) && !empty($result['message_id'])) {
            $conversation->messages()->create([
                'sender_type' => 'system',
                'sender_name' => config('company.legal_name', 'ARSDeveloper'),
                'body' => $ack,
                'channel' => 'whatsapp',
                'external_message_id' => $result['message_id'],
                'message_status' => 'sent',
                'raw_payload' => $result['data'] ?? null,
                'is_read_by_admin' => true,
                'is_read_by_visitor' => false,
            ]);
        }
    }

    protected function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);
        return $value !== '' ? $value : null;
    }

    protected function normalizePhone(mixed $value): ?string
    {
        $digits = $this->whatsAppService->normalizePhone((string) $value);
        return $digits !== '' ? $digits : null;
    }

    protected function welcomePayload(): array
    {
        return [
            'id' => 'welcome',
            'sender_type' => 'system',
            'sender_name' => config('company.legal_name', 'ARSDeveloper'),
            'body' => 'Hi, welcome to ARSDeveloper. Share your question and our team will reply shortly.',
            'attachment_url' => null,
            'created_at' => now()->toIso8601String(),
        ];
    }

    protected function conversationPayload(ChatConversation $conversation): array
    {
        return [
            'id' => $conversation->id,
            'token' => $conversation->public_token,
            'name' => $conversation->name,
            'email' => $conversation->email,
            'phone' => $conversation->phone,
            'status' => $conversation->status,
            'preferred_channel' => $conversation->preferred_channel,
            'admin_typing' => optional($conversation->admin_typing_at)?->gt(now()->subSeconds(6)) ?? false,
        ];
    }

    protected function messagePayload(ChatMessage $message): array
    {
        return [
            'id' => $message->id,
            'sender_type' => $message->sender_type,
            'sender_name' => $message->sender_name,
            'body' => $message->body,
            'channel' => $message->channel,
            'attachment_url' => $message->attachment_path ? asset('storage/' . $message->attachment_path) : null,
            'attachment_name' => $message->attachment_name,
            'created_at' => optional($message->created_at)->toIso8601String(),
        ];
    }
}
