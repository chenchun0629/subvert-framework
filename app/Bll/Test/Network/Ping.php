<?php

namespace Bll\Test\Network;


use DB;
use SQLBuilder;
use Store\Sql\Bll\Test\Network as NetworkSqlRepo;

class Ping
{


    public function pong()
    {
        return 'pong';
    }


    public function sql()
    {
        return [
            DB::select(SQLBuilder::builder(NetworkSqlRepo::TEST_SQL_1, ['id' => 2])),
            DB::select(SQLBuilder::builder(NetworkSqlRepo::TEST_SQL_2, ['id' => 3], 'limit 0, 1'))
        ];
    }


}