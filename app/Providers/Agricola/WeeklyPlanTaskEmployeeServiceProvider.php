<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\WeeklyPlanTaskEmployeeServiceInterface;
use App\Services\Agricola\WeeklyPlanTaskEmployeeService;
use Illuminate\Support\ServiceProvider;

class WeeklyPlanTaskEmployeeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WeeklyPlanTaskEmployeeServiceInterface::class, WeeklyPlanTaskEmployeeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
