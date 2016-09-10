<?php

namespace Com\Bootstrap\Middleware;

use Closure;
use ResponseData;
use Store\Code\System\SystemCode;
use Subvert\Framework\Contract\RequestMiddleware;

use Com\Bootstrap\Providers;

class RegisterProviderMiddleware implements RequestMiddleware
{

    public function handle($request, Closure $next)
    {

        $this->register();
        
        return $next($request);
        
    }


    protected function register()
    {
        $this->testRegister();
    }

    protected function testRegister()
    {
        app()->register(Providers\TestServiceProvider::class);
    }


}
