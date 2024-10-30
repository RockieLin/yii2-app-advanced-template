<?php
//總後台選單
return [
    [
        "menuId"   => null,
        "menuName" => null,
        "nodes"    => [
            [
                'name'             => '會員管理',
                "permission"       => "member.index",
                "activePermission" => [
                    "member.*"
                ]
            ],
        ],
    ],
    [
        "menuId"   => null,
        "menuName" => null,
        "nodes"    => [
            [
                'name'             => '參數設定',
                "permission"       => "config.index",
                "activePermission" => [
                    "config.*"
                ]
            ],
        ],
    ],
    [
        // 后台帐号
        "menuId"   => "admin",
        "menuName" => "後台權限",
        "nodes"    => [
            [
                'name'             => '後台帳號管理',
                "permission"       => "admin.index",
                "activePermission" => [
                    "admin.index",
                    "admin.update",
                    "admin.create"
                ]
            ],
            [
                'name'             => '角色管理',
                "permission"       => "admin.role",
                "activePermission" => [
                    'admin.role'
                ]
            ],
            [
                'name'             => '權限管理',
                "permission"       => "permission.index",
                "activePermission" => [
                    'permission.*'
                ]
            ],
            [
                'name'             => 'Log',
                "permission"       => "log-reader",
                "activePermission" => [
                    'log-reader.*'
                ]
            ],
        ],
    ],
];
