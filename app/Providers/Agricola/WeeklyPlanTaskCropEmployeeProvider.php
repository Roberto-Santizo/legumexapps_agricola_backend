<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\WeeklyPlanTaskCropEmployeeServiceInterface;
use App\Services\Agricola\WeeklyPlanTaskCropEmployeeService;
use Illuminate\Support\ServiceProvider;

class WeeklyPlanTaskCropEmployeeProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WeeklyPlanTaskCropEmployeeServiceInterface::class, WeeklyPlanTaskCropEmployeeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
