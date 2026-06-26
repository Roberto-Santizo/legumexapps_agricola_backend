<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\CropInputServiceInterface;
use App\Services\Agricola\CropInputService;
use Illuminate\Support\ServiceProvider;

class CropInputProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CropInputServiceInterface::class, CropInputService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
