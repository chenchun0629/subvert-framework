<?php

namespace Subvert\Framework\Concerns;

use Invoker;
use Closure;
use Exception;
use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Pipeline;
use Laravel\Lumen\Routing\Closure as RoutingClosure;
use Illuminate\Http\Exception\HttpResponseException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Laravel\Lumen\Routing\Controller as LumenController;
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


    /**
     * The FastRoute dispatcher.
     *
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;


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
    public function addRoute($api, $version, $action, $status)
    {
        if (!isset($this->routes[$api])) $this->routes[$api] = [];
        $this->routes[$api][$version] = [
            'action'   => $action,
            'status' => $status,
        ];
    }

    public function dispatchRoute($api, $version)
    {
        if (!isset($this->routes[$api])) {
            throw new MethodNotAllowedHttpException($api . $version);
            
        }

        if (isset($this->routes[$api][$version])) {
            if ($this->routes[$api][$version]['status']) {
                return $this->routes[$api][$version]['action'];
            }
        }

        if (isset($this->routes[$api]['*'])) {
            if ($this->routes[$api]['*']['status']) {
                return $this->routes[$api]['*']['action'];
            }
        }

        throw new MethodNotAllowedHttpException($api . $version);
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
                $action = $this->dispatchRoute($api, $version);
                return $this->invoke($action, $data);
            });
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
     * Set the FastRoute dispatcher instance.
     *
     * @param  \FastRoute\Dispatcher  $dispatcher
     * @return void
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
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
