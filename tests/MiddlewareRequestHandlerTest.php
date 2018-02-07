<?php declare(strict_types=1);

namespace Jerowork\MiddlewareDispatcher\Test;

use Jerowork\MiddlewareDispatcher\Exception\RequestHandlerException;
use Jerowork\MiddlewareDispatcher\Middleware\FinalResponseMiddleware;
use Jerowork\MiddlewareDispatcher\MiddlewareRequestHandler;
use Jerowork\MiddlewareDispatcher\Test\Stub\Middleware1Stub;
use Jerowork\MiddlewareDispatcher\Test\Stub\Middleware3Stub;
use Jerowork\MiddlewareDispatcher\Test\Stub\Middleware2Stub;
use Jerowork\MiddlewareDispatcher\Test\Stub\NotImplementingMiddlewareStub;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class MiddlewareRequestHandlerTest extends TestCase
{
    public function testReturnReverseResponseOnEmptyStack()
    {
        $response = new Response();
        $handler = new MiddlewareRequestHandler([
            new FinalResponseMiddleware($response),
        ]);
        $this->assertSame($response, $handler->handle(ServerRequestFactory::fromGlobals()));
    }

    public function testThrowExceptionWhenNoReservalMiddlewareIsSet()
    {
        $this->expectException(RequestHandlerException::class);
        (new MiddlewareRequestHandler([]))->handle(ServerRequestFactory::fromGlobals());
    }

    public function testStackIsCalledInCorrectOrder()
    {
        $handler = new MiddlewareRequestHandler([
            new Middleware1Stub(),
            new Middleware2Stub(),
            new Middleware3Stub(),
            new FinalResponseMiddleware(new Response()),
        ]);

        $response = $handler->handle(ServerRequestFactory::fromGlobals());

        $this->assertSame('321', (string)$response->getBody());
    }

    public function testMiddlewareNotImplementingInterfaceIsIgnored()
    {
        $handler = new MiddlewareRequestHandler([
            new Middleware1Stub(),
            new NotImplementingMiddlewareStub(),
            new Middleware2Stub(),
            new Middleware3Stub(),
            new FinalResponseMiddleware(new Response()),
        ]);

        $response = $handler->handle(ServerRequestFactory::fromGlobals());

        $this->assertSame('321', (string)$response->getBody());
    }
}
