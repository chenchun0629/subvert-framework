<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use ResponseData;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;

use Store\Code;

class FilteResponseMiddleware implements RequestMiddleware
{

    protected $filte = [
        Code\System\SystemCode::PARAMETER_ERROR['code']
    ];

    public function handle($request, Closure $next)
    {


        $response = $next($request);

        if ($response->original instanceof ResponseData) {

            if (in_array($response->original['code'], $this->filte) && env('APP_DEBUG')) { 
                $responseData = $response->original;
                $responseData['response'] = [];
                $response->setContent($responseData);
            }

        }

        return $response;

    }

}