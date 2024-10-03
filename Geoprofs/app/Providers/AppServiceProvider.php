<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\database\Eloquent\model;


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
        Model::unguard(true);
    }
}
