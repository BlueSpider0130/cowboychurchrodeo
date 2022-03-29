<?php

namespace Sre\SmartReportingEngine\src\Providers;

use Illuminate\Support\ServiceProvider;

class SmartEngineServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../sre_reports/shared' => public_path('sre_reports/shared'),
        ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
