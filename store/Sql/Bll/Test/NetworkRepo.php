<?php

namespace Store\Sql\Bll\Test;


class NetworkRepo
{

    const TEST_SQL_1 = [
        'sql' => 'SELECT * FROM `test` WHERE id > #{id}',
        'require' => ['id'],
    ];


    const TEST_SQL_2 = [
        'sql' => 'SELECT * FROM `test` WHERE id > #{id} #{LIMIT}#',
        'require' => ['id'],
    ];

}
