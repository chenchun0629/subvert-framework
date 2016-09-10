<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use ResponseData;
use Illuminate\Support\Arr;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class LoadRoutesMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {
        $routePath = app()->getRouteGroups(app('request_client'));

        if (empty($routePath)) {
            app('log')->error('system.route.undefined', [app('request_client')]);

            return app()->prepareResponse(
                ResponseData::set(SystemCode::SYSTEM_UNDEFINED_ROUTE, false)
            );
        }

        $routePath = app()->getConfigurationPath($routePath);

        if (empty($routePath)) {
            app('log')->error('system.route.nofile', [app('request_client')]);

            return app()->prepareResponse(
                ResponseData::set(SystemCode::SYSTEM_ROUTE_PATH_ERROR, false)
            );
        }
        
        $routes = require_once $routePath;

        app()->instance('routes', $routes);

        foreach ($routes as $route) {
            app()->addRoute($route);
        }

        try {
            $dispatchedRoute = app()->dispatchRoute(
                Arr::get($request->all(), 'call.api'),
                Arr::get($request->all(), 'call.version')
            );
        } catch (MethodNotAllowedHttpException $ex) {
            app('log')->error('system.route.notfound', [app('request_client'), $request->all()]);

            return app()->prepareResponse(
                ResponseData::set(SystemCode::SYSTEM_NOT_FOUND_ERROR, false)
            );
        }

        return $next($request);
        
    }

}
