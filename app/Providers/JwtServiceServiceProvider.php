<?php

namespace App\Providers;

use App\Services\JwtService;
use Illuminate\Support\ServiceProvider;

class JwtServiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(JwtService::class, function () {
            return new JwtService();
        });
    }

    public function boot(): void
    {
    }
}
