<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Exception;
use ResponseData;
use Illuminate\Support\Arr;
use Store\Code\System\SystemCode;
use Com\Foundation\SessionProcesser;
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
            try {
                $responseData['response'] = $entity->output($responseData['response']);
            } catch(\Exception $ex) {
                app('log')->error('system.data.process', [$ex]);

                return app()->prepareResponse($ex->getMessage());
            }
            $response->setContent($responseData);
        }

        return $response;
        
    }

    protected function getEntity($request)
    {
        $route = app()->make('dispatched_route');

        $servletConfig = $this->getServLetConfig($route['action']);

        if (empty($servletConfig)) {
            return null;
        }

        $sessionId = app('request_token') ?: null;

        return new SessionProcesser(app('session', ['session_id' => $sessionId]), $servletConfig);
    }

    protected function getServLetConfig($action)
    {
        
        $key = 'servlet/' . app('request_client');
        app()->configure($key);
        $config = config($key);

        if (isset($config[$action])) {
            return $config[$action];
        }

        $action = explode('.', $action);
        do {
            $configKey = implode('.', $action) . '.*';

            if (isset($config[$configKey])) {
                return $config[$configKey];
            }

            array_pop($action);

        } while (!empty($action));

        return [];
    }

    protected function callDataToJson($requestData)
    {
        $requestData['call']['data'] = json_decode($requestData['call']['data'], true);
        return $requestData;
    }

}
