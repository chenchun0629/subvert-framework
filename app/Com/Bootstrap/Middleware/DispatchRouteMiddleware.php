<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;

class DispatchRouteMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {

        
        
        return $next($request);
        
    }

}
