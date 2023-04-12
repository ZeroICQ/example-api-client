<?php

namespace Zeroicq\ExampleApiClient\Tests;

use GuzzleHttp\Psr7\Response;

class ApiResponse extends Response
{
    protected const RESPONSE_HEADERS = [
        'Content-Type' => 'application/json',
    ];

    public function __construct(int $status = 200, $body = null)
    {
        parent::__construct($status, self::RESPONSE_HEADERS, $body);
    }
}
