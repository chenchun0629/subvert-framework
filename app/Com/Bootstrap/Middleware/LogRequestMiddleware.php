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

        $start = microtime(true);

        $response = $next($request);

        $end = microtime(true);
        $use = number_format(($end - $start), 5);

        $log = [
            'request_data' => $request->all(),
            'response_data' => $response,
            'stack' => Invoker::$callStack,
            'use'   => $use,
        ];

        app('log')->info('statistics.request', $log);

        return $response;
        
    }

}
