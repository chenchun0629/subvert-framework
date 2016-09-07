<?php

namespace Com\Bootstrap\Validation;

use Subvert\Framework\Contract\Validatable;

class TestSignValidation extends SignValidation implements Validatable
{

    const KEY = 'MWY5MjJkMDQzMDFlYzZhZjk2ZmIwNmI5MTVmZmM5ZTU=';

    public function getKey()
    {
        return self::KEY;
    }


}