<?php

namespace Bll\Test\Network;


use DB;
use SQLBuilder;
use ResponseData;
use Store\Sql\Bll\Test\NetworkRepo;
use Store\Code\Bll\Test\NetworkCode;

class Ping
{


    public function pong()
    {
        // return ResponseData::set(
        //     NetworkCode::RESPONSE_SUCCESS, 'pong'
        //     );

        return ResponseData::success(
            ['ping' => 'pong']
        );
    }


    public function sql(Repository\Interfaces\TestRepositoryInterface $repo, $id)
    {
        $data = $repo->sql($id);
        
        return ResponseData::success($data);
    }
    

    public function entity()
    {
        return ResponseData::success(['hello' => 'world']);
        
    }

    public function sess($a, $test)
    {
        return ResponseData::success(['method' => 'session']);
    }

}
