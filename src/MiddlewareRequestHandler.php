<?php declare(strict_types=1);

namespace Jerowork\MiddlewareDispatcher;

use Jerowork\MiddlewareDispatcher\Exception\RequestHandlerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareRequestHandler implements RequestHandlerInterface
{
    /**
     * Middleware stack.
     *
     * @var \SplStack
     */
    private $stack;

    /**
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(array $middlewares = [])
    {
        $this->stack = new \SplStack();

        foreach ($middlewares as $middleware) {
            if (!$middleware instanceof MiddlewareInterface) {
                continue;
            }

            $this->addMiddleware($middleware);
        }
    }

    /**
     * Add middleware to stack.
     */
    public function addMiddleware(MiddlewareInterface ...$middlewares) : self
    {
        foreach ($middlewares as $middleware) {
            $this->stack->push($middleware);
        }

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws RequestHandlerException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->stack->isEmpty()) {
            throw RequestHandlerException::stackExhausted();
        }

        return $this->stack->shift()->process($request, $this);
    }
}
