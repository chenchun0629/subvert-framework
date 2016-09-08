<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Rhumsaa\Uuid\Uuid;
use Subvert\Framework\Contract\RequestMiddleware;

class InitMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {
        /**
         * request unique id
         */
        app()->instance('request_uuid', Uuid::uuid1()->toString());

        $requestData = $request->all();

        /**
         * 请求来源
         */
        app()->instance('request_client', $requestData['body']['client']);

        /**
         * 请求session_id
         */
        app()->instance('request_token', $requestData['body']['token']);



        app()->instance('request_datetime', date('Y-m-d H:i:s'));
        
        return $next($request);
        
    }

}
