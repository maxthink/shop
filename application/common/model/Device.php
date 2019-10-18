<?php
/**
 * 设备
 * User: maxthink
 * Date: 2019. 4 .25
 */

namespace app\common\model;

class Device extends Common {

    public $name = 'device';

    public $table = 'bg_device';
    
    protected $insert = ['createtime'];
    
    const ISLOCK_NO = 0;
    const ISLOCK_YES = 1;

    static $lock_desc = [
        self::ISLOCK_NO => '否',
        self::ISLOCK_YES => '是',
    ];
    
    public function setCreatetimeAttr()
    {
        return time();
    }
    
    public static function getimeibyid($dev_id)
    {
        return self::where('id='.$dev_id)->value('imei');
    }

    /**
     * 获取列表
     * @param int $page 第几页
     * @param int $size 每页多少条数据
     */
    public function list_admin( $page=1, $size=15, $where=[], $order=[] ){

        if(null==$order){
            array_push($order,'id DESC');
        }

        $result['data'] =  self::query('select SQL_CALC_FOUND_ROWS d.*,u.realname from '.$this->table.' d left join h_user u on d.userid=u.id where '. implode(' and ',$where).' order by lasttime desc limit '.($page-1)*$size.','.$size);;
        //echo self::getlastsql();
        $sum = self::query('select found_rows() as coun ');
        
        $result['count'] = $sum[0]['coun'];
        return $result;
    }

}