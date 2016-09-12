<?php

namespace Repo\Test\Instances;

use Invoker;
use Repo\Test\Contracts;

class SqlTestLocalRepository implements Contracts\SqlTestContract
{

    public function sql($params)
    {
        $class = 'Base.TestModule.Sql.Test.sql';
        $args = $params;
        return Invoker::execute($class, $args);
    }

}