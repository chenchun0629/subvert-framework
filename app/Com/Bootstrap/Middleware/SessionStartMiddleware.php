<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Rhumsaa\Uuid\Uuid;
use Subvert\Framework\Contract\RequestMiddleware;

class SessionStartMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {
        
        
        $response = $next($request);


        return $response;
        
    }

}
