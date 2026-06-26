<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\CropParameterServiceInterface;
use App\Services\Agricola\CropParameterService;
use Illuminate\Support\ServiceProvider;

class CropParameterProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CropParameterServiceInterface::class, CropParameterService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
