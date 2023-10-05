<?php

namespace app\common\lib;

class Dok
{

    const BUSINESS_LOGIN = 1;
    const BUSINESS_ZHUCE = 2;


    const TYPE_MOBILE = 1;
    const TYPE_IP = 2;

    public $config = [
        [
            self::BUSINESS_LOGIN => '登录',
            self::TYPE_MOBILE => '手机号',
        ],
        [
            self::BUSINESS_LOGIN => '登录',
            self::TYPE_IP => '手机号',
        ]
    ];
}