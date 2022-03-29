<?php

namespace JEY\PerfectMoney\Providers;

use Illuminate\Support\ServiceProvider;
use JEY\PerfectMoney\PerfectMoneyAPI;

class PerfectMoneyServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/perfectmoney.php', 'perfectmoney',
        );

        $this->publishes([
            __DIR__ . '/../../config/perfectmoney.php' => config('perfectmoney'),
        ], 'perfectmoney-config');

        $this->app->singleton(
            'perfectmoney', fn ($app) => new PerfectMoneyAPI()
        );

        $this->app->singleton(
            PerfectMoneyAPI::class, fn () => new PerfectMoneyAPI()
        );
    }
}
