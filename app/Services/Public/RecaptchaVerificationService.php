<?php

namespace App\Services\Public;

use Illuminate\Support\Facades\Http;
use Throwable;

class RecaptchaVerificationService
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function isConfigured(): bool
    {
        $secret = config('services.recaptcha.secret_key');
        $site = config('services.recaptcha.site_key');

        return is_string($secret) && $secret !== '' && is_string($site) && $site !== '';
    }

    public function verify(?string $responseToken, ?string $remoteIp = null): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        $token = trim((string) $responseToken);
        if ($token === '') {
            return false;
        }

        try {
            $res = Http::asForm()->timeout(10)->post(self::VERIFY_URL, [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => $remoteIp,
            ]);

            if (! $res->ok()) {
                return false;
            }

            $json = $res->json();

            return ! empty($json['success']);
        } catch (Throwable) {
            return false;
        }
    }
}
