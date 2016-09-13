<?php

return [

    'Bll.Test.*' => [
        'session' => [
            'in' => [
                'r' => [],
                'w' => [],
                'd' => [],
            ],
            'out' => [
                'r' => [],
                'w' => [],
                'd' => [],
            ]
        ],
        'cache' => [
            /*.....*/
        ]
    ],

    'Bll.Test.Network.*' => [
        'session' => [
            'in' => [
                'r' => [],
                'w' => [],
                'd' => [],
            ],
            'out' => [
                'r' => [],
                'w' => [],
                'd' => [],
            ]
        ],
        'cache' => [
            /*.....*/
        ]
    ],


    'Bll.Test.Network.Ping.entity' => [
        'session' => [
            'in' => [
                'r' => [
                    'a'
                ],
                'w' => [],
                'd' => [],
            ],
            'out' => [
                'r' => [],
                'w' => [],
                'd' => [],
            ]
        ],
        'cache' => [
            /*.....*/
        ]
    ],

    'Bll.Test.Network.Ping.sess' => [
        'session' => [
            'in' => [
                'r' => [
                    'a'
                ],
                'w' => [],
                'd' => [],
            ],
            'out' => [
                'r' => [
                    'a',
                ],
                'w' => [
                    'method'
                ],
                'd' => [],
            ]
        ],
        'cache' => [
            /*.....*/
        ]
    ],

    'Bll.Test.Network.Ping.destory' => [
        'session' => [
            'in' => [
                'r' => [
                    'a'
                ],
                'w' => [],
                'd' => [],
            ],
            'out' => [
                'r' => [],
                'w' => [],
                'd' => ['*'],
            ]
        ],
        'cache' => [
            /*.....*/
        ]
    ],

];
