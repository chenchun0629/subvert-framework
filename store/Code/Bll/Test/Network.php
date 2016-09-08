<?php

namespace Store\Code\Bll\Test;

use Subvert\Framework\Foundation\Response\ResponseData;

use Store\Code\AbstractCode;

class Network extends AbstractCode
{


    public function range()
    {
        return [10001, 10999];
    }


}
