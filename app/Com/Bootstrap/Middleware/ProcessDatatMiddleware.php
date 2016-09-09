<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Exception;
use ResponseData;
use Illuminate\Support\Arr;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\RequestMiddleware;
use Symfony\Component\HttpFoundation\ParameterBag;

class ProcessDatatMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {
        $requestData = $request->request->all();

        $requestData = $this->callDataToJson($requestData);

        $entity = $this->getEntity($request);

        if ($entity) {
            try {
                $requestData['call']['data'] = $entity->input($requestData['call']['data']);
            } catch(\Exception $ex) {
                app('log')->error('system.data.process', [$ex]);

                return app()->prepareResponse($ex->getMessage());
            }
        }

        $request->request = new ParameterBag($requestData);

        $response = $next($request);

        $responseData = $response->getOriginalContent();
        if ($entity && $responseData['code'] === 0) {
            $responseData['response'] = $entity->output($responseData['response']);
            $response->setContent($responseData);
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

    protected function callDataToJson($requestData)
    {
        $requestData['call']['data'] = json_decode($requestData['call']['data'], true);
        return $requestData;
    }

}
