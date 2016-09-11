<?php

namespace Subvert\Framework\Foundation\Exception;

use Exception;
use Illuminate\Http\Response as LumenResponse;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

use Subvert\Framework\Foundation\Response\ResponseData;
use Subvert\Framework\Foundation\Response\FrameworkCode;

class ExceptionHandler extends ExceptionHandler
{
    
    public function report(Exception $e)
    {
        app('log')->error('system.exception.handler', [$e]);
    }
    
    public function render($request, Exception $e)
    {
        if (env('APP_DEBUG')) {
            return new LumenResponse(ResponseData::set(FrameworkCode::SYSTEM_EXCEPTION, (string)$e));
        }
        return new LumenResponse(ResponseData::set(FrameworkCode::SYSTEM_EXCEPTION));
    }
}
