<?php
/**
 *  优惠券领取记录
 */

namespace app\common\model;

class Couponrecord extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_coupon_record';
    
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
     * 判断用户是否领取该优惠券
     * @param  int $uid 用户id
     * @param  int $qid 优惠券id
     * @return bool      [description]
     */
    public static function istake($uid, $qid){
    	$result = self::where(['uid'=>$uid,'qid'=>$qid])->find();
    	if($result){
    		return true;
    	} else {
    		return false;
    	}
    }

    public static function add($uid, $qid){
    	return self::create( ['uid'=>$uid, 'qid'=>$qid] );
    }

    
}