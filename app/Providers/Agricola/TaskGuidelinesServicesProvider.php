<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\TaskGuidelinesServiceInterface;
use App\Services\Agricola\TaskGuidelinesService;
use Illuminate\Support\ServiceProvider;

class TaskGuidelinesServicesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TaskGuidelinesServiceInterface::class, TaskGuidelinesService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
