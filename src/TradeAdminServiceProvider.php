<?php

namespace Selfreliance\TradeAdmin;

use Illuminate\Support\ServiceProvider;

class TradeAdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        include __DIR__.'/routes.php';
        $this->app->make('Selfreliance\TradeAdmin\TradeAdminController');
        $this->loadViewsFrom(__DIR__.'/views', 'tradeadmin');
        
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