<?php

namespace Store\Code;

use Subvert\Framework\Foundation\Response\ResponseData;

abstract class AbstractCode
{
    abstract public function range();


    const RESPONSE_SUCCESS = ['code' => 0, 'message' => 'success', 'flag' => ResponseData::FLAG_SUCCESS];
}
