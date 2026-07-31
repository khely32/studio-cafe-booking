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
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                $this->seedIfNeeded();
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }

    private function seedIfNeeded(): void
    {
        if (! \App\Models\User::where('email', 'admin@5630studiocafe.com')->exists()) {
            \App\Models\User::create([
                'name' => 'Admin',
                'email' => 'admin@5630studiocafe.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ]);
        }

        if (\App\Models\Service::count() === 0) {
            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--class' => \Database\Seeders\ServiceSeeder::class,
                '--force' => true,
            ]);
            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--class' => \Database\Seeders\SampleDataSeeder::class,
                '--force' => true,
            ]);
        }
    }
}
