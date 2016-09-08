<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use ResponseData;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;

class LoadRoutesMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {
        $routePath = app()->getRouteGroups(app('request_client'));

        if (empty($routePath)) {
            app('log')->error('system.route.undefined', [app('request_client')]);

            return ResponseData::set(SystemCode::SYSTEM_UNDEFINED_ROUTE, false);
        }

        $routePath = app()->getConfigurationPath($routePath);

        if (empty($routePath)) {
            app('log')->error('system.route.nofile', [app('request_client')]);

            return ResponseData::set(SystemCode::SYSTEM_ROUTE_PATH_ERROR, false);
        }
        
        $routes = require_once $routePath;

        app()->instance('routes', $routes);

        foreach ($routes as $route) {
            app()->addRoute($route);
        }

        return $next($request);
        
    }

}
