<?php


namespace PerfectMoney;

class PerfectMoneyApi
{

    /**
     * @var Client
     */
    private static $client;

	
	/**
	 * return Client
	 */
	private static function getClient()
	{
		if(!self::$client instanceof Client)
			return self::$client = new Client;
		return  self::$client;
	}
    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        return call_user_func_array([self::getClient(), $method], $arguments);
    }

    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments = [])
    {
        return call_user_func_array([static::getClient(), $method], $arguments);
    }

}
