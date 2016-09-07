<?php

namespace Subvert\Framework\Foundation\Invoker;

use Subvert\Framework\Contract\Invokable;

class DotInvoker implements Invokable
{

    public static function execute($action, $data)
    {
        $action = explode('.', $action);
        $method = array_pop($action);
        $class  = implode('\\', $action);
        $object = app()->make($class);
        return app()->call([$object, $method], $data);
    }

}