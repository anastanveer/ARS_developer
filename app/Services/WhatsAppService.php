<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WhatsAppService
{
    public function sendTextMessage(string $to, string $message): array
    {
        return $this->sendPayload([
            'messaging_product' => 'whatsapp',
            'to' => $this->normalizePhone($to),
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $message,
            ],
        ]);
    }

    public function sendTemplateMessage(string $to, string $templateName, string $languageCode = 'en_US', array $components = []): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->normalizePhone($to),
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
            ],
        ];

        if ($components !== []) {
            $payload['template']['components'] = $components;
        }

        return $this->sendPayload($payload);
    }

    public function markMessageAsRead(string $messageId): array
    {
        return $this->sendPayload([
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $messageId,
        ]);
    }

    public function parseWebhookPayload(array $payload): array
    {
        $parsed = [
            'messages' => [],
            'statuses' => [],
        ];

        foreach ((array) Arr::get($payload, 'entry', []) as $entry) {
            foreach ((array) Arr::get($entry, 'changes', []) as $change) {
                $value = (array) Arr::get($change, 'value', []);
                $contacts = (array) Arr::get($value, 'contacts', []);
                $contactProfile = (array) Arr::get($contacts, '0.profile', []);
                $contactWaId = (string) Arr::get($contacts, '0.wa_id', '');

                foreach ((array) Arr::get($value, 'messages', []) as $message) {
                    $type = (string) Arr::get($message, 'type', 'text');
                    $body = (string) Arr::get($message, 'text.body', '');

                    if ($type === 'button') {
                        $body = (string) Arr::get($message, 'button.text', $body);
                    }

                    $parsed['messages'][] = [
                        'message_id' => (string) Arr::get($message, 'id', ''),
                        'from' => $this->normalizePhone((string) Arr::get($message, 'from', '')),
                        'profile_name' => (string) Arr::get($contactProfile, 'name', ''),
                        'wa_id' => $contactWaId !== '' ? $this->normalizePhone($contactWaId) : '',
                        'timestamp' => (string) Arr::get($message, 'timestamp', ''),
                        'type' => $type,
                        'body' => $body,
                        'raw' => $message,
                    ];
                }

                foreach ((array) Arr::get($value, 'statuses', []) as $status) {
                    $parsed['statuses'][] = [
                        'message_id' => (string) Arr::get($status, 'id', ''),
                        'recipient_id' => $this->normalizePhone((string) Arr::get($status, 'recipient_id', '')),
                        'status' => (string) Arr::get($status, 'status', ''),
                        'timestamp' => (string) Arr::get($status, 'timestamp', ''),
                        'conversation_id' => (string) Arr::get($status, 'conversation.id', ''),
                        'raw' => $status,
                    ];
                }
            }
        }

        return $parsed;
    }

    public function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?: '';
    }

    public function isConfigured(): bool
    {
        return (bool) config('whatsapp.enabled', true)
            && trim((string) config('whatsapp.access_token')) !== ''
            && trim((string) config('whatsapp.phone_number_id')) !== '';
    }

    protected function sendPayload(array $payload): array
    {
        $phoneNumberId = trim((string) config('whatsapp.phone_number_id'));
        $version = trim((string) config('whatsapp.graph_version', 'v22.0'));
        $url = sprintf('https://graph.facebook.com/%s/%s/messages', $version, $phoneNumberId);

        if (!$this->isConfigured()) {
            Log::warning('WhatsApp payload skipped because configuration is incomplete.', [
                'url' => $url,
                'payload' => Arr::except($payload, ['access_token']),
            ]);

            return [
                'success' => false,
                'error' => 'WhatsApp is not configured.',
                'status' => null,
                'data' => null,
            ];
        }

        try {
            Log::info('WhatsApp outbound request', [
                'url' => $url,
                'payload' => $payload,
            ]);

            $response = Http::timeout((int) config('whatsapp.timeout', 15))
                ->withToken((string) config('whatsapp.access_token'))
                ->acceptJson()
                ->post($url, $payload);

            $data = $response->json();

            Log::info('WhatsApp outbound response', [
                'status' => $response->status(),
                'body' => $data,
            ]);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $data,
                'message_id' => Arr::get($data, 'messages.0.id'),
                'error' => $response->successful() ? null : ($data['error']['message'] ?? 'WhatsApp request failed.'),
            ];
        } catch (Throwable $exception) {
            Log::error('WhatsApp outbound exception', [
                'message' => $exception->getMessage(),
            ]);

            return [
                'success' => false,
                'status' => null,
                'data' => null,
                'message_id' => null,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
