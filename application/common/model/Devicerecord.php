<?php
/**
 *  设备使用记录表
 *   
 *  @记录设备使用
 */

namespace app\common\model;

class Devicerecord extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_device_record';
    
    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['createtime','code'];
    
    public function setCreatetimeAttr()
    {
        return request()->time();
    }

    public function setCodeAttr()
    {
        return date('YmdHis',request()->time() ).rand(100,999);
    }
    
    /**
     * 
     * @param type $uid     用户id
     * @param type $bill_id 账单id
     * @param type $sum     次数
     * @param type $long    时长
     * @param type $devid   设备id
     * @param type $source  来源, 0系统赠送, 1购买, 2用户赠送
     * @return type
     */
    public static function add($uid, $bill_id, $sum=1, $long=900, $devid=0, $source=1 )
    {

        for( $i=0; $i<$sum; $i++  ) {
            self::create(['user_id'=>$uid,'bill_id'=>$bill_id,'dev_id'=>$devid,  'long'=>$long ,'source'=>$source] );
        }
    }
    
    /**
     * 按照人员id获取设备使用记录列表, 使用地方: 1,手机端展示个人记录用
     * @param type $uid
     * @param type $cur_page
     * @param type $size
     * @return type
     */
    public static function getRecordByUid($uid, $cur_page=1, $size=15 )
    {
        $offect = ($cur_page-1)*15;
        $list = self::where(['user_id'=>$uid])->field('SQL_CALC_FOUND_ROWS id,code,bill_id,source,long,createtime,status,usetime')->limit($offect, $size)->order('id desc')->select();
 
        $sum = self::query('select found_rows() as coun ');
        //$page = ['size'=>15, 'curpage'=>$cur_page,'sum'=>$sum];
        //return ['page'=>$page, 'data'=>$list ];
        return ['limit'=>$size, 'data'=>$list ];
    }

    /**
     * 获取用户最后一条记录, 默认执行
     * @param  integer $uid 用户id, 
     * @return [mix]       最后一条记录的数据对象
     */
    public static function getLastOne(int $uid=0)
    {
        if(0!==$uid){
            return self::where('user_id',$uid)->order('id desc ')->find();
        }else{
            return self::where()->order('id desc ')->find();
        }
        
    }
    
}