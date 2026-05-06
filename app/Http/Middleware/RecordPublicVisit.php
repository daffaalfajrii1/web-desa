<?php

namespace App\Http\Middleware;

use App\Services\Public\VisitCounterService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordPublicVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        app(VisitCounterService::class)->record($request);

        return $next($request);
    }
}
