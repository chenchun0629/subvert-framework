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
        // echo SQLBuilder::where('id', 2)->orWhere('id', 3)->compile();
        // echo SQLBuilder::whereBetween('id', [2,4])->orWhereBetween('id', [3,5])->compile();
        // echo SQLBuilder::whereNotBetween('id', [2,4])->orwhereNotBetween('id', [3,5])->compile();
        // echo SQLBuilder::whereIn('id', [2,4])->orWhereIn('id', [3,5])->compile();
        // echo SQLBuilder::whereNotIn('id', [2,4])->orWhereNotIn('id', [3,5])->compile();
        // echo SQLBuilder::whereNull('id')->orWhereNull('age')->compile();
        // echo SQLBuilder::whereNull('a.id')->orWhereNull('a.age')->compile();
        // echo SQLBuilder::whereNotNull('id')->orWhereNotNull('age')->compile();
        // echo SQLBuilder::whereNotNull('id')->orWhereNotNull('age')->compile();
        // echo SQLBuilder::where('name', 'like', '%a%')->compile();
        // echo SQLBuilder::where('age', '>', 1)->compile();
        // echo SQLBuilder::where('age', '>', 1)->compile();
        $data = $repo->sql($id);
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
