<?php

namespace Store\Code\System;

use Subvert\Framework\Foundation\Response\ResponseData;

use Store\Code\AbstractCode;

class SystemCode extends AbstractCode
{

    public function range()
    {
        return [1001, 9999];
    }

    const SYSTEM_PARAMETER_ERROR     = ['code' => 1001, 'message' => '参数错误', 'flag' => ResponseData::FLAG_FAIL];
    const SYSTEM_CLIENT_ERROR        = ['code' => 1002, 'message' => '来源异常', 'flag' => ResponseData::FLAG_FAIL];
    const SYSTEM_SIGN_ERROR          = ['code' => 1003, 'message' => '签名错误', 'flag' => ResponseData::FLAG_FAIL];
    const SYSTEM_UNDEFINED_ROUTE     = ['code' => 1004, 'message' => '未定义路由', 'flag' => ResponseData::FLAG_FAIL];
    const SYSTEM_ROUTE_PATH_ERROR    = ['code' => 1005, 'message' => '路由配置未找到', 'flag' => ResponseData::FLAG_FAIL];
    const SYSTEM_NOT_FOUND_ERROR     = ['code' => 1006, 'message' => '非法接口', 'flag' => ResponseData::FLAG_FAIL];
    const SYSTEM_READ_SESSION_ERROR  = ['code' => 1007, 'message' => '会话异常', 'flag' => ResponseData::FLAG_FAIL];
    const SYSTEM_WRITE_SESSION_ERROR = ['code' => 1008, 'message' => '会话异常', 'flag' => ResponseData::FLAG_FAIL];
    const SYSTEM_TOKEN_ERROR         = ['code' => 1009, 'message' => '未登录', 'flag' => ResponseData::FLAG_NOTICE];



}
