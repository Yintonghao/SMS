<?php

namespace Mydom\Sms;

use think\facade\Cache;

/**
 * 频发拦截
 */
class Intercept extends Base
{
    //手机号
    public $mobile;
    //IP地址
    public $IP;
    //业务ID
    public $businessID;
    //拦截异常
    public $Mexception = [];
    public $IPexception = [];
    //数据配置
    public $config = [];
    public function __construct($businessID,$mobile,$IP)
    {
        parent::__construct();
        $this->config = (new Setting())->getConfigSms();
        if(!$businessID){
            throw new \Exception('缺少必要参数',10500);
        }
        if(!$mobile && !$IP){
            throw new \Exception('缺少必要参数',10500);
        }
        $this->businessID = $businessID;
        $this->mobile = $mobile;
        $this->IP = $IP;
    }

    /**
     * 拦截频发
     */
    public function holdBack()
    {
        foreach ($this->config as $index => $item){
            switch ($item['business']){
                case 1:
                    switch ($item['type']){
                        case 1:
                            if(!$this->mobile) continue;
                            $jz = $item['second'].':'.$this->mobile;
                            $key = $item['business'].'_'.$item['type'].':'.$jz;
                            $this->holdM($key, $item,$jz);
                            break;
                        case 2:
                            if(!$this->IP) continue;
                            $jz = $item['second'].':'.$this->IP;
                            $key = $item['business'].'_'.$item['type'].':'.$jz;
                            $this->holdIP($key, $item,$jz);
                            break;
                    }
                    break;
                default:
                    //待开发
                    $key = null;
                    break;
            }
        }

        if(count($this->Mexception) > 0){
            throw new \Exception(array_values($this->Mexception)[0],10500);
        }
        if(count($this->IPexception) > 0){
            throw new \Exception(array_values($this->IPexception)[0],10500);
        }

        return true;
    }

    private function holdM($key,$data,$jz)
    {
        $redis = Cache::store($this->redisName);

        $num = $redis->inc($key,1);

        if($num == 1){
            $redis->expire($key,$data['second']);
        }

        $ttl = $redis->ttl($key);
        if($num > $data['num'] && $ttl > 0){
            $this->Mexception[$jz] = "频繁发送短信,请稍后再试_{$num}";
        }
        return true;
    }

    private function holdIP($key,$data,$jz)
    {
        $redis = Cache::store($this->redisName);

        $num = $redis->inc($key,1);

        if($num == 1){
            $redis->expire($key,$data['second']);
        }

        $ttl = $redis->ttl($key);
        if($num > $data['num'] && $ttl > 0){
            $this->IPexception[$jz] = "频繁发送短信,请稍后再试_{$num}";
        }
        return true;
    }
}