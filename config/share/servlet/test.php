<?php

return [

    'Bll.Test.*' => [
        'session' => [
            'in' => [
                'r' => [],
            ],
            'out' => [
                'r' => [],
                'w' => [],
                'd' => [],
                's' => [],
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
            ],
            'out' => [
                'r' => [],
                'w' => [],
                'd' => [],
                's' => [],
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
            ],
            'out' => [
                'r' => [],
                'w' => [],
                'd' => [],
                's' => [],
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
            ],
            'out' => [
                'r' => [
                    'a',
                ],
                'w' => [
                    'method'
                ],
                'd' => [],
                's' => [],
            ]
        ],
        'cache' => [
            /*.....*/
        ]
    ],

    'Bll.Test.Network.Ping.login' => [
        'session' => [
            'in' => [
                'r' => [],
            ],
            'out' => [
                'r' => [],
                'w' => ['user'],
                'd' => [],
                's' => [],
            ]
        ],
        'token' => true,
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
            ],
            'out' => [
                'r' => [],
                'w' => [],
                'd' => ['*'],
                's' => [],
            ]
        ],
        'cache' => [
            /*.....*/
        ]
    ],

];
