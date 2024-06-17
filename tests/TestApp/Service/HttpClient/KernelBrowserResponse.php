<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Service\HttpClient;

use Override;
use RuntimeException;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\RedirectionException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class KernelBrowserResponse implements ResponseInterface
{
    /**
     * @param string[][] $headers
     */
    public function __construct(
        private readonly string $url,
        private readonly int $statusCode,
        private readonly array $headers,
        private readonly string $content
    ) {
    }

    #[Override]
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @throws RedirectionExceptionInterface On a 3xx when $throw is true and the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx when $throw is true
     * @throws ServerExceptionInterface      On a 5xx when $throw is true
     */
    #[Override]
    public function getHeaders(bool $throw = true): array
    {
        if ($throw) {
            $this->throw();
        }

        return $this->headers;
    }

    #[Override]
    public function getContent(bool $throw = true): string
    {
        if ($throw) {
            $this->throw();
        }

        return $this->content;
    }

    #[Override]
    public function toArray(bool $throw = true): array
    {
        throw new RuntimeException('Not implemented');
    }

    #[Override]
    public function cancel(): void
    {
        throw new RuntimeException('Not implemented');
    }

    #[Override]
    public function getInfo(string $type = null): mixed
    {
        return match ($type) {
            'url' => $this->url,
            'http_code' => $this->statusCode,
            'response_headers' => array_map(
                fn($name, $values): string => sprintf('%s: %s', $name, implode(', ', $values)),
                array_keys($this->headers),
                $this->headers
            ),
            'response_content' => $this->content,
            default => throw new RuntimeException('Unsupported type: ' . $type)
        };
    }

    public function throw(): void
    {
        match (true) {
            $this->statusCode >= 300 && $this->statusCode < 400 => throw new RedirectionException($this),
            $this->statusCode >= 400 && $this->statusCode < 500 => throw new ClientException($this),
            $this->statusCode >= 500 && $this->statusCode < 600 => throw new ServerException($this),
            default => null
        };
    }
}
