<?php declare(strict_types=1);

namespace Jerowork\MiddlewareDispatcher\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class FinalResponseMiddleware implements MiddlewareInterface
{
    /** @var ResponseInterface */
    private $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Return injected response to reverse order of middleware stack
        return $this->response;
    }
}
