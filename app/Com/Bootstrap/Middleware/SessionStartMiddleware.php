<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Invoker;
use Illuminate\Support\Arr;
use Subvert\Framework\Contract\RequestMiddleware;
// use Subvert\Framework\

class SessionStartMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {
        

        $entity = $this->getEntity($request);

        if ($entity) {
            $entity->input($request);
        }

        $response = $next($request);

        if ($entity) {
            $entity->output($response);
        }

        return $response;
        
    }

    protected function getEntity($request)
    {
        $route = app()->make('dispatched_route');

        if (!empty($route['entity'])) {
            $sessionId = Arr::get($request->all(), 'body.token', '');
            $sessionId = $sessionId ? $sessionId : null;
            $entity = str_replace('.', '\\', $route['entity']);
            return new $entity(app('session', ['session_id' => $sessionId]));
        }

        return null;
    }

}
