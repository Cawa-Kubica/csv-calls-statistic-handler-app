<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait GuzzleRequest
 * @package App\Traits
 */
trait GuzzleRequest
{
    /**
     * @var Client
     */
    protected Client $http;

    /**
     * @var ResponseInterface
     */
    protected ResponseInterface $lastResponse;

    /**
     * @var string
     */
    protected string $lastResponseBody;

    /**
     * @var array|string[]
     */
    private array $availableMethodsType = [
        'GET',
        'POST',
        'PUT'
    ];

    protected function guzzleInitClient()
    {
        $this->http = new Client([
            'http_errors' => false,
            'timeout' => 30,
        ]);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array|null $params
     * @return mixed
     */
    public function requestJSONData(string $method, string $uri, ?array $params = [])
    {
        try {
            $response_body = [];

            if (in_array($method, $this->availableMethodsType)) {
                $res = $this->http->request($method, $uri, $params);

                $this->lastResponse = $res;
                $response_body = $res->getBody()->getContents();
                $this->lastResponseBody = $response_body;
            }

        } catch (GuzzleException $exception) {
            handleException($exception);
        }

        return @json_decode($response_body, true);
    }

}