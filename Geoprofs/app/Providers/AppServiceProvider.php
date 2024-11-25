<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;  // Import Auth
use Illuminate\Support\Facades\View;  // Import View
use Illuminate\Database\Eloquent\Model;  // Import Model

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
        View::composer('*', function ($view) {
            $view->with('user', Auth::user());
        });

        Model::unguard(true);
    }
}
