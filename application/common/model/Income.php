<?php
/**
 *  收入
 */

namespace app\common\model;

class Income extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_income';
    
    CONST INCOME_SHOP  = 1;  //商户
    CONST INCOME_AGENT = 2;  //代理
    CONST INCOME_PATNER= 4;  //合伙人
    CONST INCOME_UNICOME=8; //联创

    //获取某个用户当日总收入
    public static function uidCount($uid, $timestart=0, $timeend=0 )
    {
        $where = '';
        if( 0<$timestart && $timeend>0 )    //区间
        {
            $where = ' time >='.$timestart.' and time <'.$timeend;
        }else if( 0<$timestart && 0==$timeend )    //大于
        {
            $where = ' time >='.$timestart;
        }else if( 0==$timestart && 0<$timeend )     //小于
        {
            $where = ' time <'.$timeend;
        }else{
            $where = '1=1';
        }

        $result = self::query('select sum(money) money from h_income where uid='.$uid.' and '.$where);
        //echo self::getlastsql();
        return $result[0]['money'];
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
        return self::uidCount($uid );
    }


    /**
     * 获取设备管理员账单列表
     * @param int $page 第几页
     * @param int $size 每页多少条数据
     */
    public static function list1( $uid, $page=1, $size=15, $timestart=0, $timeend=0 ){

        $where = '';
        if( $timestart>0 && $timeend>0 )
        {
            $where = ' and time>='.$timestart.' and time<'.$timeend;
        }else if( $timestart>0 && $timeend==0 )
        {
            $where = ' and time>='.$timestart;
        }elseif( $timestart==0 && $timeend>0){
            $where = ' and time<='.$timeend;
        }

        $result['data'] = self::query(' select id,bid,type,money,from_unixtime(time) time from h_income  where uid='.$uid.$where.' order by id desc limit '.($page-1)*$size.','.$size);

        //$result['data'] =  self::where( 'uid ='.$uid )->field('SQL_CALC_FOUND_ROWS *')->limit( ($page-1)*$size, $size)->order( 'createtime desc' )->select();
        //echo self::getlastsql();
        //$sum = self::query('select found_rows() as coun ');
        
        $result['size'] = $size;
        return $result;
    }

    public static function yestodaylist($uid, $page=1, $size=15 )
    {
        return self::list1($uid,$page, $size, strtotime(date('Y-m-d'))-86400, strtotime(date('Y-m-d')) );
    }

    public static function todaylist($uid, $page=1, $size=15 )
    {
        return self::list1($uid,$page, $size, strtotime(date('Y-m-d')) );
    }

    public static function historylist($uid, $page=1, $size=15 )
    {
        return self::list1($uid,$page, $size, 0, strtotime(date('Y-m-d')) );
    }


    /**
     * 获取 列表
     * @param int $page 第几页
     * @param int $size 每页多少条数据
     */
    public static function list_admin( $page=1, $size=15, $where  ){

        $result['data'] = self::query(' select SQL_CALC_FOUND_ROWS i.id id,i.bid,i.uid,i.type,i.money,i.time, u.realname, b.dev_id  from h_income i left join h_user u on i.uid=u.id left join h_bill b on i.bid=b.id  where '.implode(' and ',$where).' order by id desc limit '.($page-1)*$size.','.$size);

        $sum = self::query('select found_rows() as coun ');
        
        $result['count'] = $sum[0]['coun'];

        if(count($where)>1){    //默认带 1=1, 所以得是大于0的
            $sum = self::query(' select sum(i.money) ddd from h_income i left join h_bill b on i.bid=b.id where '.implode(' and ',$where));
            $result['sum'] = $sum[0]['ddd'];
        }
         
        return $result;
        
    }

   

}