<?php


namespace PerfectMoney;

class PerfectMoneyApi
{

    /**
     * @var Client
     */
    private static $client;

    public function __construct()
    {
        self::$client = new Client;
    }

    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        return call_user_func_array([self::$client, $method], $arguments);
    }

    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments = [])
    {
        return call_user_func_array([self::$client, $method], $arguments);
    }

}
