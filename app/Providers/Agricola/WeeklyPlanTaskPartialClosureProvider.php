<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\WeeklyPlanTaskPartialClosureServiceInterface;
use App\Services\Agricola\WeeklyPlanTaskPartialClosureService;
use Illuminate\Support\ServiceProvider;

class WeeklyPlanTaskPartialClosureProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WeeklyPlanTaskPartialClosureServiceInterface::class, WeeklyPlanTaskPartialClosureService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
