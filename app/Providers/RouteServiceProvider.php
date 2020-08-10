<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Action\IncomeService;
use App\Action\ExchangeService;
use App\Action\ForceExchangeService;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    protected const ACTION_TYPED_ID_RE = '/^(?P<type>income|exchange|forceExchange)(?P<id>[0-9]+)$/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */

    public function boot()
    {
        parent::boot();
        $re = static::ACTION_TYPED_ID_RE;
        Route::bind('actionTypeId', function($typeId) use ($re) {
           $params = [];
           if (preg_match($re, $typeId, $params)){
                switch ($params['type']) {
                    case 'income':
                        return app()->make('App\Action\IncomeService')->findById($params['id'])->firstOrFail();
                    case 'exchange':
                        return app()->make('App\Action\ExchangeService')->findById($params['id'])->firstOrFail();
                    case 'forceExchange':
                        return app()->make('App\Action\ForceExchangeService')->findById($params['id'])->firstOrFail();
                }     
           }
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
