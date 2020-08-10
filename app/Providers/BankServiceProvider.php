<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BankServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\ExchangeRate\BankService',
            'App\ExchangeRate\NbuServiceVersion1'
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
