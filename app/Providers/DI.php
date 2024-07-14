<?php

namespace App\Providers;

use App\Services\ImageOptimizer\ImageOptimizerService;
use Illuminate\Support\ServiceProvider;

class DI extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ImageOptimizerService::class, function ($app) {
            return new ImageOptimizerService(

            );
        });
    }

    public function boot(): void
    {
    }
}
