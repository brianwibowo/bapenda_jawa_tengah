<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        // Force HTTPS only when it is explicitly enabled or in production.
        if (config('app.env') === 'production' || filter_var(env('FORCE_HTTPS', false), FILTER_VALIDATE_BOOL)) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Gate::before(function ($user, $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });
    }
}
