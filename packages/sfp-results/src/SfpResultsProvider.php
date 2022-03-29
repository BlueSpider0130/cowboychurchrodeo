<?php

namespace SfpResults;

use Illuminate\Support\ServiceProvider;


class SfpResultsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->resolving(ResultsRequest::class, function ($request, $app) {
            $request = ResultsRequest::createFrom($app['request'], $request);
        });

        $this->loadViewsFrom(__DIR__.'/views', 'sfp-results');
    }
}
