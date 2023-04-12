<?php

namespace Zeroicq\ExampleApiClient\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Zeroicq\ExampleApiClient\DTO\Comment;
use Zeroicq\ExampleApiClient\ExampleApiClient;
use Zeroicq\ExampleApiClient\Exception\ApiException;
use Zeroicq\ExampleApiClient\Util\JsonHelper;

class ExampleApiClientTest extends TestCase
{
    public function testList(): void
    {
        $client = $this->createMockClientWithResponses([
            new ApiResponse(
                200,
                JsonHelper::json_encode([
                    [
                        'id' => 1,
                        'name' => 'ivan',
                        'text' => 'hello there',
                    ],
                    [
                        'id' => 2,
                        'name' => 'andrew',
                        'text' => 'test comment1',
                    ]])
            ),
        ]);

        $api = new ExampleApiClient($client);

        /** @var Comment[] $responses */
        $responses = iterator_to_array($api->getComments());

        $this->assertSame(2, count($responses));
        $this->assertSame(1, $responses[0]->id);
        $this->assertSame('ivan', $responses[0]->name);
        $this->assertSame('hello there', $responses[0]->text);

        $this->assertSame(2, $responses[1]->id);
        $this->assertSame('andrew', $responses[1]->name);
        $this->assertSame('test comment1', $responses[1]->text);
    }

    public function testListRequest(): void
    {
        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) { // todo: refactor messy callback
                return
                    '/comments' == $request->getUri()->getPath()
                    && 'GET' === $request->getMethod();
            }))
            ->willReturn(new ApiResponse(
                200,
                JsonHelper::json_encode([
                    [
                        'id' => 1,
                        'name' => 'ivan',
                        'text' => 'hello there',
                    ],
                ])
            ))
        ;

        $api = new ExampleApiClient($clientMock);
        iterator_to_array($api->getComments());
    }

    public function testUpdateRequest(): void
    {
        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) { // todo: refactor messy callback
                return
                    0 === strcmp('{"name":"my name","text":"my text"}', $request->getBody()->getContents())
                    && '/comment/12' == $request->getUri()->getPath()
                    && 'PUT' === $request->getMethod();
            }))
            ->willReturn(new ApiResponse(200))
        ;

        $api = new ExampleApiClient($clientMock);
        $api->updateComment(
            new Comment(12, 'my name', 'my text')
        );
    }

    public function testUpdate404(): void
    {
        $client = $this->createMockClientWithResponses([
            new ApiResponse(404),
        ]);
        $api = new ExampleApiClient($client);

        $this->expectException(ApiException::class);
        $api->updateComment(new Comment(1, 'name', 'text'));
    }

    public function testCreateRequest(): void
    {
        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (Request $request) { // todo: refactor messy callback
                return
                    0 === strcmp('{"name":"my name","text":"my text"}', $request->getBody()->getContents())
                    && '/comment' == $request->getUri()->getPath()
                    && 'POST' === $request->getMethod();
            }))
            ->willReturn(new ApiResponse(200))
        ;

        $api = new ExampleApiClient($clientMock);
        $api->createComment('my name', 'my text');
    }

    public function test503Error(): void
    {
        $client = $this->createMockClientWithResponses([
            new ApiResponse(503),
        ]);

        $api = new ExampleApiClient($client);
        $this->expectException(ApiException::class);
        iterator_to_array($api->getComments());
    }

    /**
     * @param array<Response> $responses
     */
    protected function createMockClientWithResponses(array $responses): Client
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }
}
