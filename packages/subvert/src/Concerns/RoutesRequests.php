<?php

namespace Subvert\Framework\Concerns;


use Closure;
use Exception;
use Throwable;
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

    
    protected $filter = [];

    /**
     * The FastRoute dispatcher.
     *
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;


    public function routeGroups(array $groups)
    {
         $this->routeGroups = $groups;
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
            'uses'   => $action,
            'status' => $status,
        ];
    }

    /**
     * Run the application and send the response.
     *
     * @param  SymfonyRequest  $request
     * @return void
     */
    public function run($request)
    {
        $this->doFilter($request);

        $response = $this->dispatch($request);

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
        
    }

    public function addFilter($filter)
    {
        if (!is_array($filter)) $filter = [$filter];

        $this->filter = array_unique(array_merge($this->filter, $filter));

        return $this;
    }

    public function doFilter($request)
    {

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
