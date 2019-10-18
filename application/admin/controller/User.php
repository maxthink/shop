<?php
/**
 * 前端用户信息 控制器
 */
namespace app\admin\controller;

use think\facade\Request;
use app\common\model\User as Muser;

class User extends Common
{

    /**
     * 用户列表页
     */
    public function index()
    {
        if( Request::instance()->isPost() )     //post 方式, 获取数据
        {
            $page = input('page',1,'intval');
            $limit = input('limit',10,'intval');
            
            $list = Muser::page($page,$limit);
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面
            
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

            $user = Muser::get($params['id']);
            $user->level = $params['level'];


            if (false !== $user->save()) {
                return apiJson(['user/index'],0,'修改成功');
            } else {
                return apiJson('',1,'修改失败');
            }
        } else {
            return apiJson('',1,'没有参数');
        }
    }


}
