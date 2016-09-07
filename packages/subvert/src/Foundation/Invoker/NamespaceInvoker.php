<?php

namespace Subvert\Framework\Foundation\Invoker;

use Subvert\Framework\Contract\Invokable;

class NamespaceInvoker implements Invokable
{

    public static function execute($action, $data)
    {
        list($class, $method) = explode('@', $action);
        $object = app()->make($class);
        return app()->call([$object, $method], $data);
    }

}