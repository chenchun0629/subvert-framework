<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use ResponseData;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;

class CrossOriginMiddleware implements RequestMiddleware
{

    protected $allowDomain = [];

    public function handle($request, Closure $next)
    {

        if ($request->server('REQUEST_METHOD') == 'OPTIONS') {
            $response = app()->prepareResponse('');

            if (in_array($request->server('HTTP_ORIGIN'), config('cross_origin.allow_domain_list'))) {
                $response->headers->set('Access-Control-Allow-Origin', config('cross_origin.doamin_schema') . $request->server('HTTP_ORIGIN'));
                $response->headers->set('Access-Control-Allow-Methods', implode(', ', config('cross_origin.allow_request_type')));
                $response->headers->set('Access-Control-Allow-Credentials', config('cross_origin.allow_credentials'));
                $response->headers->set('Access-Control-Allow-Headers', implode(', ', config('cross_origin.allow_headers')));
            }

            return $response;
        }

        if (in_array($request->server('HTTP_ORIGIN'), config('cross_origin.allow_domain_list'))) {

            $response = $next($request);

            $response->headers->set('Access-Control-Allow-Origin', config('cross_origin.doamin_schema') . $request->server('HTTP_ORIGIN'));
            $response->headers->set('Access-Control-Allow-Methods', implode(', ', config('cross_origin.allow_request_type')));
            $response->headers->set('Access-Control-Allow-Credentials', config('cross_origin.allow_credentials'));
            $response->headers->set('Access-Control-Allow-Headers', implode(', ', config('cross_origin.allow_headers')));

            return $response;
        }

        return '';
        
    }

}
