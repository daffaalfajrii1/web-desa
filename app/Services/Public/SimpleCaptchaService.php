<?php

namespace App\Services\Public;

use Illuminate\Support\Facades\Session;

class SimpleCaptchaService
{
    private const SESSION_KEY = 'public_captcha';

    public function challenge(string $context): array
    {
        $left = random_int(2, 9);
        $right = random_int(1, 9);

        Session::put($this->sessionKey($context), (string) ($left + $right));

        return [
            'context' => $context,
            'question' => $left.' + '.$right.' =',
            'field' => 'captcha_answer',
        ];
    }

    public function verify(string $context, mixed $answer): bool
    {
        $expected = Session::get($this->sessionKey($context));

        if ($expected === null) {
            return false;
        }

        $isValid = hash_equals((string) $expected, trim((string) $answer));

        if ($isValid) {
            Session::forget($this->sessionKey($context));
        }

        return $isValid;
    }

    private function sessionKey(string $context): string
    {
        return self::SESSION_KEY.'.'.$context;
    }
}
