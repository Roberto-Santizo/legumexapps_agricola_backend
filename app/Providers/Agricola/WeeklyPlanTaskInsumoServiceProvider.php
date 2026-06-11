<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\WeeklyPlanTaskInsumoServiceInterface;
use App\Services\Agricola\WeeklyPlanTaskInsumoService;
use Illuminate\Support\ServiceProvider;

class WeeklyPlanTaskInsumoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WeeklyPlanTaskInsumoServiceInterface::class, WeeklyPlanTaskInsumoService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
