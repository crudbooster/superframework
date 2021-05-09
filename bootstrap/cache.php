<?php

return [
    'route' => [
        '/' => [
             'App\\Modules\\Main\\Controllers\\HomeController',
             'index',
        ],
    ],
    'boot' => [
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilSession\\Configs\\Boot',
            'class' => 'SuperFrameworkEngine\\App\\UtilSession\\Configs\\Boot',
        ],
         [
            'path' => 'App\\Modules\\Main\\Configs\\Boot',
            'class' => 'App\\Modules\\Main\\Configs\\Boot',
        ],
    ],
    'middleware' => [
         [
            'path' => 'App\\Modules\\Main\\Configs\\Middleware',
            'class' => 'App\\Modules\\Main\\Configs\\Middleware',
        ],
    ],
    'helper' => [
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilCache\\Configs\\Helper',
            'class' => 'SuperFrameworkEngine\\App\\UtilCache\\Configs\\Helper',
        ],
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilDateTime\\Configs\\Helper',
            'class' => 'SuperFrameworkEngine\\App\\UtilDateTime\\Configs\\Helper',
        ],
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilLang\\Configs\\Helper',
            'class' => 'SuperFrameworkEngine\\App\\UtilLang\\Configs\\Helper',
        ],
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilORM\\Configs\\Helper',
            'class' => 'SuperFrameworkEngine\\App\\UtilORM\\Configs\\Helper',
        ],
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilRequest\\Configs\\Helper',
            'class' => 'SuperFrameworkEngine\\App\\UtilRequest\\Configs\\Helper',
        ],
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilResponse\\Configs\\Helper',
            'class' => 'SuperFrameworkEngine\\App\\UtilResponse\\Configs\\Helper',
        ],
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilSession\\Configs\\Helper',
            'class' => 'SuperFrameworkEngine\\App\\UtilSession\\Configs\\Helper',
        ],
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilString\\Configs\\Helper',
            'class' => 'SuperFrameworkEngine\\App\\UtilString\\Configs\\Helper',
        ],
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilView\\Configs\\Helper',
            'class' => 'SuperFrameworkEngine\\App\\UtilView\\Configs\\Helper',
        ],
         [
            'path' => 'App\\Modules\\Main\\Configs\\Helper',
            'class' => 'App\\Modules\\Main\\Configs\\Helper',
        ],
    ],
    'command' => [
         [
            'path' => 'vendor\\fherryfherry\\super-framework-engine\\src\\App\\UtilModel\\Configs\\Command',
            'class' => 'SuperFrameworkEngine\\App\\UtilModel\\Configs\\Command',
        ],
    ],
];