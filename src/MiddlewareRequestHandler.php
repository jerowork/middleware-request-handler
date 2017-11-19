<?php declare(strict_types=1);

namespace Jerowork\MiddlewareDispatcher;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Jerowork\MiddlewareDispatcher\Exception\RequestHandlerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class MiddlewareRequestHandler implements RequestHandlerInterface
{
    /** @var \SplStack */
    private $stack;

    /**
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(array $middlewares = [])
    {
        $this->stack = new \SplStack();

        foreach ($middlewares as $middleware) {
            if (!$middleware instanceof MiddlewareInterface) {
                return;
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
