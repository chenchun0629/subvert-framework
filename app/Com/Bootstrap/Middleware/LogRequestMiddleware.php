<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Invoker;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;

class LogRequestMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {

        $response = $next($request);

        $log = [
            'stack' => Invoker::$callStack['children'],
        ];

        app('log')->info('statistics.request', $log);

        return $response;
        
    }

}
