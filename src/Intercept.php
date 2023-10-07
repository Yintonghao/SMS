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
    public $allKey = [];
    //数据配置
    public $config = [];

    public function __construct($businessID)
    {
        parent::__construct();
        $this->config = (new Setting())->getConfigSms();
        if (!$businessID) {
            throw new \Exception('缺少必要参数', 10500);
        }
        $this->businessID = $businessID;
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
            switch ($item['business']) {
                case 1:
                    switch ($item['type']) {
                        case 1:
                            $jz = $item['second'] . ':' . $this->mobile;
                            $key = $item['business'] . '_' . $item['type'] . ':' . $jz;
                            $this->hold($key, $item);
                            break;
                        case 2:
                            $jz = $item['second'] . ':' . $this->IP;
                            $key = $item['business'] . '_' . $item['type'] . ':' . $jz;
                            $this->hold($key, $item);
                            break;
                    }
                    break;
                default:
                    //待开发
                    $key = null;
                    break;
            }
        }
        return true;
    }

    private function hold($key, $data)
    {
        $this->allKey[] = ['key' => $key, 'second' => $data['second']];
        $redis = Cache::store($this->redisName);

        $num = $redis->get($key);

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
        foreach ($this->allKey as $value) {
            $redis = Cache::store($this->redisName);

            $key = $value['key'];

            $num = $redis->incr($key, 1);

            if ($num == 1) {
                $redis->expire($key, $value['second']);
            }
        }
    }
}