<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\TaskGuidelineSupplyServiceInterface;
use App\Services\Agricola\TaskGuidelineSupplyService;
use Illuminate\Support\ServiceProvider;

class TaskGuidelineSupplyProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TaskGuidelineSupplyServiceInterface::class, TaskGuidelineSupplyService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
