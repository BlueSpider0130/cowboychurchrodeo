<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ListBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Contracts\ListBuilder',
            'App\Services\ListBuilderService'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
