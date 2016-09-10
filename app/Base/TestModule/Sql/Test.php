<?php

namespace Base\TestModule\Sql;

use DB;
use SQLBuilder;
use ResponseData;
use Store\Sql\Bll\Test\NetworkRepo;

class Test
{

    public function sql($id)
    {
        return ResponseData::success(
            [
                DB::select(SQLBuilder::builder(NetworkRepo::TEST_SQL_1, ['id' => $id])),
                DB::select(SQLBuilder::builder(NetworkRepo::TEST_SQL_2, ['id' => $id], 'limit 0, 1'))
            ]
        );
    }

}
