<?php
/**
 *  优惠券
 */

namespace app\common\model;

class Coupon extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_coupon';
    
    /**
     * 	获取优惠券列表, 根据用户id获取券领取状态
     */
    public static function getlist($uid)
    {
    	return self::query('select c.*,cr.uid from h_coupon as c left join h_coupon_record as cr on cr.qid = c.id and uid='.$uid);
    }
}