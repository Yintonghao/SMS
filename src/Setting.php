<?php

namespace Mydom\Sms;

use think\facade\Cache;
use think\facade\Db;

/**
 * 配置设置
 */
class Setting extends Base
{
    public function __construct($is_table = false)
    {
        parent::__construct();
        
        if($is_table){
            $this->createTable();
        }
    }

    /**
     * 创建表结构
     * @return void
     */
    private function createTable()
    {
        $res = $this->getShowTable();
        if(!$res) {
            $newTable = sprintf($this->table, $this->tableName);
            Db::query($newTable);
        }
    }
    
    /**
     * 查询数据表是否存在
     * @return mixed
     */
    private function getShowTable()
    {
        return Db::query('SHOW TABLES LIKE '."'".$this->tableName."'");
    }

    /**
     * 枚举列表
     * @return array|array[]|mixed
     */
    public function getData()
    {
        return $this->Enumdata;
    }

    /**
     * 查询所有配置
     */
    public function getConfigSms()
    {
        $redis = Cache::store($this->redisName);
        $data = $redis->get($this->config_key_data);

        if(!$data) {
            $data = $this->getConfigList();
            $redis->set($this->config_key_data,json_encode($data,256));
        }else{
            $data = json_decode($data,true);
        }
        return $data;
    }

    private function getConfigList()
    {
        list($typeArr, $business) = $this->Enumdata;
        $typeArr = array_column($typeArr, null, 'type');
        $business = array_column($business, null, 'business');
        $data = Db::table($this->tableName)
            ->order('sort asc')
            ->select()
            ->each(function ($item) use ($typeArr, $business) {
                $item['type_name'] = $typeArr[$item['type']]['name'];
                $item['business_name'] = $business[$item['business']]['name'];
                return $item;
            })->toArray();

        return $data;
    }

    /**
     * 更新或添加
     * @param $save
     * @return int|string
     */
    public function saveConfig($save)
    {
        if(!isset($save['update_time' ])){
            $save['update_time' ]= time();
        }

        $res = Db::table($this->tableName)->replace()->insert($save);

        Cache::store($this->redisName)->set($this->config_key_data,json_encode($this->getConfigList(),256));

        return $res;
    }

    /**
     * 删除
     * @param $type
     * @param $business
     * @return int
     * @throws \think\db\exception\DbException
     */
    public function deleteConfig($type,$business)
    {
        $res =  Db::table($this->tableName)
            ->where('type',$type)
            ->where('business',$business)
            ->delete();

        Cache::store($this->redisName)->set($this->config_key_data,json_encode($this->getConfigList(),256));

        return $res;
    }























}