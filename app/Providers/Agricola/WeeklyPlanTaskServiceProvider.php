<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\WeeklyPlanTaskServiceInterface;
use App\Services\Agricola\WeeklyPlanTaskService;

use Illuminate\Support\ServiceProvider;

class WeeklyPlanTaskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WeeklyPlanTaskServiceInterface::class, WeeklyPlanTaskService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
