<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ApiAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Access\ApiAuthServiceInterface',
            'App\Access\OauthService'
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
