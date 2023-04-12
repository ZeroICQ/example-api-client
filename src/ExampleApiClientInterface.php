<?php

namespace Zeroicq\ExampleApiClient;

use Psr\Http\Client\ClientExceptionInterface;
use Zeroicq\ExampleApiClient\DTO\Comment;
use Zeroicq\ExampleApiClient\Exception\ApiException;

interface ExampleApiClientInterface
{
    /**
     * Get list of comments.
     *
     * @return \Traversable<Comment>
     *
     * @throws ApiException
     * @throws ClientExceptionInterface
     */
    public function getComments(): \Traversable;

    /**
     * Create new comment.
     *
     * @throws ApiException
     * @throws ClientExceptionInterface
     */
    public function createComment(string $name, string $text): void;

    /**
     * Update existing comment.
     *
     * @return mixed
     *
     * @throws ApiException
     * @throws ClientExceptionInterface
     */
    public function updateComment(Comment $comment);
}
