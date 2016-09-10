<?php

namespace Store\Code\Bll\Test;

use Store\Code\Bll\BllCode;

abstract class TestCode extends BllCode
{
    public function range()
    {
        return [
            10000001, 10099999
        ];
    }
}
