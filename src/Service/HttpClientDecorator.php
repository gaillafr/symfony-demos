<?php

declare(strict_types=1);

namespace App\Service;

use App\Helper\TestResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class HttpClientDecorator implements HttpClientInterface
{
    private static int $expectedResponseCode;

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        dump(self::$expectedResponseCode);
        exit;
        // TODO: Check for existing recorded response.
        return (new TestResponse())
            ->setContent('{"ip": "0.0.0.0"}')
        ;
    }

    /**
     * @param iterable|ResponseInterface|ResponseInterface[] $responses
     */
    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        return $this->client->stream($responses, $timeout);
    }

    public static function setExpectedResponseCode(int $code): void
    {
        self::$expectedResponseCode = $code;
    }
}
