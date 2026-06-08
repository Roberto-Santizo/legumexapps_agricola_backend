<?php

namespace App\Providers\Agricola;

use App\Interfaces\Agricola\RecipeServiceInterface;
use App\Services\Agricola\RecipeService;
use Illuminate\Support\ServiceProvider;

class RecipeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RecipeServiceInterface::class, RecipeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
