<?php
/**
 *  用户设备绑定关系模型
 */

namespace app\common\model;

class Bind extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_bind';
    

    const BIND_APPLY  = 1;  //绑定申请
    const BIND_PASS   = 2;  //绑定通过
    const BIND_REFUSE = 4;  //驳回
    const BIND_UNBIND = 8;  //解绑

    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['timeline'];
    
    public function setTimelineAttr()
    {
        return request()->time();
    }

    /**
     * 根据微信id获取
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getByWxuid($uid=0 )
    {
        return self::where(['wx_uid'=>$uid])->find();
    }

    /**
     * 根据微信id获取最后一条申请记录
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getlastByWxuid($uid=0, $status=self::BIND_APPLY )
    {
        //返回状态为 1,申请, 2 通过, 4驳回
        $status = self::BIND_APPLY+self::BIND_REFUSE ;
        return self::where(' wx_uid='.$uid.' and status&'.$status )->order('id desc ')->find();
    }

    /**
     * 根据 微信id 设备id, 获取最后申请记录
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getlastByWxuidandDeviceid( $did, $uid )
    {
        //返回状态为 1,申请, 2 通过, 4驳回
        return self::where(' wx_uid='.$uid.' and device_id='.$did )->order('id desc')->find();
    }
    

    /**
     * 根据设备id找
     * @param  [type] $device_id [description]
     * @return [type]            [description]
     */
    public static function getByDeviceid( $did )
    {
        return self::where('device_id='.$did )->order('id desc')->find();
    }

    /**
     * 根据设备id找该设备最后一次操作记录
     * @param  [type] $device_id [description]
     * @return [type]            [description]
     */
    public static function getLastModifyByDeviceid( $did )
    {
        return self::where('device_id='.$did )->order('timeline desc')->find();
        //$res = $this->query('select * from '.$this->table.' where device_id='.$device_id.' order by timeline desc limit 1');
        //return $res[0] ?? null;
    }



    /**
     * 添加绑定申请
     * @param int $wx_uid    微信用户id
     * @param int $device_id 设备id
     * @param int $status    [description]
     * @param int $p_uid 推荐人id
     */
    public static function addBind($wx_uid, $realname, $device_id, $p_uid=0 )
    {
        $bind = [ 'wx_uid'=>$wx_uid, 'realname'=>$realname, 'p_uid'=>$p_uid, 'device_id'=>$device_id, 'status'=> self::BIND_APPLY,'timeline'=>time(), 'time_apply'=>time() ];
        return self::create($bind);
    }

    //管理后台用列表
    public function list_admin( $page=1, $size=15, $where=[], $order=[] )
    {
        if(null==$order){
            array_push($order,'id DESC');
        }

        $result['data'] =  self::query('select SQL_CALC_FOUND_ROWS b.id id, b.wx_uid, u.nickname, u.avatar, b.realname, b.device_id, b.p_uid, ut.realname p_realname, d.imei, b.timeline, b.time_verify, b.time_apply, b.time_unbind, b.`status`,b.comment from '.$this->table.' b left join h_user u on b.wx_uid=u.id left join h_user ut on b.p_uid=ut.id left join bg_device d on b.device_id=d.id where '. implode(' and ',$where).' order by id desc limit '.($page-1)*$size.','.$size);
        //echo self::getlastsql();
        $sum = self::query('select found_rows() as coun ');
        
        $result['count'] = $sum[0]['coun'];
        return $result;
    }


}