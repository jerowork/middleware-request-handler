<?php

declare(strict_types=1);

namespace Jerowork\MiddlewareDispatcher\Exception;

use Exception;

class RequestHandlerException extends Exception
{
    public static function stackExhausted() : RequestHandlerException
    {
        return new self('Middleware stack exhausted, missing final response middleware?');
    }
}
