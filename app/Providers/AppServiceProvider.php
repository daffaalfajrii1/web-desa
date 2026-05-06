<?php

namespace App\Providers;

use App\View\Composers\PublicFrontendComposer;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Quick tunnel sits behind reverse proxy; force https URLs there to avoid mixed-content blocked CSS/JS.
        if (str_contains((string) request()->getHost(), 'trycloudflare.com')) {
            URL::forceScheme('https');
        }

        View::composer('public.layouts.app', PublicFrontendComposer::class);
    }
}
