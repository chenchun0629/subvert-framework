<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;

class ClientValidationMiddleware implements RequestMiddleware, Validatable
{

    public function handle($request, Closure $next)
    {

        if (!$this->validate($request->all())) {
            return 'client errro';
        }
        
        return $next($request);
        
    }

    public function validate($data)
    {
       $routeGroups = app()->getRouteGroups();
       $client = $data['body']['client'];

       if (isset($routeGroups[$client])) {
           return true;
       }

       return false;
    }

}
