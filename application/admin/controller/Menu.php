<?php

/**
 * 菜单管理
 */

namespace app\admin\controller;

use app\admin\model\Menu as menuModel;
use think\facade\Session;
use think\facade\Cache;
use think\facade\Request;

class Menu extends Common {

    /**
     * 菜单管理首页
     * @return mixed
     */
    public function index() {
        if (Request::instance()->isAjax()) { //获取菜单数据
            $list = menuModel::all();

            return apiJson($list);
        } else {
            $Mmenu = new menuModel();
            $menu = $Mmenu->getAllmenu();
            //var_dump($menu);
            $this->assign('menu', $menu);
            return $this->fetch();
        }
    }

    /*
     * 添加菜单
     */

    public function add() {
        if (request()->isPost()) {
            //修改处理
            $params = input('post.');

            //验证规则
            //$result = $this->validate($params, 'app\admin\validate\Admin');
            //if ($result !== true) {
            //    return ['status' => 0, 'msg' => $result, 'url' => ''];
            //}

            $Mmenu = new menuModel();
            $Mmenu->pid = $params['pid'];
            $Mmenu->name = $params['name'];
            $Mmenu->isMenu = $params['isMenu'];
            $Mmenu->router = $params['router'];
            $Mmenu->menuIcon = $params['menuIcon'];
            $Mmenu->orderNumber = $params['orderNumber'];
            $Mmenu->createtime = time();

            if ($Mmenu->save()) {
                return apiJson([], 0, '添加成功');
            } else {
                return apiJson([], 1, '添加失败');
            }
        } else {
            return apiJson([], 1, '参数错误');
        }
    }

    /**
     * 修改菜单项
     */
    public function update() {
        if (request()->isPost()) {

            $params = input('post.');

            //验证规则
            //$result = $this->validate($params, 'app\admin\validate\Admin.edit');
            //if ($result !== true) {
            //  return ['status' => 0, 'msg' => $result, 'url' => ''];
            //}

            $Mmenu = menuModel::get($params['id']);

            if ($Mmenu->data($params, true)->save()) {
                $uid = Session::get('userinfo.id');
                Cache::rm('auth_'.$uid);
                Cache::rm('menu_'.$uid);

                Cache::tag('auth')->set('auth_'.$uid, get_power_by_uid($uid) );
                Cache::tag('menu')->set('menu_'.$uid, getMenuByRid($uid) );
                return apiJson([], 0, '修改成功');
            } else {
                return apiJson([], 1, '修改失败');
            }
        } else {
            return apiJson([], 1, '参数错误');
        }
    }

    /**
     * 删除用户信息
     */
    public function del() {
        $id = input('param.id/d', 0);
        $res=\app\admin\model\Menu::where('id','=',$id)->delete();
        if ($res) {
            return apiJson([], 0, '删除成功');
        }else {
            return apiJson([], 1, '删除失败');
        }
    }

}
