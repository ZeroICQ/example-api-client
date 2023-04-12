<?php

namespace Zeroicq\ExampleApiClient\Util;

class JsonHelper
{
    /**
     * json_encode wrapper that throws exception on error.
     *
     * @throws \JsonException
     */
    public static function json_encode(mixed $object): string
    {
        // @phpstan-ignore-next-line
        return json_encode($object, true, JSON_THROW_ON_ERROR);
    }

    /**
     * json_decode wrapper that throws exception on error.
     *
     * @throws \JsonException
     */
    public static function json_decode(string $json): mixed
    {
        return json_decode($json, true, JSON_THROW_ON_ERROR);
    }
}
