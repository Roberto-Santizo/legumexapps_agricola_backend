<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\SupplyServiceInterface;
use App\Services\Agricola\SupplyService;
use Illuminate\Support\ServiceProvider;

class SupplyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SupplyServiceInterface::class, SupplyService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
