<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\CdpServiceInterface;
use App\Services\Agricola\CdpService;
use Illuminate\Support\ServiceProvider;

class CdpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CdpServiceInterface::class, CdpService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
