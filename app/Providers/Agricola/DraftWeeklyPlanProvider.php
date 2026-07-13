<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\DraftWeeklyPlanServiceInterface;
use App\Services\Agricola\DraftWeeklyPlanService;
use Illuminate\Support\ServiceProvider;

class DraftWeeklyPlanProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DraftWeeklyPlanServiceInterface::class, DraftWeeklyPlanService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
