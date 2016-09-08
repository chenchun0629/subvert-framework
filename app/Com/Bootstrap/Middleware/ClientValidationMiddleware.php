<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use ResponseData;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;

class ClientValidationMiddleware implements RequestMiddleware, Validatable
{

    public function handle($request, Closure $next)
    {

        if (!$this->validate($request->all())) {
            app('log')->error('system.client.validation', [app('request_client')]);

            return ResponseData::set(SystemCode::SYSTEM_CLIENT_ERROR, false);
        }
        
        return $next($request);
        
    }

    public function validate($data)
    {
        $routeGroups = app()->getRouteGroups();
        $client = app('request_client');

        if (isset($routeGroups[$client])) {
            return true;
        }
  
        return false;
    }

}
