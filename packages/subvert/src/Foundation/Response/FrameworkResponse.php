<?php

namespace Subvert\Framework\Foundation\Response;

use Subvert\Framework\Foundation\Response\ResponseData;
use Subvert\Framework\Contract\ResponseStructGroup as ResponseStructGroupContract;

class FrameworkResponse implements ResponseStructGroupContract
{

    public function range()
    {
        return [1, 999];
    }


    const SYSTEM_EXCEPTION = ['code' => 1, 'message' => '系统异常', 'flag' => ResponseData::FLAG_FAIL];

}
