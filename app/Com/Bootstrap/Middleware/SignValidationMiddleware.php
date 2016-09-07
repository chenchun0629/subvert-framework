<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use Subvert\Framework\Contract\Validatable;
use Subvert\Framework\Contract\RequestMiddleware;

class SignValidationMiddleware implements RequestMiddleware, Validatable
{

    public function handle($request, Closure $next)
    {

        $valid = $this->validate($request->all());

        if (!$valid['result']) {
            return $valid;
        }
        
        return $next($request);
        
    }

    public function validate($data)
    {
        return app('Com\Bootstrap\Validation\SignValidation')->validate($data);
    }

}
