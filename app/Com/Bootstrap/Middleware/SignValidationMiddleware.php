<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use ResponseData;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;

class SignValidationMiddleware implements RequestMiddleware, Validatable
{

    public function handle($request, Closure $next)
    {
        
        if (env('APP_DEBUG') && !env('SIGN_VALID')) {
            return $next($request);
        }
        
        $valid = $this->validate($request->all());

        if (!$valid['result']) {
            app('log')->error('system.sign.validation', [$valid]);

            if (env('APP_DEBUG')) {
                return app()->prepareResponse(
                    ResponseData::set(SystemCode::SYSTEM_SIGN_ERROR, $valid)
                );
            }

            return app()->prepareResponse(
                ResponseData::set(SystemCode::SYSTEM_SIGN_ERROR, false)
            );
        }
        
        return $next($request);
        
    }

    public function validate($data)
    {
        return app('Com\Bootstrap\Validation\SignValidation')->validate($data);
    }

}
