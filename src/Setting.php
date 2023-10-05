<?php

namespace Mydom\Sms;

use think\facade\Cache;
use think\facade\Db;

/**
 * 配置设置
 */
class Setting extends Base
{
    /**
     * 初始化调用时两个参数都true。初始化完成后。改为false即可
     * @param bool $is_table 是否创建表结构
     * @param bool $is_default_data 是否创建默认配置数据
     */
    public function __construct($is_table = false,$is_default_data = false)
    {
        parent::__construct();
        if($is_table){
            $this->createTable();
        }

        if($is_default_data){
            $this->createDefaultData();
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
            $newTable = sprintf($this->table, $this->prefix . $this->tableName);
            Db::query($newTable);
        }
    }

    /**
     * 创建配置默认数据
     * @return void
     */
    private function createDefaultData()
    {
        $res = $this->getShowTable();
        if($res){
            $time = time();
            $newTable = sprintf($this->defaultSql,$this->database,$this->prefix.$this->tableName,$time,$time);
            Db::query($newTable );
        }else{
            $this->createTable();
            $this->createDefaultData();
        }
    }

    /**
     * 查询数据表是否存在
     * @return mixed
     */
    private function getShowTable()
    {
        return Db::query('SHOW TABLES LIKE '."'".$this->prefix.$this->tableName."'");
    }

    /**
     * 读取配置
     * @return array
     */
    public function getConfig()
    {
        $data = $this->getData($this->dataKey);

        if(!$data){
            $data = Db::table($this->prefix.$this->tableName)->where('id = 1')->find();
            $this->setData($this->dataKey,json_encode($data,256));
        }else{
            $data = json_decode($data,true);
        }

        return $data;
    }

    /**
     * 修改配置
     * @param $data
     * @return int
     */
    public function editConfig($data)
    {
        if(!isset($data['update_time']) || empty($data['update_time'])){
            $data['update_time'] = time();
        }
        $res = Db::table($this->prefix.$this->tableName)->where('id = 1')->update($data);

        $this->setData($this->dataKey,json_encode($data,256));

        return $res;
    }
























}