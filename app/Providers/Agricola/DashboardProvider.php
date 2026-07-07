<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\DashboardServiceInterface;
use App\Services\Agricola\DashboardService;
use Illuminate\Support\ServiceProvider;

class DashboardProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
