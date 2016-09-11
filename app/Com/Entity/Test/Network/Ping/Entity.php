<?php

namespace Com\Entity\Test\Network\Ping;

use Com\Foundation\SessionProcesser;

class Entity extends SessionProcesser
{

    public function getInputRegular()
    {
        return [];
    }

    public function getOutputRegular()
    {
        return [
            'w' => [
                'hello',
            ]
        ];
    }

    public function output($response)
    {
        $this->session['test'] = 'hello world';
        $response['response'] = app('session')->sessionId();
        parent::output($response);
        return $response;
    }

}
