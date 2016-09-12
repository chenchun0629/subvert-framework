<?php

namespace Subvert\Framework\Foundation\Response;

use Subvert\Framework\Foundation\Response\ResponseData;

class FrameworkCode
{

    public function range()
    {
        return [1, 999];
    }


    const SYSTEM_SUCCESS         = ['code' => 0, 'message' => 'success', 'flag' => ResponseData::FLAG_SUCCESS];
    const SYSTEM_EXCEPTION       = ['code' => 1, 'message' => '系统异常', 'flag' => ResponseData::FLAG_FAIL];
    const SYSTEM_BUSY            = ['code' => 2, 'message' => '系统繁忙', 'flag' => ResponseData::FLAG_FAIL];
    const SYSTEM_NOT_FOUND_ROUTE = ['code' => 3, 'message' => '无效接口', 'flag' => ResponseData::FLAG_FAIL];

    const PARAMETER_ERROR        = ['code' => 1001, 'message' => 'success', 'flag' => ResponseData::FLAG_NOTICE];

}
