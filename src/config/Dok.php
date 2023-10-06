<?php

namespace app\common\lib;

class Dok
{
    /**
     * 业务
     */
    const BUSINESS_LOGIN = 1;//手机号限制
    const BUSINESS_ZHUCE = 2;//IP限制
    
    /**
     * 类型
     */
    const TYPE_MOBILE = 1;//手机号
    const TYPE_IP = 2;//IP
    
    public $config = [
        [
            ['type_id' => self::TYPE_MOBILE,'name' => '手机号'],
            ['type_id' => self::TYPE_IP,'name' => 'IP']
        ],
        [
            ['business_id' => self::BUSINESS_LOGIN,'name' => '手机号限制'],
            ['business_id' => self::BUSINESS_ZHUCE,'name' => 'IP限制']
        ]
    ];
}