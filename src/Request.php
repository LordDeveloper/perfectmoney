<?php

namespace JEY\PerfectMoney;

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class Request
{
    protected static string $baseURL = 'https://perfectmoney.com/acct/';

    /**
     * @param $uri
     * @param array $data
     * @return array
     */
    public static function fetch($uri, array $data = []): array
    {
        $query = array_merge($data, [
            'AccountID' => config('perfectmoney.account_id'),
            'PassPhrase' => config('perfectmoney.password'),
        ]);

        $client = new Client([
            'base_uri' => static::$baseURL,
        ]);

        return static::inputs($client->getAsync(trim($uri, '/') .'.asp', compact('query'))->then(
            [static::class, 'onFulfilled'],
            [static::class, 'onRejected']
        )->wait());

    }

    /**
     * @param ResponseInterface $response
     * @return string
     */
    final public static function onFulfilled(ResponseInterface $response): string
    {
        return $response->getBody()
            ->getContents();
    }

    /**
     * @param Throwable $exception
     * @internal
     * @return string
     */
    final public static function onRejected(Throwable $exception): string
    {
        return method_exists($exception, 'hasResponse')
            ? (
                $exception->hasResponse()
                    ? $exception->getResponse()
                        ->getBody()
                        ->getContents()
                    : $exception->getMessage()
            ) : $exception->getMessage();
    }

    /**
     * @param string $contents
     * @return array
     *@internal
     */
    private static function inputs(string $contents): array
    {
        $response = (function () use ($contents) {
            $response = [
                'success' => true,
                'error' => null,
                'response' => null,
            ];

            if(! static::isHTML($contents)) {
                if(str_contains($contents, 'ERROR')) {
                    return array_merge($response, [
                        'success' => false,
                        'error' => $contents,
                    ]);
                }

                return array_merge($response, [
                    'response' => $contents
                ]);
            }

            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($contents);
            libxml_use_internal_errors(false);
            $xpath = new DOMXPath($dom);
            $inputs = $xpath->query('//input');

            if($inputs->count() > 0) {
                foreach ($inputs as $input) {
                    [$name, $value] = [$input->getAttribute('name'), $input->getAttribute('value')];

                    if($name === 'ERROR') {
                        $response['error'] = $value;
                    } else {
                        $response['response'][$name] = $value;
                    }
                }

                return $response;
            }

            return array_merge($response, [
                'success' => false,
                'error' => 'NO_RESPONSE',
            ]);
        })();

        return array_filter($response, fn ($item) => ! is_null($item));
    }

    /**
     * @param $contents
     * @return bool
     */
    private static function isHTML($contents): bool
    {
        return $contents !== strip_tags($contents);
    }
}
