<?php

namespace Com\Entity\Test\Network\Ping;

use Com\Foundation\SessionProcesser;

class Sess extends SessionProcesser
{

    public function getInputRegular()
    {
        return [
            'r' => ['test'],
            'w' => ['a'],
        ];
    }

    public function getOutputRegular()
    {
        return [
            'r' => ['test', 'a'],
        ];
    }

}
