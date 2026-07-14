<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\AnnualSalaryServiceInterface;
use App\Services\Agricola\AnnualSalaryService;
use Illuminate\Support\ServiceProvider;

class AnnualSalaryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AnnualSalaryServiceInterface::class, AnnualSalaryService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
