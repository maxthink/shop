<?php
/**
 * Created by PhpStorm.
 * User: nango
 * Date: 2018/1/3
 * Time: 17:23
 */

namespace app\admin\model;

use think\Model;
use think\Db;

class Admin extends Model
{
    protected $name = 'admin';
    protected $pk = 'id';
    
    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['createtime'];

    const ISLOCK_NO = 0;
    const ISLOCK_YES = 1;

    static $lock_desc = [
        self::ISLOCK_NO => '否',
        self::ISLOCK_YES => '是',
    ];
    
    public function role()
    {
        return $this->belongsTo('Role', 'role_id');
    }

    public function setLoginipAttr()
    {
        return request()->ip();
    }

    public function setCreatetimeAttr()
    {
        return time();
    }
    
    /**
     * 获取管理员数据
     * @param int $page 第几页，从 1 开始
     * @param int $size 每页数据条数
     */
    public static function getList( $page=0, $size=20 )
    {
        $offect = ($page-1) * $size;
        
        //$sql = 'select a.id, a.username, a.realname, a.email, a.logintime, a.loginip, a.islock, a.createtime, r.`name` as rolename,
        //        from (select * from bg_admin limit '.$offect.','.$size.') as a 
        //        left join bg_role r on a.role_id = r.id';
        $sql = 'select * from bg_admin limit '.$offect.','.$size ;
        return Db::query($sql);
        
        //return Db::name('admin')->field('')->join('role', '')->limit( '\''.$offect.','.$limit.'\'' )->select();
        //Db::name('admin')->limit( '\''.$offect.','.$limit.'\'' )->select();
        //echo db()->getLastSql();
        // 使用闭包查询
        //Db::table('think_article')->limit(10,25)->select();
    }


    /**
     * 密码加盐处理
     * @return string
     */
//    public function setPasswordAttr($value, $data)
//    {
//        return get_password($value, $data['encrypt']);
//    }

}