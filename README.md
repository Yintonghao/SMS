# 频发拦截
设定git版本
~~~
git tag -a v1.0.1 -m "第1个版本"
~~~
~~~
git push origin v1.0.1
~~~

使用须知
~~~
请把src/config目录下的Dok.php拷贝到app/common/lib下边。也可拷贝到其它目录请注意命令空间
~~~
调用示例
~~~
/*
 * 频发拦截
 */
public function sms()
{
    $param = $this->request->param();
    //后台配置
    $obj = new Setting();
    //枚举类型
    $data = $obj->getConfigSms();
    //添加或修改
    $ad = [
        'type' => 1,
        'business' => 1,
        'second' => 1,
        'c' => 1,// 1-时 2-分 3-秒
        'num' => 1,
        'sort' => 0
    ];
    $data = $obj->addConfig($ad);
    //删除
    $obj->deleteConfig(1);

    //频繁拦截
    $obj = new Intercept(Dok::BUSINESS_LOGIN);
    $obj->mobile = $param['mobile'];
    $obj->IP = '127.0.0.1';
    $obj->holdBack();
    $obj->sendBeforeInc();
}

/*
 * 发送短信
 */
public function send()
{
    //创蓝平台
    $appid = '你的应用id';
    $secret = '你的应用密钥';
    $obj = Factory::createObject('ChuangLanSms',$appid,$secret);
    $code = $obj->getCode(6);
    $msg = "您的验证码为: {$code}，请妥善保管";
    $obj->sendSMS(15350800151,$msg);
    //后续还会增加其它平台
} 

~~~