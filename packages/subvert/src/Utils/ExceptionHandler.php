<?php

namespace Subvert\Framework\Utils;

use Exception;
use Illuminate\Http\Response;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

use Subvert\Framework\Foundation\Struct\ResponseStruct;

class ExceptionHandler extends ExceptionHandler
{
    
    public function report(Exception $e)
    {
        parent::report($e);
    }
    
    public function render($request, Exception $e)
    {
        return new Response(ResponseStruct::set(FrameworkResponse::SYSTEM_EXCEPTION));
    }
}
