<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\DraftWeeklyPlanTaskServiceInterface;
use App\Services\Agricola\DraftWeeklyPlanTaskService;
use Illuminate\Support\ServiceProvider;

class DraftWeeklyPlanTaskProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DraftWeeklyPlanTaskServiceInterface::class, DraftWeeklyPlanTaskService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
