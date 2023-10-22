<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Service\HttpClient;

use Dontdrinkandroot\Common\Asserted;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class KernelBrowserHttpClient implements HttpClientInterface
{
    public function __construct(private readonly KernelBrowser $kernelBrowser)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $this->kernelBrowser->request(
            $method,
            $url,
            [],
            [],
            $this->transformRequestHeaders($options['headers']),
            $options['body'] ?? null
        );

        $response = $this->kernelBrowser->getResponse();
        return new KernelBrowserResponse(
            url: $url,
            statusCode: $response->getStatusCode(),
            headers: $this->transformResponseHeaders($response),
            content: Asserted::string($response->getContent()),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function withOptions(array $options): static
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @param array<string, string> $headers
     * @return array<string, string>
     */
    private function transformRequestHeaders(array $headers): array
    {
        $transformedHeaders = [];
        foreach ($headers as $name => $value) {
            $transformedName = strtoupper(str_replace('-', '_', $name));
            if (!in_array($transformedName, ['CONTENT_TYPE', 'CONTENT_LENGTH'])) {
                $transformedName = 'HTTP_' . $transformedName;
            }

            $transformedHeaders[$transformedName] = $value;
        }

        return $transformedHeaders;
    }

    private function transformResponseHeaders(Response $response): array
    {
        $headers = [];
        foreach ($response->headers->all() as $name => $values) {
            $headers[is_string($name) ? strtolower($name) : $name] = $values;
        }

        return $headers;
    }
}
