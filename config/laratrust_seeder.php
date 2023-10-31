<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        // 'superadministrator' => [
        //     'users' => 'c,r,u,d',
        //     'payments' => 'c,r,u,d',
        //     'profile' => 'r,u'
        // ],
        // 'administrator' => [
        //     'users' => 'c,r,u,d',
        //     'profile' => 'r,u'
        // ],
        // 'user' => [
        //     'profile' => 'r,u',
        // ],
        'administrator' => [
            'users' => 'c,r,u,d',
            'payments' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'HR' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'accounting' => [
            'users' => 'c,r,u,d',
            'payments' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'employee' => [
            'profile' => 'r,u'
        ],
        'attendance' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'manager' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'COO' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'VPO' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'CEO' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'SVPT' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'legal' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'assistantHR' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'teamleader' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ]
        // 'role_name' => [
        //     'module_1_name' => 'c,r,u,d',
        // ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
