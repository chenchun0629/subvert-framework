<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Rhumsaa\Uuid\Uuid;
use Subvert\Framework\Contract\RequestMiddleware;

class RuidMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {
        /**
         * request unique id
         */
        app()->instance('ruid', Uuid::uuid1()->toString());

        $requestData = $request->all();

        /**
         * 请求来源
         */
        app()->instance('request_client', $requestData['body']['client']);
        
        return $next($request);
        
    }

}
