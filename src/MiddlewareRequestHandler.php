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
    public function __construct(array $middlewares)
    {
        $this->stack = new \SplStack();

        foreach ($middlewares as $middleware) {
            if (!$middleware instanceof MiddlewareInterface) {
                continue;
            }

            $this->stack->push($middleware);
        }
    }

    /**
     * @inheritDoc
     *
     * @throws RequestHandlerException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->stack->isEmpty()) {
            throw new RequestHandlerException('Middleware stack exhausted, missing final response middleware?');
        }

        return $this->stack->shift()->process($request, $this);
    }
}
