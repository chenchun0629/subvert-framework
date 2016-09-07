<?php

namespace Subvert\Framework\Traits;

trait JsonableTrait
{
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
