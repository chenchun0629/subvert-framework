<?php

namespace Bll\Test\Network;


use DB;
use SQLBuilder;
use ResponseData;
use Bll\Base;
use Store\Sql\Bll\Test\NetworkRepo;
use Store\Code\Bll\Test\NetworkCode;
use Repo\Test as Repository;

class Ping extends Base
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


    public function sql(Repository\Contracts\SqlTestContract $repo, $params)
    {

        // echo SQLBuilder::where('id', 2)->orWhere('id', 3)->compile(), "\n";
        // echo SQLBuilder::whereBetween('id', [2,4])->orWhereBetween('id', [3,5])->compile(), "\n";
        // echo SQLBuilder::whereNotBetween('id', [2,4])->orwhereNotBetween('id', [3,5])->compile(), "\n";
        // echo SQLBuilder::whereIn('id', [2,4])->orWhereIn('id', [3,5])->compile(), "\n";
        // echo SQLBuilder::whereNotIn('id', [2,4])->orWhereNotIn('id', [3,5])->compile(), "\n";
        // echo SQLBuilder::whereNull('id')->orWhereNull('age')->compile(), "\n";
        // echo SQLBuilder::whereNull('a.id')->orWhereNull('a.age')->compile(), "\n";
        // echo SQLBuilder::whereNotNull('id')->orWhereNotNull('age')->compile(), "\n";
        // echo SQLBuilder::whereNotNull('id')->orWhereNotNull('age')->compile(), "\n";
        // echo SQLBuilder::where('name', 'like', '%a%')->compile(), "\n";
        // echo SQLBuilder::where('age', '>', 1)->compile(), "\n";
        // echo SQLBuilder::where(function($query) {
        //     $query->where('id', '<', 3)->orWhere('id', '>', 5);
        // })->where(function($query) {
        //     $query->where('age', '<', 3)->orWhere('age', '>', 5);
        // })->where(function($query) {
        //     $query->whereIn('id', [1, 2])->whereNotNull('id');
        // })->compile(), "\n";

        $data = $repo->sql($params);
        $data = $repo->sql($params);

        return ResponseData::success($data);
    }
    

    public function entity()
    {
        return ResponseData::success(['hello' => 'world']);
        
    }

    public function sess()
    {
        return ResponseData::success(['method' => 'session']);
    }


    public function paramerr()
    {
        $params = [];
        $vaild = $this->validate($params, [
                'id' => 'required|integer'
            ]);   

        return ResponseData::set(NetworkCode::PARAMETER_ERROR, $vaild['errors']);
    }

    public function destory()
    {
        return ResponseData::success(['method' => 'destory']);
    }

}
