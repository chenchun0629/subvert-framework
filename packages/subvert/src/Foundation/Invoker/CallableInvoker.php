<?php

namespace Subvert\Framework\Foundation\Invoker;

use Subvert\Framework\Contract\Invokable;

class CallableInvoker implements Invokable
{

    public static function execute($action, $data)
    {
        return app()->call($action, $data));
    }

}