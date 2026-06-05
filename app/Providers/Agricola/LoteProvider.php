<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\LoteServiceInterface;
use App\Services\Agricola\LoteService;
use Illuminate\Support\ServiceProvider;

class LoteProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LoteServiceInterface::class, LoteService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
