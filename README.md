# Middleware request handler
Minimalist PSR-15 middleware based request handler (dispatcher).

## Installation
Install via Composer: 
```
$ composer require jerowork/middleware-request-handler
```

## Usage
Use with a PSR-7 request implementation, like [Zend Diactoros](https://github.com/zendframework/zend-diactoros).

### Example
```php
use Jerowork\MiddlewareDispatcher\Middleware\FinalResponseMiddleware;
use Jerowork\MiddlewareDispatcher\MiddlewareRequestHandler;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

// Setup a list of PSR-15 middlewares
$middlewares = [
    new SomeMiddleware(),
    new AnotherMiddleware(),
    new FinalResponseMiddleware(new Response()), // reversal order middleware
];

// Handle a PSR-7 server request to response by the request handler (PSR-15)
$response = (new MiddlewareRequestHandler($middlewares))
    ->handle(ServerRequestFactory::fromGlobals());

// Output PSR-7 response with a response emitter implementation of your choice
(new Response\SapiEmitter())->emit($response);
```