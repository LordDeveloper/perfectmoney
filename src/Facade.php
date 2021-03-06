<?php

namespace PerfectMoney;

use Illuminate\Support\Facades\Facade as FacadeAlias;

class Facade extends FacadeAlias
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'perfectmoney';
    }
}
