<?php

namespace Mydom\Sms;

use think\facade\Cache;

/**
 * 频发拦截
 */
class Intercept extends Base
{
    public $config = [];
    public function __construct($sms_type,$business_id,$IP)
    {
        parent::__construct();

        $this->config = (new Setting())->getConfig($this->dataKey);
        $this->holdBackIP($sms_type,$business_id,$IP);
    }

    /**
     * 手机号短信送拦截
     * @param $sms_type
     * @param $business_id
     * @param $mobile
     * @return true
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function holdBack($sms_type,$business_id,$mobile)
    {
        $redis = Cache::store($this->redisName);
        $redis->select(1);

        $mobilekey = $sms_type.':'.$business_id.':'.$mobile;

        $has = $redis->has($mobilekey);

        if(!$has){
            $redis->set($mobilekey,1,$this->config['minute'] * 60);
        }else{
            //获取key的剩余时间
            $ttl = $redis->ttl($mobilekey);
            //获取key的已经发送的次数
            $limit = $redis->get($mobilekey);

            if($ttl > 0 && $limit >= $this->config['num']){
                throw new \Exception('频繁发送,请稍后再试',10500);
            }

            if($ttl > 0 && $limit < $this->config['num']){
                $redis->inc($mobilekey,1);
            }else{
                $redis->set($mobilekey,1,$this->config['minute'] * 60);
            }
        }

        return true;
    }

    public function holdBackIP($sms_type,$business_id,$IP)
    {
        $redis = Cache::store($this->redisName);
        $redis->select(1);

        $IPkey = $sms_type.':'.$business_id.':'.$IP;

        $has = $redis->has($IPkey);

        if(!$has){
            $redis->set($IPkey,1,$this->config['total_minute'] * 60);
        }else{
            //获取key的剩余时间
            $ttl = $redis->ttl($IPkey);
            //获取key的已经发送的次数
            $limit = $redis->get($IPkey);

            if($ttl > 0 && $limit >= $this->config['total_num']){
                throw new \Exception('同IP频繁发送,请稍后再试',10500);
            }

            if($ttl > 0 && $limit < $this->config['total_num']){
                $redis->inc($IPkey,1);
            }else{
                $redis->set($IPkey,1,$this->config['total_minute'] * 60);
            }
        }

        return true;
    }

    
}