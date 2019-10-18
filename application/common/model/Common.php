<?php
/**
 *  用户管理
 */

namespace app\common\model;

use think\Model;

class Common extends Model
{
    protected $pk = 'id';
    
    /**
     * 获取列表
     * @param int $page 第几页
     * @param int $size 每页多少条数据
     */
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
     * 列表
     * @param int $page 第几页
     * @param int $size 每页多少条数据
     * @param array(string) 查询条件, ['uid=1', 'type=2']
     * @param array(string) 排序条件, ['id desc']
    */
    public static function page2( $page=1, $size=15, $where=[], $order=[] ){

        if(null==$order){
            array_push($order,'id DESC');
        }

        $result['data'] =  self::where( implode(' and ',$where) )->field('*')->limit( ($page-1)*$size, $size)->order( implode(',',$order) )->select();
 
        $result['size'] = $size;
        
        return $result;
    }

}