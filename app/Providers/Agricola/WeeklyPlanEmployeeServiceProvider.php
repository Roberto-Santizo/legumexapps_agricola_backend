<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\WeeklyPlanEmployeeServiceInterface;
use App\Services\Agricola\WeeklyPlanEmployeeService;
use Illuminate\Support\ServiceProvider;

class WeeklyPlanEmployeeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WeeklyPlanEmployeeServiceInterface::class, WeeklyPlanEmployeeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
