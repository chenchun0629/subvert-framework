<?php

namespace Subvert\Framework\Utils;

use Subvert\Framework\Foundation\Struct\ResponseStruct;
use Subvert\Framework\Contract\ResponseStructGroup as ResponseStructGroupContract;

class FrameworkResponse implements ResponseStructGroupContract
{

    public function range()
    {
        return [1, 999];
    }


    const SYSTEM_UPGRADE   = ['code' => 1, 'message' => '系统升级维护', 'flag' => ResponseStruct::FLAG_FAIL];
    const SYSTEM_EXCEPTION = ['code' => 2, 'message' => '系统异常', 'flag' => ResponseStruct::FLAG_FAIL];

}
