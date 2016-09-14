<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use ResponseData;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Foundation\Session\Session;
use Subvert\Framework\Contract\RequestMiddleware;

class TokenValidationMiddleware implements RequestMiddleware, Validatable
{

    public function handle($request, Closure $next)
    {
        
        if (!$this->validate($request->all())) {
            return app()->prepareResponse(
                ResponseData::set(SystemCode::SYSTEM_TOKEN_ERROR, false)
            );
        }
        
        return $next($request);
        
    }

    public function validate($data)
    {

        if (empty($data['body']['token']) || Session::existsSessionId($data['body']['token'])) {
            return true;
        }

        return false;
    }

}
