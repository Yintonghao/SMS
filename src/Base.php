<?php

namespace Mydom\Sms;

use think\facade\Cache;
use think\facade\Config;

class Base
{
    //数据库名
    public $database = '';
    //表前缀
    public $prefix = '';
    //表名
    public $tableName = 'sms_setting';
    //表结构
    public $table = "CREATE TABLE `%s` (
  `id` int(11) NOT NULL,
  `minute` int(6) NOT NULL DEFAULT '1' COMMENT '默认1分钟',
  `num` int(6) NOT NULL DEFAULT '1' COMMENT '默认1次',
  `total_minute` int(6) NOT NULL DEFAULT '1' COMMENT '每几分钟',
  `total_num` int(6) NOT NULL DEFAULT '3' COMMENT '每几分钟发送总次数',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    //默认配置数据
    public $defaultSql = "INSERT INTO `%s`.`%s` (`id`, `minute`, `num`, `total_minute`, `total_num`, `create_time`, `update_time`) VALUES (1, 1, 1, 1, 3, %s, %s);";
    //redis配置key的名称
    public $redisName = '';
    public $dataKey = 'sms_key';

    public function __construct()
    {
        $database = Config::get('database.connections.mysql');
        $this->database = $database['database'];
        $this->prefix = $database['prefix'];

        $store = Config::get('cache.stores');
        if(!isset($store['redis'])){
            throw new \Exception('请在config/cache文件中增加redis配置',10500);
        }
        $this->redisName = 'redis';
    }
    
    public function setData($key,$value,$expire = 0)
    {
       $redis = $this->getRedis();
       
       Cache::store($redis)->set($key,$value);
    }

    public function getData($key)
    {
        $redis = $this->getRedis();

        return Cache::store($redis)->get($key);
    }
    
    public function getRedis()
    {
        if(!$this->redisName){
            throw new \Exception('未启用redis');
        }
        
        return $this->redisName;
    }
}