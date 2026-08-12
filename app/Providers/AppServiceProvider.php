<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Auth\UnscopedUserProvider;
use Illuminate\Support\Facades\Auth;

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
         Auth::provider('unscoped_eloquent', function ($app, array $config) {
        return new UnscopedUserProvider($app['hash'], $config['model']);
    });
    }
}
