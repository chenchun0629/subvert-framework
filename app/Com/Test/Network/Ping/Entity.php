<?php

namespace Com\Test\Network\Ping;

use Com\Foundation\SessionProcesser;

class Entity extends SessionProcesser
{

    public function getInputRegular()
    {
        return [];
    }

    public function getOutputRegular()
    {
        return [];
    }

    public function output($response)
    {
        $this->session['test'] = 'hello world';
        $response['response'] = app('session')->sessionId();
    }

}