<?php
/**
 * 设备
 * User: maxthink
 * Date: 2019. 4 .25
 */

namespace app\common\model;
 
class Devtype extends Common {

    protected $name = 'devtype';
    
    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['createtime'];
    
    public function setCreatetimeAttr()
    {
        return request()->time();
    }
    
    protected $update = ['updatetime'];
    
    public function setUpdatetimeAttr()
    {
        return request()->time();
    }

    const ISLOCK_NO = 0;
    const ISLOCK_YES = 1;

    static $lock_desc = [
        self::ISLOCK_NO => '否',
        self::ISLOCK_YES => '是',
    ];
    
    
}