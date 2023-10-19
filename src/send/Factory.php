<?php

namespace Mydom\Sms\send;

class Factory
{
    static $auditHandleClass = [
        'ChuangLanSms'    => "\Mydom\Sms\send\ChuangLanSms",
        //后续可增加阿里云、腾讯
    ];

    public static function createObject($classNmae,$appid,$secret)
    {
        if(!isset(self::$auditHandleClass[$classNmae])){
            throw new \Exception('短信平台不存在,[ '.$classNmae.' ]',10500);
        }
        
        $class = self::$auditHandleClass[$classNmae];
        $obj = new $class;
        $obj->setAppid($appid);
        $obj->setSecret($secret);
        $obj->setPlatform($classNmae);
        return $obj;
    }
}