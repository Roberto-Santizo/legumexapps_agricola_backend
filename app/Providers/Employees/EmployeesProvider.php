<?php

namespace App\Providers\Employees;

use App\Interfaces\Employees\EmployeesServiceInterface;
use App\Services\Employees\EmployeesService;
use Illuminate\Support\ServiceProvider;

class EmployeesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EmployeesServiceInterface::class, EmployeesService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
