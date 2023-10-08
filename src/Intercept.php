<?php

namespace Mydom\Sms;

use app\common\lib\Dok;
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
    public $allKey = [];
    //数据配置
    public $config = [];

    public function __construct($businessID)
    {
        parent::__construct();
        if (!$businessID) {
            throw new \Exception('缺少必要参数', 10500);
        }
        $this->config = (new Setting())->getConfigSms();
        $this->businessID = $businessID;
        if($this->config){
            $this->getConfing();
        }
    }

    private function getConfing()
    {
        $newConfig = [];
        foreach ($this->config as $value){
            if($value['business'] == $this->businessID){
                $newConfig[] = $value;
            }
        }
        $this->config = $newConfig;
    }

    /**
     * 拦截频发
     */
    public function holdBack()
    {
        if (!$this->mobile && !$this->IP) {
            throw new \Exception('缺少必要参数', 10500);
        }
        foreach ($this->config as $index => $item) {
            switch ($item['type']) {
                case Dok::TYPE_MOBILE:
                    $sole = $this->mobile;
                    break;
                case Dok::TYPE_IP:
                    $sole = $this->IP;
                    break;
            }
            $this->hold($item, $sole);
        }
        return true;
    }

    private function hold($data,$sole)
    {
        $key =  $data['business'] . '_' . $data['type'] . ':' . $data['second'] . $sole;

        $this->allKey[] = ['key' => $key, 'second' => $data['second']];

        $redis = Cache::store($this->redisName);

        $num = $redis->get($key) ?? 0;

        if ($num + 1 > $data['num']) {
            throw new \Exception("频繁发送短信,请稍后再试", 10500);
        }
        return true;
    }

    /**
     * value+1
     */
    public function sendBeforeInc()
    {
        $ret = [];
        foreach ($this->allKey as $value) {
            $redis = Cache::store($this->redisName);

            $key = $value['key'];

            $num = $redis->incr($key, 1);
            if($num === false){
                throw new \Exception('服务器异常,请稍后再试');
            }

            if ($num == 1) {
                $redis->expire($key, $value['second']);
            }
            $ret[$value['key']] = $num;
        }
        return $ret;
    }
}