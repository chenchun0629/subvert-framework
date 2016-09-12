<?php

namespace Repo\Test\Contracts;

interface SqlTestContract extends TestModuleContract
{
    public function sql($params);
}