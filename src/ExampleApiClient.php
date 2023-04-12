<?php

namespace Zeroicq\ExampleApiClient;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Zeroicq\ExampleApiClient\DTO\Comment;
use Zeroicq\ExampleApiClient\Exception\ApiException;
use Zeroicq\ExampleApiClient\Exception\ValidationException;
use Zeroicq\ExampleApiClient\Util\JsonHelper;

class ExampleApiClient implements ExampleApiClientInterface
{
    protected const HTTP_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    public function __construct(
        protected ClientInterface $httpClient,
        protected readonly string $baseURL = 'http://example.com'
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function updateComment(Comment $comment): void
    {
        $body = $this->encodeBody([
            'name' => $comment->name,
            'text' => $comment->text,
        ]);
        $this->sendRequest('PUT', "/comment/{$comment->id}", $body);
    }

    /**
     * {@inheritDoc}
     */
    public function createComment(Comment $comment): void
    {
        $this->validateNoId($comment);
        $body = $this->encodeBody([
            'name' => $comment->name,
            'text' => $comment->text,
        ]);
        $this->sendRequest('POST', '/comment', $body);
    }

    /**
     * {@inheritDoc}
     */
    public function getComments(): \Traversable
    {
        // request will be executed on generator access
        // $api->getComments() - request not sent
        // iterator_to_array($api->getComments()) - request sent
        $response = $this->sendRequest('GET', '/comments');
        $body = $this->decodeBody($response);
        foreach ($body as $element) {
            yield new Comment($element['id'], $element['name'], $element['text']);
        }
    }

    /**
     * @param string  $uri  must start with '/', e.g. '/comments'
     * @param ?string $body
     *
     * @throws ApiException
     * @throws ClientExceptionInterface
     */
    protected function sendRequest(string $method, string $uri, ?string $body = null): ResponseInterface
    {
        $request = new Request(
            $method,
            $this->baseURL.$uri,
            self::HTTP_HEADERS,
            $body
        );

        $response = $this->httpClient->sendRequest($request);
        if (200 !== $response->getStatusCode()) {
            throw new ApiException("Response code {$response->getStatusCode()}");
        }

        return $response;
    }

    /**
     * @throws ApiException
     */
    protected function encodeBody(mixed $obj, bool $includeId = true): string
    {
        try {
            return JsonHelper::json_encode($obj);
        } catch (\JsonException $ex) {
            throw new ApiException('Encoding request body error', previous: $ex);
        }
    }

    /**
     * @throws ApiException
     */
    protected function decodeBody(ResponseInterface $response): mixed
    {
        try {
            return JsonHelper::json_decode($response->getBody());
        } catch (\JsonException $ex) {
            throw new ApiException('Decoding response error', previous: $ex);
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateNoId(Comment $comment): void
    {
        if (is_null($comment->id)) {
            return;
        }

        throw new ValidationException('id must be bull');
    }
}
