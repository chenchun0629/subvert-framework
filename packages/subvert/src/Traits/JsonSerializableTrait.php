<?php

namespace Subvert\Framework\Traits;

trait JsonSerializableTrait
{
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
