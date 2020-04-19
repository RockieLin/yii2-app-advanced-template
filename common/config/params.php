<?php
return [
    "title"              => "Site Title",
    'noReplyEmail'       => ['noreply@domain.com' => "No Reply Mail"],
    'adminEmail'         => [
        'xxx@domain.com'],
    'commonCacheTime'    => 86400,
    'defaultPageSize'    => 30,
    'checkCodeExpired'   => 86400, //1天
    'cookieExpired'      => 86400 * 10,
    "thumbWidth"         => [
        "announce" => [
            "width"  => 720,
            "height" => 400],
    ],
    'statusList'         => [
        1 => "啟用",
        0 => "停用"
    ],
    'bool'               => [
        1 => "是",
        0 => "否"
    ],
    'memberStatus'       => [
        2 => "已認證",
        1 => "Email未認證",
        0 => "停用"
    ],
    'sexList'            => [
        "male"   => "男",
        "female" => "女"
    ],
    'adminRoleType'      => [
        1 => "主帳號",
        2 => "副帳號1",
        3 => "副帳號2"],
    'socialType'         => [
        "email"    => "本站註冊",
        "facebook" => "Facebook",
    ],
    "mailCheckCodeTitle" => [
        "register"  => "E-mail 認證",
        "forgetPwd" => "忘記密碼確認",
    ],
];
