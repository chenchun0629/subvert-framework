<?php

namespace Com\Entity\Bll\Test;

use Com\Foundation\SessionProcesser;

class Network extends SessionProcesser
{

    public function getInputRegular()
    {
        return [];
    }

    public function getOutputRegular()
    {
        return [
            'd' => ['*', 'abc']
        ];
    }

}
