<?php
/**
 * Created by PhpStorm.
 * User: maxthink
 * Date: 2018/1/3
 * Time: 17:23
 */
/**
 * 菜单编辑类
 */

namespace app\admin\model;

use think\Model;

class Menu extends Model
{
    protected $pk = 'id';

    protected $name='menu';
    
    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['createtime'];
    
    public function setCreatetimeAttr()
    {
        return request()->time();
    }
    
    protected $update = ['updatetime'];
    
    public function setUpdatetimeAttr()
    {
        return request()->time();
    }
    
    /**
     * 获取所有菜单项
     */
    public function getAllmenu(){
	$sql = 'select id,name,router,pid,menuIcon from bg_menu where isMenu&1 order by pid asc, orderNumber asc';
	return $this->query($sql);
    }
}