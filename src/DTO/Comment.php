<?php

namespace Zeroicq\ExampleApiClient\DTO;

readonly class Comment
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $text
    ) {
    }
}
