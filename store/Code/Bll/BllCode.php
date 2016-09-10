<?php

namespace Store\Code\Bll;

use Store\Code\AbstractCode;

abstract class BllCode extends AbstractCode
{
    public function range()
    {
        return [
            10000001, 19999999
        ];
    }
}
