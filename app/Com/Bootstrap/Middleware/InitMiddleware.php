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
        app()->instance('request_uuid', Uuid::uuid4()->toString());

        $requestData = $request->all();

        /**
         * 请求来源
         */
        $requestClient = isset($requestData['body']) && isset($requestData['body']['client']) ?  $requestData['body']['client'] : '';
        app()->instance('request_client', $requestClient);


        /**
         * 请求session_id
         */
        $requestToken = isset($requestData['body']) && isset($requestData['body']['token']) ?  $requestData['body']['token'] : '';
        app()->instance('request_token', $requestToken);

        /**
         * 请求时间
         */
        app()->instance('request_datetime', date('Y-m-d H:i:s'));
        

        return $next($request);
        
    }

}
