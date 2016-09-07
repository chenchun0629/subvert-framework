<?php


namespace Subvert\Framework\Contract;

interface Invokable
{
    public static function execute($action, $data);
}