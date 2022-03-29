<?php


namespace PerfectMoney;

use Illuminate\Support\ServiceProvider;

class ServiceProviderLaravel4 extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('jey/prefectmoney', null, __DIR__);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['perfectmoney'] = $this->app->share(function ($app) {
            return new PerfectMoneyApi();
        });
    }
}
