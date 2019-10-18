<?php
/**
 *  账单
 */

namespace app\common\model;

class Bill extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_bill';
    
    CONST PAY_NO = 0;    //支付订单生成
    CONST PAY_OK = 1;    //支付完成
    CONST PAY_BACK = 2;     //申请退款
    CONST PAY_BACKOK = 4;   //退款ok
    
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
     * 获取列表, 后台统计每台设备总数用
     * @param int $page 第几页
     * @param int $size 每页多少条数据
     * @param array(string) 查询条件, ['uid=1', 'type=2']
    */
    public function count( $page=1, $size=15, $where=[] ){

        $offect = ($page-1)*15;
        $result = [];
        if($where) {
            $result['data'] = self::query('select SQL_CALC_FOUND_ROWS dev_id, sum(price) as money from '. $this->table.' where '.implode(' and ',$where).' group by dev_id limit '.$offect.','.$size );
        } else {
            $result['data'] = self::query('select SQL_CALC_FOUND_ROWS dev_id, sum(price) as money from '. $this->table.' group by dev_id limit '.$offect.','.$size );
        }
        
        $sum = self::query('select found_rows() as coun ');
        $result['count'] = $sum[0]['coun'];
        
        return $result;
    }


    //获取某个用户当日总收入
    public static function uidCount($uid, $timestart=0, $timeend=0 )
    {
        $where = '';
        if( 0<$timestart && $timeend>0 )    //区间
        {
            $where = ' and createtime>='.$timestart.' and createtime<'.$timeend;
        }else if( 0<$timestart && 0==$timeend )    //大于
        {
            $where = ' and createtime>='.$timestart;
        }else if( 0==$timestart && 0<$timeend )     //小于
        {
            $where = ' and createtime<'.$timeend;
        }

        $result = self::query('select sum(price) price from h_bill where owner_uid='.$uid.' and status=1 '.$where);
        //echo self::getlastsql();
        return $result[0]['price'];
    }

    public static function todayCount($uid)
    {
        return self::uidCount($uid, strtotime(date('Y-m-d')) );
    }

    public static function yestodayCount($uid)
    {
        return self::uidCount($uid, strtotime(date('Y-m-d'))-86400, strtotime(date('Y-m-d')) );
    }

    public static function allCount($uid)
    {
        return self::uidCount($uid, 0, strtotime(date('Y-m-d')) );
    }

    /**
     * 获取设备管理员账单列表, 第一版账单模块在用
     * @param int $page 第几页
     * @param int $size 每页多少条数据
     */
    public static function list_admin( $page=1, $size=15, $where, $order=[] ){

        
        if(null==$order){
            $order = 'id DESC';
        }

        $result['data'] = self::query('select SQL_CALC_FOUND_ROWS b.id id, b.user_id,b.owner_uid, b.price, b.dev_id, b.meal_id, b.`status`, b.createtime, u.realname, d.imei from h_bill b left join h_user u on b.owner_uid=u.id left join bg_device d on b.dev_id=d.id where '.implode(' and ',$where).' order by '.$order.' limit '.($page-1)*$size.','.$size);
        //echo self::getlastsql();
        
        $sum = self::query('select found_rows() as coun ');
        
        $result['count'] = $sum[0]['coun'];
        return $result;
    }
    public static function page( $page=1, $size=15, $where=[], $order=[] ){

        if(null==$order){
            array_push($order,'id DESC');
        }

        $result['data'] =  self::where( implode(' and ',$where) )->field('SQL_CALC_FOUND_ROWS *')->limit( ($page-1)*$size, $size)->order( implode(',',$order) )->select();
        //echo self::getlastsql();
        $sum = self::query('select found_rows() as coun ');
        
        $result['count'] = $sum[0]['coun'];
        return $result;
    }

    /**
     * 获取设备管理员账单列表
     * @param int $page 第几页
     * @param int $size 每页多少条数据
     */
    public static function list1( $uid, $page = 1, $size = 15, $timestart = 0, $timeend = 0 )
    {
        $where = '';
        if( 0<$timestart && $timeend>0 )
        {
            $where = ' and b.createtime>='.$timestart.' and b.createtime<'.$timeend;
        }
        else if( 0<$timestart )
        {
            $where = ' and b.createtime>='.$timestart;
        }
        else if( 0==$timestart && 0<$timeend )     //小于
        {
            $where = ' and b.createtime<'.$timeend;
        }

        //前端为了兼容ios安卓, 时间在这里处理好.
        $result['data'] = self::query('select b.user_id, b.price, b.dev_id, b.meal_id, b.`status`, from_unixtime(b.createtime) createtime, u.nickname from h_bill b left join h_user u on b.user_id=u.id where b.owner_uid='.$uid.$where.' order by b.id desc  limit '.($page-1)*$size.','.$size);
        
        $result['size'] = $size;
        return $result;
    }

    public static function todaylist($uid, $page=1, $size=15 )
    {
        return self::list1($uid,$page, $size, strtotime(date('Y-m-d')) );
    }

    public static function yestodaylist($uid, $page=1, $size=15 )
    {
        return self::list1($uid,$page, $size, strtotime(date('Y-m-d'))-86400, strtotime(date('Y-m-d')) );
    }

    public static function historylist($uid, $page=1, $size=15 )
    {
        return self::list1($uid,$page, $size, 0, strtotime(date('Y-m-d'))-86400 );
    }


    
    //根据微信 trade_no 返回订单信息
    public static function getBillByPaycode($wx_trade_no)
    {
        return self::where('code',$wx_trade_no)->find();
    }       

}