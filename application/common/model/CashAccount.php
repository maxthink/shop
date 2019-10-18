<?php
/**
 *  提现账户
 */
namespace app\common\model;

class CashAccount extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_cash_account';
    
    
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
    public static function getListByUid($uid)
    {
        return self::where('uid='.$uid)->select();
    }

    public static function getCountByUid($uid)
    {
        return self::where('uid='.$uid)->count();
    }

    public static function getDefault($uid)
    {
        $default = self::where(' uid='.$uid.' and `default`=1 ')->order('id desc')->find();
        if($default){
            return $default;
        }else{
            return self::where(' uid='.$uid)->order('id desc ')->find();
        }
    }

    

}