<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;

class LoadRoutesMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {
        $routePath = app()->getRouteGroups(app('request_client'));
        $routePath = app()->getConfigurationPath($routePath);

        if (empty($routePath)) {
            return 'load route error';
        }
        
        $routes = require_once $routePath;

        app()->instance('routes', $routes);

        foreach ($routes as $route) {
            app()->addRoute($route);
        }

        return $next($request);
        
    }

}
