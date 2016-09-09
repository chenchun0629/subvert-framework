<?php

namespace Subvert\Framework\Contract;

interface Cachable
{
    public function cache($request, $response);
}
