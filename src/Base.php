<?php

namespace Mydom\Sms;

use app\common\lib\Dok;
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business` int(6) NOT NULL DEFAULT '0' COMMENT '业务ID',
  `type` int(6) NOT NULL DEFAULT '0' COMMENT '1-手机号  2-IP 3-UID',
  `second` int(6) NOT NULL DEFAULT '0' COMMENT '发送频率，秒',
  `num` int(6) NOT NULL DEFAULT '0' COMMENT '发送限制，次数',
  `c` tinyint(4) DEFAULT NULL COMMENT '1-时  2-分  3-秒',
  `sort` int(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='短信频发设置';";
    //枚举数据
    public $Enumdata = [];
    public $redisName = '';
    public $config_key_data = '__SMS_CONFIG__';
    public function __construct()
    {
        $database = Config::get('database.connections.mysql');
        $this->database = $database['database'];
        $this->prefix = $database['prefix'];
        $this->tableName = $this->prefix.= $this->tableName;
        $this->Enumdata = (new Dok())->config;

        $store = Config::get('cache.stores');
        if(!isset($store['redis'])){
            throw new \Exception('请在config/cache.php文件中增加redis配置。');
        }
        $this->redisName = 'redis';
    }
}