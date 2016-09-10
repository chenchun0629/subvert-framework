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

        $where = SQLBuilder::where('id', 2)->orWhere('id', 3)->compile();

        return ResponseData::success(
            [
                DB::select(SQLBuilder::builder(NetworkRepo::TEST_SQL_1, ['id' => $id])),
                DB::select(SQLBuilder::builder(NetworkRepo::TEST_SQL_2, ['id' => $id], ['limit' => 'limit 0, 1'])),
                DB::select(SQLBuilder::builder(NetworkRepo::TEST_SQL_3, ['id' => $id], ['limit' => 'limit 0, 1', 'where' => $where])),
            ]
        );
    }

}
