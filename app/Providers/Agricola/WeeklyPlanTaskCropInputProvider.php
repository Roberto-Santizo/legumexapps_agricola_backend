<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\WeeklyPlanTaskCropInputServiceInterface;
use App\Services\Agricola\WeeklyPlanTaskCropInputService;
use Illuminate\Support\ServiceProvider;

class WeeklyPlanTaskCropInputProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WeeklyPlanTaskCropInputServiceInterface::class, WeeklyPlanTaskCropInputService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
