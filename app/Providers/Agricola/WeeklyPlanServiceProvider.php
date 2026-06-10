<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
use App\Services\Agricola\WeeklyPlanService;
use Illuminate\Support\ServiceProvider;

class WeeklyPlanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WeeklyPlanServiceInterface::class, WeeklyPlanService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
