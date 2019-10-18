<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\Log;
use think\Controller;
use think\facade\Session;
use think\facade\Cache;
use think\facade\Request;

class Login extends Controller
{
    //登录界面
    public function index(){
        //验证是否登录成功
        if (Session::has('userinfo')) {
            $this->redirect('index/index');
        }
        return $this->fetch();
    }

    //登录操作
    public function login(){
        
        if(!request()->isPost()){
            $this->redirect('index/index');
        }
        $name = input('post.username');
        $passwd = input('post.password');
        $captcha = input('post.captcha');

        if (!$name || !$passwd) {
            return mcJson('用户名和密码不能为空',1);
        }

        //if(!captcha_check($captcha)){
            //return mcJson('请输入正确的验证码',1);
        //}

        $info = Admin::where(['username' => $name])->find();
        $md5_passwd = get_password($passwd,$info['encrypt']);

        if (!$info || $md5_passwd != $info['password']) {
            return mcJson('用户名或密码错误，请重新输入',1);
        }

        if ($info['islock'] == 1) {
            return mcJson('您的账户已被锁定，请联系超级管理员',1 );
        }

        //写入日志
        $log = new Log();
        $log->add( $info['id'], 1,'', $info['username'] );
        
        //todo 最后登录时间在日志里查询. 不做重复记录
        
        //登入成功，存入session
        Session::set('userinfo',['name' => $name,'roleid' => $info['role_id'],'id' => $info['id'],'login_time' => time()]);
        
        //权限存入缓存并设置auth标签
        Cache::tag('auth')->set('auth_'.$info['id'], get_power_by_uid($info['role_id']) );
        
        //菜单存入缓存并设置menu标签
        Cache::tag('menu')->set('menu_'.$info['id'], getMenuByRid($info['role_id']) );

        //return ['status' => 1, 'msg' => '登录成功', 'url' => url('index/index')];
        return mcJson('登录成功');

    }

    //退出
    public function logout(){
        Cache::rm('auth_'.Session::get('userinfo.id'));
        Cache::rm('menu_'.Session::get('userinfo.id'));
        Session::clear();
        if( Request::instance()->isAjax() ) {
            exit(json_encode(array('status' => 1, 'msg' => '退出成功')));
        } else {
            header('location: /admin');
        }
    }
    
    /**
     * 验证码图片
     */
    public function captcha()
    {
        
    }

    /*
     * 空操作
     */
    public function _empty()
    {
        $this->redirect('login/index');
    }
    /**
     * 登录日志
     */
    public function log()
    {
        
    }
}
