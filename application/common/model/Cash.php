<?php
/**
 *  提现
 */
namespace app\common\model;

class Cash extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_cash';
    
    CONST CASH_APPLY = 1;   //申请提现, 客服审核
    CONST CASH_FINANCE = 2;    //客服通过, 财务审核
    CONST CASH_REFUSE = 4;  //审核拒绝
    CONST CASH_PASS = 8;   //财务通过, 等待打款
    CONST CASH_PAYED = 16;   //已打款
    
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

    //计算审核中和已打款的钱数
    public static function moneyused($uid)
    {
        $status = self::CASH_APPLY + self::CASH_PASS + self::CASH_PAYED + self::CASH_FINANCE ;
        return self::where('uid='.$uid.' and status&'.$status)->sum('money');
    }

    //
    public function list_admin( $page=1, $size=15, $where=[], $order=[] ){

        if(null==$order){
            array_push($order,'id DESC');
        }

        if( is_int($page) && $page>0 ){

            $result['data'] =  self::query('select SQL_CALC_FOUND_ROWS c.*, u.realname from '.$this->table.' c left join h_user u on c.uid=u.id where '. implode(' and ',$where).' order by c.id desc limit '.($page-1)*$size.','.$size );
            $sum = self::query('select found_rows() as coun ');
        
            $result['count'] = $sum[0]['coun'];
            return $result;

        } else {

            return self::query('select SQL_CALC_FOUND_ROWS c.*, u.realname from '.$this->table.' c left join h_user u on c.uid=u.id where '. implode(' and ',$where).' order by c.id desc ' );
        }
    }

    public static function list_client( $page=1, $size=15, $where=[], $order=[] ){
        if(null==$order){
            array_push($order,'id DESC');
        }

        $result['data'] =  self::where( implode(' and ',$where) )->field('*, from_unixtime(timeline) time')->limit( ($page-1)*$size, $size)->order( implode(',',$order) )->select();
 
        $result['size'] = $size;
        
        return $result;
    }


    

}