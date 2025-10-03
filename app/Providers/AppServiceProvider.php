<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for schema
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
        
        // Configure validator to handle checkbox inputs more consistently
        \Illuminate\Support\Facades\Validator::extend('boolean', function ($attribute, $value, $parameters, $validator) {
            return $value === '1' || $value === 1 || $value === true || $value === 'true' || $value === 'on' || $value === '0' || $value === 0 || $value === false || $value === 'false' || $value === 'off' || $value === null;
        });
    }
}
