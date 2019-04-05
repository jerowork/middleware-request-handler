<?php declare(strict_types=1);

namespace Jerowork\MiddlewareDispatcher\Exception;

class RequestHandlerException extends \LogicException
{
    public static function stackExhausted() : RequestHandlerException
    {
        return new self('Middleware stack exhausted, missing final response middleware?');
    }
}
