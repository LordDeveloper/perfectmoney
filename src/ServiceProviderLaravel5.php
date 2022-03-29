<?php
namespace PerfectMoney;

use Illuminate\Support\ServiceProvider;

class ServiceProviderLaravel5 extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->publishes([__DIR__.'/migrations'=> database_path('migrations')]);
        $this->publishes([__DIR__.'/config/config.php' => config_path('perfectmoney.php')]);
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'perfectmoney');
        $this->app->singleton('perfectmoney', function ($app) {
            return new PerfectMoneyApi();
        });
    }
}
