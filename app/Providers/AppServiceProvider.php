<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 1. Add this line
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // 2. Add this line to fix the design
        Paginator::useBootstrap();
    }
}
