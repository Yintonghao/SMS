<?php

namespace app\common\lib;

class Dok
{
    /**
     * 业务
     */
    const BUSINESS_LOGIN = 1;//手机号登录
    const BUSINESS_ZHUCE = 2;//手机号注册
    
    /**
     * 类型
     */
    const TYPE_MOBILE = 1;//手机号
    const TYPE_IP = 2;//IP

    public $config = [
        [
            ['type' => self::TYPE_MOBILE,'name' => '手机号'],
            ['type' => self::TYPE_IP,'name' => 'IP']
        ],
        [
            ['business' => self::BUSINESS_LOGIN,'name' => '手机号登录'],
            ['business' => self::BUSINESS_ZHUCE,'name' => '手机号注册']
        ]
    ];
}