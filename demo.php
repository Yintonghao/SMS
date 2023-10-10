<?php

namespace sms;
use app\common\lib\Dok;
use Mydom\Sms\Intercept;

/**
 * 调用示例
 */
class demo
{
    public function sms()
    {
        //$param = $this->request->param();
        //后台配置
        //$obj = new Setting();
        //枚举类型
        //$data = $obj->getConfigSms();
        //添加或修改
//        $ad = [
//            'type' => 1,
//            'business' => 1,
//            'second' => 1,
//            'c' => 1,// 1-时 2-分 3-秒
//            'num' => 1,
//            'sort' => 0
//        ];
        //$data = $obj->addConfig($ad);
        //删除
        //$obj->deleteConfig(1);

        //频繁拦截
        //$obj = new Intercept(Dok::BUSINESS_LOGIN);
        //$obj->mobile = $param['mobile'];
        //$obj->IP = '127.0.0.1';
        //$obj->holdBack();
        //$obj->sendBeforeInc();
    }
}



