<?php
/**
 * 角色管理
 * @author luyanmin <maxthink@163.com>
 */
namespace app\admin\controller;

use app\admin\model\AdminRole;

use think\Db;
use think\facade\Cache;
use think\Validate;
use think\facade\Request;
use think\facade\Session;

class Role extends Common
{
    /**
     * 角色管理首页
     */
    public function index()
    {
        if( Request::instance()->isPost() ){    //获取角色数据
            if( $serchkey = input('post.keyword/s') ){
                $list = Db::name('role')->field('id,name,createtime,remark')->where(' name like "%'.$serchkey.'%"')->select();
            }else {
                $list = Db::name('role')->field('id,name,createtime,remark')->select();
            }
            
            return apiJson($list);
        } else {    //解析模板            
            return $this->fetch();
        }
        
    }
    
    /*
     * 添加角色
     */
    public function add()
    {
        if ( Request::instance()->isPost() ) {
            $data = input('param.');
            $validate = new Validate([
               'name'=>'require',
            ]);
            if (!$validate->check($data)) {
                //exit(json_encode(['status' => 0, 'msg' => $validate->getError()]));
                return apiJson('',1, $validate->getError() );
            }

            $role = new AdminRole();
            if ($role->data($data,true)->save()) {
                return apiJson('',0,'添加成功');
            } else {
                return apiJson('',1,'添加失败,请稍后重试');
            }
            
        } else {
            return apiJson('',1,'参数错误');
        }
    }

    /**
     * 修改角色信息
     */
    public function update()
    {
        if ( Request::instance()->isPost() ){
            
            $data = input('param.');
            $validate = new Validate([
                'name'=>'require',
                'id'=>'require',
            ]);
            if (!$validate->check($data)) {
                return apiJson('',1,$validate->getError() );
            }

            $role = new AdminRole();
            if (false !== $role->save($data,['id' => $data['id']])) {
                //清除缓存
                Cache::clear('auth');
                return apiJson('',0,'更新成功');
            } else {
                return apiJson('',1,'更新失败,请稍后重试');
            }
            
        } else {
            return apiJson('',1,'参数错误');
        }
    }

    /**
     * 删除角色信息
     */
    public function del()
    {
        $id = input('param.roleId/d',0);
        $role = AdminRole::get($id);
        if ( $role && $role->delete()) {
            return ['status' => 1, 'msg' => '删除成功'];
        } else {
            return ['status' => 0, 'msg' => '删除失败'];
        }
    }
    
    /**
     * 权限管理
     */
    public function authtree()
    {
        //获取权限列表
        if ( Request::instance()->isGet() ){
            $roleId = input('get.roleId/d',0);
            if( 0 == $roleId ){
                return apiJson('',1,'参数错误');
            }
            
            //todo 拼接权限树形数据
            $menu = db('menu')->field('id, name, pid')->select();   //获取菜单列表
            $mids = db('role')->field('power')->find($roleId);      //获取权限存的 菜单id 们,一个字符串
            $power = explode(',', $mids['power']);                           //获取权限存的id, 数组形式

            // 检查权限, 有权限就默认 checked 
            foreach ($menu as $key=>$val ) {
                if( in_array($val['id'], $power) ){
                    $menu[$key]['checked'] = true;
                                       
                }
                $menu[$key]['open'] = true; 
            }
            //print_r($menu); die;
            return apiJson($menu);
            
        }
        
        //设置权限列表
        if ( Request::instance()->isPost() ){
            
            $roleId = input('post.roleId/d',0);
            if( 0 == $roleId ){
                return apiJson('',1,'参数错误');
            }
            
            $authIds = input('post.authIds',0);
            
            if( preg_match('/^\[(\d+,?)*\]$/', $authIds) ) {    //匹配: [1,11,112,113,114,4,117] [12] [] 三种形式

                $_authIds = trim($authIds, '[');
                $authIds = trim($_authIds, ']');
                
                $role = AdminRole::get($roleId);
                
                if( $role->power == $authIds ) {
                    return apiJson('',1,'权限相同,无须保存');
                }
                
                $role->power = $authIds;
                if( $role->save() ) {
                    $uid = Session::get('userinfo.id');
                    Cache::rm('auth_'.$uid);
                    Cache::rm('menu_'.$uid);
        
                    Cache::tag('auth')->set('auth_'.$uid, get_power_by_uid($uid) );
                    Cache::tag('menu')->set('menu_'.$uid, getMenuByRid($uid) );
                    return apiJson('',0,'保存成功');
                } else {
                    return apiJson('',1,'保存错误');
                }
            } else {
                return apiJson('',1,'参数错误');
            }
        }
            
    }
}
