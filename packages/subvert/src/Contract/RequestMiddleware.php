<?php

namespace Subvert\Framework\Contract;

use Closure;

interface RequestMiddleware
{
    public function handle($request, Closure $next);
}
