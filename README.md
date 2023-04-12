# example-api-client
## Description
example.com API client library

## Install
Add folowing lines to your `composer.json` file:
```json 
{
  ...
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/ZeroICQ/example-api-client"
    }
  ]
  ...
}
```

Then install it:
```shell
composer require zeroicq/example-api-client
```
## Usage
In order to make requests you'll need PSR-18 compatible HTTP client. E.g. [Guzzle](https://packagist.org/packages/guzzlehttp/guzzle).

Create API client:
```php
<?php

use GuzzleHttp\Client;
use Zeroicq\ExampleApiClient\ExampleApiClient;

class Example
{
    public function test()
    {
        $api = new ExampleApiClient(new Client());
    }
}
```

Get comments:
```php
foreach ($api->getComments() as $comment) {
    ...
}
```

Update comment:
```php
$api->updateComment(new Comment(
    id: 123,
    name: 'Mr. X',
    text: 'my text'
));
```

Create comment:
```php
$api->createComment('Slim Shady', 'Hi there');
```

## Developing
Start docker dev image and run shell inside it:
```shell
make docker/exec-sh
```
Install dependencies:
```shell
composer install
```

Useful make commands:
```shell
# Run CS fixer
make code/cs 

# Run phpstan
make code/phpstan

# Run code checks.
make code/check

# Run phpunit tests
make code/test
```