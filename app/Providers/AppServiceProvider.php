<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        /*
            Laravel character set is utf8mb4. However, this app need to support an older MySQL version (i.e. less than v5.7.7).
            So set default string length to 191 here.
            See: https://laravel-news.com/laravel-5-4-key-too-long-error
        */          
        Schema::defaultStringLength(191);
    }
}
