<?php

namespace Com\Foundation;

use Subvert\Framework\Contract\SessionProcesser as SessionProcesserContract;
use Subvert\Framework\Contract\Sessionable;

abstract class SessionProcesser implements SessionProcesserContract
{

    protected $session;

    public function __construct(Sessionable $session)
    {
        $this->session = $session;
    }

    public function input($request)
    {
        $data = $request->all();
    }

    public function output($response)
    {

    }
    
    /**
     * return example
     * [
     *     'w'  => ['key'], // session写入到request
     *     'r'  => ['key'], // request写入到session
     * ]
     * @return [type] [description]
     */
    abstract public function getInputRegular();

    /**
     * return example
     * [
     *     'w'  => ['key'], // session写入到response
     *     'r'  => ['key'], // response写入到session
     * ]
     * @return [type] [description]
     */
    abstract public function getOutputRegular();

}