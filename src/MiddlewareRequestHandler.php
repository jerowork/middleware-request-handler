<?php declare(strict_types=1);

namespace Jerowork\MiddlewareDispatcher;

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
     * Final response to reverse middleware stack flow.
     *
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param MiddlewareInterface[] $middlewares
     * @param ResponseInterface $response
     */
    public function __construct(array $middlewares, ResponseInterface $response)
    {
        $this->stack = new \SplStack();

        foreach ($middlewares as $middleware) {
            // ignore middleware not implementing psr interface
            if (!$middleware instanceof MiddlewareInterface) {
                continue;
            }

            $this->stack->push($middleware);
        }

        $this->response = $response;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->stack->isEmpty()) {
            // Stack empty, return final reversal response
            return $this->response;
        }

        // Process next middleware
        return $this->stack->shift()->process($request, $this);
    }
}
