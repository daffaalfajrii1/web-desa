<?php

namespace App\Services\Public;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class VisitCounterService
{
    public function record(Request $request): void
    {
        try {
            if (! Schema::hasTable('public_visits')) {
                return;
            }

            $date = now()->toDateString();
            $sessionKey = 'public_visit_recorded_'.$date;

            if ($request->session()->has($sessionKey)) {
                return;
            }

            $sessionId = $request->session()->getId()
                ?: hash('sha256', (string) $request->ip().'|'.(string) $request->userAgent());

            DB::table('public_visits')->updateOrInsert(
                [
                    'visit_date' => $date,
                    'session_id' => substr($sessionId, 0, 120),
                ],
                [
                    'ip_hash' => $request->ip() ? hash('sha256', $request->ip()) : null,
                    'user_agent_hash' => $request->userAgent() ? hash('sha256', $request->userAgent()) : null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $request->session()->put($sessionKey, true);
        } catch (Throwable) {
            return;
        }
    }

    public function todayCount(): int
    {
        try {
            if (! Schema::hasTable('public_visits')) {
                return 0;
            }

            return (int) DB::table('public_visits')
                ->whereDate('visit_date', now()->toDateString())
                ->count();
        } catch (Throwable) {
            return 0;
        }
    }
}
