<?php

namespace Subvert\Framework\Concerns;

use Invoker;
use Closure;
use Exception;
use Throwable;
use ResponseData;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Pipeline;
use Laravel\Lumen\Routing\Closure as RoutingClosure;
use Illuminate\Http\Exception\HttpResponseException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Laravel\Lumen\Routing\Controller as LumenController;
use Subvert\Framework\Foundation\Response\FrameworkCode;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait RoutesRequests
{
    /**
     * All of the routes waiting to be registered.
     *
     * @var array
     */
    protected $routes = [];

    /**
     * routes lists
     * 
     * @var array
     */
    protected $routeGroups = [];

    
    /**
     * All of the global middleware for the application.
     *
     * @var array
     */
    protected $middleware = [];




    public function routeGroups(array $groups)
    {
         if (! is_array($groups)) {
             $groups = [$groups];
         }

         $this->routeGroups = array_unique(array_merge($this->routeGroups, $groups));

         return $this;
    }

    public function getRouteGroups($client = null)
    {
        if (empty($client)) {
            return $this->routeGroups;
        }

        return $this->routeGroups[$client];
    }

    /**
     * Add a route to the collection.
     *
     * @param  array|string  $method
     * @param  string  $uri
     * @param  mixed  $action
     * @return void
     */
    public function addRoute($route)
    {
        list($api, $action, $version, $status) = $this->parseRoute($route);
        
        if (!isset($this->routes[$api])) $this->routes[$api] = [];
        $this->routes[$api][$version] = [
            'action' => $action,
            'status' => $status,
        ];
    }

    public function parseRoute($route)
    {
        $action  = $route['action'];
        $api     = isset($route['api']) ? $route['api'] : $this->actionToApi($action);
        $version = isset($route['version']) ? $route['version'] : '*'; 
        $status  = isset($route['status']) ? $route['status'] : 'enable';

        return [
            $api,
            $action,
            $version,
            $status,
        ];
    }

    protected function actionToApi($action)
    {
        $action = explode('.', $action);
        array_shift($action);
        return strtolower(implode('.', $action));
    }

    public function dispatchRoute($api, $version)
    {

        if ($this->bound('dispatched_route')) {
            return $this->make('dispatched_route');
        }

        if (!isset($this->routes[$api])) {
            throw new MethodNotAllowedHttpException([], $api . $version);
            
        }

        if (isset($this->routes[$api][$version])) {
            if ($this->routes[$api][$version]['status']) {

                $this->instance('dispatched_route', $this->routes[$api][$version]);

                return $this->routes[$api][$version];
            }
        }

        if (isset($this->routes[$api]['*'])) {
            if ($this->routes[$api]['*']['status']) {

                $this->instance('dispatched_route', $this->routes[$api]['*']);

                return $this->routes[$api]['*'];
            }
        }

        throw new MethodNotAllowedHttpException([], $api . $version);
    }

    /**
     * Run the application and send the response.
     *
     * @param  SymfonyRequest  $request
     * @return void
     */
    public function run($request = null)
    {

        $request = empty($request) ? app('request') : $request;

        $response = $this->dispatch($request);

        $response = $this->prepareResponse($response);

        if ($response instanceof SymfonyResponse) {
            $response->send();
        } else {
            echo (string) $response;
        }
    }

    /**
     * Dispatch the incoming request.
     *
     * @param  SymfonyRequest  $request
     * @return Response
     */
    public function dispatch($request)
    {

        try {
            return $this->sendThroughPipeline($this->middleware, function () use($request) {
                list($api, $version, $data) = $this->parseIncomingRequest($request);
                $route = $this->dispatchRoute($api, $version);
                $response = $this->invoke($route['action'], $data);
                if (!($response instanceof ResponseData)) {
                    $response = ResponseData::set(FrameworkCode::SYSTEM_SUCCESS ,$response);
                }
                return $this->prepareResponse($response);
            });
        } catch(MethodNotAllowedHttpException $e) {
            return $this->prepareResponse(
                ResponseData::set(FrameworkCode::SYSTEM_NOT_FOUND_ROUTE, false)
            );
        } catch (Exception $e) {
            return $this->sendExceptionToHandler($e);
        } catch (Throwable $e) {
            return $this->sendExceptionToHandler($e);
        }
        
    }

    public function parseIncomingRequest($request)
    {
        return [
            Arr::get($request->all(), 'call.api'),
            Arr::get($request->all(), 'call.api_version'),
            Arr::get($request->all(), 'call.data'),
        ];
    }

    public function invoke($action, $data)
    {
        return Invoker::execute($action, $data);
    }

    /**
     * Add new middleware to the application.
     *
     * @param  Closure|array  $middleware
     * @return $this
     */
    public function middleware($middleware)
    {
        if (! is_array($middleware)) {
            $middleware = [$middleware];
        }

        $this->middleware = array_unique(array_merge($this->middleware, $middleware));

        return $this;
    }

    protected function sendThroughPipeline(array $middleware, Closure $then)
    {
        $shouldSkipMiddleware = $this->bound('middleware.disable') &&
                                        $this->make('middleware.disable') === true;

        if (count($middleware) > 0 && ! $shouldSkipMiddleware) {
            return (new Pipeline($this))
                ->send($this->make('request'))
                ->through($middleware)
                ->then($then);
        }

        return $then();
    }



    /**
     * Prepare the response for sending.
     *
     * @param  mixed  $response
     * @return Response
     */
    public function prepareResponse($response)
    {
        if ($response instanceof PsrResponseInterface) {
            $response = (new HttpFoundationFactory)->createResponse($response);
        } elseif (! $response instanceof SymfonyResponse) {
            $response = new Response($response);
        } elseif ($response instanceof BinaryFileResponse) {
            $response = $response->prepare(Request::capture());
        }

        return $response;
    }


    /**
     * Get the raw routes for the application.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
