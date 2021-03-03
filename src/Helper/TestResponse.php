<?php

declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TestResponse implements ResponseInterface
{
    private string $content;
    private array $headers = [];
    private int $statusCode = Response::HTTP_OK;

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getContent(bool $throw = true): string
    {
        return $this->content;
    }

    public function setContent(string $content): TestResponse
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return array<array<string>>
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getHeaders(bool $throw = true): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): TestResponse
    {
        $this->headers = $headers;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): TestResponse
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray(bool $throw = true): array
    {
        return \json_decode($this->content, true);
    }

    public function cancel(): void
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getInfo(string $type = null): ?array
    {
        return null;
    }
}
