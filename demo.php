<?php

namespace sms;
/**
 * 调用示例
 */
class demo
{
    public function sms()
    {
        /**
         * 调用示例
         * 请把src/config目录下的Dok.php拷贝到app/common/lib下边。也可拷贝到其它目录请注意命令空间
         */
        //$param = $this->request->param();
        //后台配置
        //$obj = new Setting();
        //枚举类型
        //$data = $obj->getConfigSms();
        //添加或修改
        //$data = $obj->saveConfig(['type' => 1,'business' => 2,'second' => 1,'num' => 1,'sort' => 2]);
        //删除
        //$obj->deleteConfig(1,2);

        //频繁拦截
        //$obj = new Intercept();
        //$obj->holdBack($param['mobile'],'127.0.0.1');
    }
}