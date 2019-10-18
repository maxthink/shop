<?php
/**
 *  账单
 */
namespace app\common\model;

class Exchange extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_exchange';
    
    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['createtime'];
    
    public function setCreatetimeAttr()
    {
        return request()->time();
    }
    
    /**
     * 
     */
    public static function moneyall($uid)
    {
        return self::where('uid='.$uid)->sum('money');
        
    }

    

}