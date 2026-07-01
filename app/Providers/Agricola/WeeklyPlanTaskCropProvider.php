<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\WeeklyPlanTaskCropServiceInterface;
use App\Services\Agricola\WeeklyPlanTaskCropService;
use Illuminate\Support\ServiceProvider;

class WeeklyPlanTaskCropProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WeeklyPlanTaskCropServiceInterface::class, WeeklyPlanTaskCropService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
