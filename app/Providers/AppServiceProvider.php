<?php

namespace App\Providers;

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
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        \Illuminate\Support\Facades\URL::forceRootUrl(request()->getSchemeAndHttpHost());

        if (! $this->app->runningInConsole() && config('database.default') === 'pgsql') {
            try {
                if (! \Illuminate\Support\Facades\Schema::hasTable('migrations')) {
                    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}
