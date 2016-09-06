<?php

namespace Com\Filter;

use Subvert\Framework\Contract\Filter;

class ParameterFilter
{

    protected $parameterMapping = [
        'call' => [
            'api', 'data', 'api_version',
        ],
        'body' => [
            'token', 'sign', 'client',
        ],
        'device' => [
            'type', 'info', 'app_version',
        ]
    ];

    public function handle($request)
    {
        
    }

}
