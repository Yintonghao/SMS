<?php

namespace Mydom\Sms;

use think\facade\Cache;

/**
 * 频发拦截
 */
class Intercept extends Base
{
    public $config = [];
    public function __construct()
    {
        parent::__construct();
        $this->config = (new Setting())->getConfigSms();
    }

    /**
     * 拦截频发
     * @param $mobile
     * @param $IP
     * @return void
     * @throws \Exception
     */
    public function holdBack($mobile,$IP)
    {
        if(!$mobile){
            throw new \Exception('缺少手机号',10500);
        }
        if(!$IP){
            throw new \Exception('缺少IP',10500);
        }
        foreach ($this->config as $index => $item){
            $type = $item['type'].'_'.$item['business'];
            switch ($type){
                case '1_1':// 手机号限制频发
                    $key = $type.':'.$mobile;
                    break;
                case '2_2':// IP限制频发
                    $key = $type.':'.$IP;
                    break;
                case '1_2':// 手机号+IP频发
                    $key = $type.':'.$mobile.$IP;
                    break;
            }
            $this->hold($key,$item);
        }
    }
    
    private function hold($key,$data)
    {
        $redis = Cache::store($this->redisName);

        $num = $redis->inc($key,1);

        if($num == 1){
            $redis->expire($key,$data['second']);
        }

        $ttl = $redis->ttl($key);
        if($num > $data['num'] && $ttl > 0){
            throw new \Exception('频繁发送短信,请稍后再试',10500);
        }
        return true;
    }
}