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
            $newTable = sprintf($this->table, $this->prefix . $this->tableName);
            Db::query($newTable);
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























}