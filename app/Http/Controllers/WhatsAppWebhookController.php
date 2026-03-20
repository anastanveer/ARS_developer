<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\WhatsAppWebhookLog;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsAppWebhookController extends Controller
{
    public function __construct(private readonly WhatsAppService $whatsAppService)
    {
    }

    public function verify(Request $request)
    {
        $mode = (string) $request->query('hub_mode', $request->query('hub.mode', ''));
        $token = (string) $request->query('hub_verify_token', $request->query('hub.verify_token', ''));
        $challenge = (string) $request->query('hub_challenge', $request->query('hub.challenge', ''));

        if ($mode === 'subscribe' && hash_equals((string) config('whatsapp.verify_token'), $token)) {
            return response($challenge, 200);
        }

        Log::warning('WhatsApp webhook verification failed.', [
            'mode' => $mode,
        ]);

        return response('Invalid verify token.', 403);
    }

    public function receive(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('WhatsApp webhook received.', ['payload' => $payload]);

        $log = WhatsAppWebhookLog::query()->create([
            'event_type' => $this->detectEventType($payload),
            'payload' => $payload,
            'processed' => false,
        ]);

        $parsed = $this->whatsAppService->parseWebhookPayload($payload);

        foreach ($parsed['messages'] as $message) {
            $this->storeInboundMessage($message);
        }

        foreach ($parsed['statuses'] as $status) {
            $this->applyStatusUpdate($status);
        }

        $log->forceFill(['processed' => true])->save();

        return response()->json([
            'success' => true,
            'messages' => count($parsed['messages']),
            'statuses' => count($parsed['statuses']),
        ]);
    }

    protected function storeInboundMessage(array $message): void
    {
        $phone = $this->whatsAppService->normalizePhone((string) ($message['from'] ?? ''));
        if ($phone === '') {
            return;
        }

        $conversation = ChatConversation::query()
            ->where('phone', $phone)
            ->latest('id')
            ->first();

        if (!$conversation) {
            $conversation = ChatConversation::query()->create([
                'public_token' => (string) Str::uuid(),
                'name' => $message['profile_name'] ?: 'WhatsApp Visitor',
                'phone' => $phone,
                'preferred_channel' => 'whatsapp',
                'status' => 'open',
                'source_page' => 'whatsapp:webhook',
                'last_visitor_message_at' => now(),
            ]);
        }

        $exists = ChatMessage::query()->where('external_message_id', (string) ($message['message_id'] ?? ''))->exists();
        if ($exists) {
            return;
        }

        $body = $message['body'] ?: '[WhatsApp ' . ($message['type'] ?: 'message') . ']';
        if (($message['type'] ?? '') === 'image' && !str_contains($body, '[WhatsApp')) {
            $body .= ' [image]';
        }

        $conversation->messages()->create([
            'sender_type' => 'visitor',
            'sender_name' => $message['profile_name'] ?: ($conversation->name ?: 'WhatsApp Visitor'),
            'body' => $body,
            'channel' => 'whatsapp',
            'external_message_id' => $message['message_id'] ?: null,
            'message_status' => 'received',
            'raw_payload' => $message['raw'] ?? null,
            'is_read_by_admin' => false,
            'is_read_by_visitor' => true,
        ]);

        $conversation->forceFill([
            'preferred_channel' => 'whatsapp',
            'phone' => $phone,
            'status' => 'open',
            'closed_at' => null,
            'last_visitor_message_at' => now(),
        ])->save();
    }

    protected function applyStatusUpdate(array $status): void
    {
        $message = ChatMessage::query()
            ->where('external_message_id', (string) ($status['message_id'] ?? ''))
            ->first();

        if (!$message) {
            return;
        }

        $message->forceFill([
            'message_status' => $status['status'] ?: $message->message_status,
            'raw_payload' => $status['raw'] ?? $message->raw_payload,
        ])->save();
    }

    protected function detectEventType(array $payload): ?string
    {
        if (Arr::has($payload, 'entry.0.changes.0.value.messages.0')) {
            return 'message';
        }

        if (Arr::has($payload, 'entry.0.changes.0.value.statuses.0')) {
            return 'status';
        }

        return 'unknown';
    }
}
