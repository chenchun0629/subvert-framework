<?php

namespace Subvert\Framework\Contract;

interface SessionProcesser
{
    public function input($request);
    public function output($response);
    // public function getInputRegular();
    // public function getOutputRegular();

}
