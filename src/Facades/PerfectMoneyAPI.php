<?php

namespace Jey\PerfectMoney\Facades;

use Illuminate\Support\Facades\Facade;


class PerfectMoneyAPI extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'perfectmoney';
    }
}