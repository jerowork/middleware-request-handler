<?php declare(strict_types=1);

namespace Jerowork\MiddlewareDispatcher\Test\Stub;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class NotImplementingMiddlewareStub
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $response->getBody()->write('4');
        return $response;
    }
}
