<?php

namespace App\Providers;

use App\Http\Controllers\conexionController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(conexionController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Add this custom validation rule.
    Validator::extend('alpha_spaces', function ($attribute, $value) {

        // This will only accept alpha and spaces. 
        // If you want to accept hyphens use: /^[\pL\s-]+$/u.
        return preg_match('/^[\pL\s]+$/u', $value); 

    });
    }
}
