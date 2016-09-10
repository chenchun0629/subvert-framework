<?php

namespace Bll\Test\Network\Repository\Instances;

use Invoker;
use Bll\Test\Network\Repository;

class TestLocalRepository implements Repository\Interfaces\TestRepositoryInterface
{

    public function sql($id)
    {
        $class = 'Base.TestModule.Sql.Test.sql';
        $args = [
            'id' => $id,
        ];
        return Invoker::execute($class, $args);
    }

}
