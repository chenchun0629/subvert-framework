<?php

namespace Subvert\Framework\Contract;

interface Sessionable
{
    public function sessionId();
    public function get($key);
    public function set($key, $value);
    public function destory();

}
