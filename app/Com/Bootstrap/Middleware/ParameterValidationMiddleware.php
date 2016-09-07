<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Validator;
use Subvert\Framework\Contract\RequestMiddleware;

class ParameterValidationMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {

        $validator = Validator::make($request->all(), [
                    'call'               => 'required|array',
                    'call.api'           => 'required|string',
                    'call.data'          => 'required|string',
                    'call.api_version'   => 'required|string',
                    'body'               => 'required|array',
                    'body.token'         => 'alpha',
                    'body.sign'          => 'required|string',
                    'body.client'        => 'required|string',
                    'device'             => 'required|array',
                    'device.type'        => 'required|string',
                    'device.info'        => 'required|string',
                    'device.app_version' => 'required|string',
                ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return $next($request);
        
    }

}
