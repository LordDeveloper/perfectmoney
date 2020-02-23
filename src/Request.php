<?php


namespace PerfectMoney;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Class Request
 * @package Jey\PerfectMoney
 */
class Request
{

    const PM_URL = 'https://perfectmoney.com/acct/%s.asp';

    /**
     * @var array
     */
    private $authenticate;

    public function __construct()
    {
        $this->authenticate['AccountID'] = config('perfectmoney.account_id');
        $this->authenticate['PassPhrase'] = config('perfectmoney.password');
    }
    /**
     * @param $action
     * @param array $query
     * @return mixed
     */
    public function get($action, $query = [])
    {
        $client = new Client();
        $query = urldecode(http_build_query(array_merge($this->authenticate, $query)));
        $promise = $client->getAsync(sprintf(static::PM_URL, $action), compact('query'))->then(
            [$this, 'onFulfilled'],
            [$this, 'onRejected']
        );
        return $promise->wait();
    }


    /**
     * @param ResponseInterface $response
     * @return string
     */
    private function onFulfilled(ResponseInterface $response) {
        return $response->getBody()->getContents();
    }

    /**
     * @param Throwable $exception
     * @return mixed
     */
    private function onRejected(Throwable $exception)
    {
        return $exception->getResponse()->getBody()->getContents();
    }

    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        return call_user_func_array([$this, $method], $arguments);
    }

    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments = [])
    {
        return call_user_func_array([new static, $method], $arguments);
    }

}
