<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    public function verify(?string $token, ?string $ip = null): array
    {
        if (!config('recaptcha.enabled')) {
            return [
                'success' => true,
                'message' => null,
                'error_codes' => [],
            ];
        }

        if (blank(config('recaptcha.site_key')) || blank(config('recaptcha.secret_key'))) {
            Log::warning('reCAPTCHA validation skipped because configuration is incomplete.');

            return [
                'success' => false,
                'message' => 'Verification is temporarily unavailable. Please try again shortly.',
                'error_codes' => ['missing-config'],
            ];
        }

        if (blank($token)) {
            return [
                'success' => false,
                'message' => 'Please complete the Google reCAPTCHA check.',
                'error_codes' => ['missing-input-response'],
            ];
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post(config('recaptcha.verify_url'), array_filter([
                    'secret' => config('recaptcha.secret_key'),
                    'response' => $token,
                    'remoteip' => $ip,
                ]));

            $payload = $response->json() ?: [];

            if ($response->failed() || !($payload['success'] ?? false)) {
                Log::warning('reCAPTCHA verification failed.', [
                    'status' => $response->status(),
                    'payload' => $payload,
                ]);

                return [
                    'success' => false,
                    'message' => 'Please complete the Google reCAPTCHA check.',
                    'error_codes' => $payload['error-codes'] ?? [],
                ];
            }

            return [
                'success' => true,
                'message' => null,
                'error_codes' => [],
            ];
        } catch (\Throwable $exception) {
            Log::error('reCAPTCHA verification exception.', [
                'message' => $exception->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Verification is temporarily unavailable. Please try again shortly.',
                'error_codes' => ['request-failed'],
            ];
        }
    }
}
