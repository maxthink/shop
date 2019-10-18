<?php
/**
 * 管理员 控制器
 */

namespace app\admin\controller;

use app\admin\model\AdminRole;
use app\admin\model\Admin as adminModel;
use think\facade\Request;
use think\facade\Session;

class Admin extends Common
{

    /**
     * 管理员首页
     * 
     */
    public function index()
    {
        if( Request::instance()->isPost() ) {    //post 方式, 获取数据
            $page = input('get.page',1,'intval');
            $limit = input('get.limit',10,'intval');
            
            $list = adminModel::getList($page,$limit);
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面
            $roleids = AdminRole::all();
            $this->assign('roleids', $roleids);
            return $this->fetch();
        }
        
    }
    
    /*
     * 添加用户
     */
    public function add()
    {
        if ( Request::instance()->isPost() ) {
            
            $params = input('post.');

            //验证规则
            //$result = $this->validate($params, 'app\admin\validate\Admin');
            //if ($result !== true) {
                //return ['status' => 0, 'msg' => $result, 'url' => ''];
            //}
            
            $admin = new adminModel();
            $admin->username = $params['username'];
            $admin->realname = $params['realname'];
            $admin->email = $params['email'];
            //$admin->roleid = array_sum( explode(',',$params['roleId']) );
            $admin->role_id = $params['roleId'];
            $admin->islock = 0; //默认用户不锁定
            $admin->createtime = time();

            $admin->encrypt = get_randomstr();   //6位hash值
            $admin->password = get_password('111111', $admin->encrypt );   //默认密码六个一
            
            if ( $admin->save() ) {
                return apiJson('', 0, '添加成功');
            } else {
                return apiJson('', 1, '添加出错');
            }
        } else {
            
            $this->assign('role', AdminRole::all());
            return $this->fetch();
        }
    }

    /**
     * 修改用户信息
     */
    public function update()
    {
        if ( Request::instance()->isPost() ) {

            $params = input('post.');

            //验证规则
            //$result = $this->validate($params, 'app\admin\validate\Admin.edit');
            //if ($result !== true) {
            //    return ['status' => 0, 'msg' => $result, 'url' => ''];
            //}

            $admin = adminModel::get($params['id']);
            $admin->username = $params['username'];
            $admin->realname = $params['realname'];
            $admin->email = $params['email'];
            $admin->role_id = $params['roleId'];

            if (false !== $admin->save()) {
                return apiJson(['admin/index'],0,'修改成功');
            } else {
                return apiJson('',1,'修改失败');
            }
        } else {
            return apiJson('',1,'没有参数');
        }
    }
    
    /**
     * 锁定用户
     */
    public function lock()
    {
        $id = input('param.userId/d', 0);
        $state = input('param.state/d', '');
        if( 0 == $id ){
            return apiJson('', 1, '参数错误');
        }
        
        //获取管理员对象
        $admin = adminModel::get($id);

        if( adminModel::ISLOCK_YES===$state && $state==$admin->islock ){
            return apiJson('',0,'已是锁定状态'); 
        }elseif( adminModel::ISLOCK_NO===$state && $state==$admin->islock ){
            return apiJson('',0,'已是正常状态'); 
        }
        
        //todo 赋值状态,改变
        $admin->islock = $state;
        
        if( adminModel::ISLOCK_NO===$state ){
            if ( $admin->save()) {
                return apiJson('',0,'解锁成功');
            } else {
                echo $admin::getLastSql();
                return apiJson('', 1, '解锁失败');
            }
        } if( adminModel::ISLOCK_YES===$state ) {
            if ($admin->save()) {
                return apiJson('',0,'锁定成功');
            } else {
                return apiJson('', 1, '锁定失败');
            }
        }
         
    }

    /**
     * 删除用户信息
     */
    public function del()
    {
        $id = input('param.userId/d', 0);
        $admin = adminModel::get($id);
        $admin->islock = adminModel::ISLOCK_YES;
        if ($admin->save()) {
            return apiJson();
        } else {
            return apiJson('', 1, '删除失败');
        }
    }
    
    /**
     * 重置密码
     */
    public function resetpasswd()
    {
        if ( Request::instance()->isPost() ) {
            $params = input('post.');
            $admin = adminModel::get($params['userId']);
            $admin->encrypt = get_randomstr();//6位hash值
            $admin->password = get_password('111111', $admin->encrypt);
            
            if (false !== $admin->save()) {
                return apiJson('',0,'重置密码成功,默认密码为 111111 (六个 1 ) ');
            } else {
                return apiJson('',1,'重置密码出错');
            }
            
        }
    }
    
    /**
     * 修改密码
     */
    public function updatepasswd()
    {
        if ( Request::instance()->isPost() ) {
            $params = input('post.');
            $admin = adminModel::get(Session::get('userinfo.id'));

            $oldpsw = get_password($params['oldPsw'], $admin->encrypt);

            if($oldpsw != $admin->password )
            {
                return json( mcJson( '原密码错误', 1 ) ) ;
            }

            $newpsw = get_password($params['newPsw'], $admin->encrypt);
            if($newpsw == $admin->password )
            {
                return json( mcJson( '新旧密码一样', 1 ) ) ;
            }

            $admin->encrypt = get_randomstr();//6位hash值
            $admin->password = get_password($params['newPsw'], $admin->encrypt);
            
            if (false !== $admin->save()) {
                return json( mcJson( '修改成功',0 ) );
            } else {
                return json( mcJson( '修改出错',1 ) );
            }
            
        } else {
            return $this->fetch('admin_passwd');
        }
    }
}
