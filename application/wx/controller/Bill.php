<?php

/**
 * 微信后台
 *
 */

namespace app\wx\controller;


use app\common\model\User;
use app\common\model\Bill as Mbill;
use app\common\model\Income;
use app\common\model\Cash;


use think\facade\Session;


class Bill extends Common {

    private $userid = null;

    protected $user = null; //用户信息


    //获取用户id
    protected function initialize()
    {
        //echo '<br>Manage init 初始化: '.memory_get_usage();
        $this->oauthLogin(); //微信登录
        $this->userid = Session::get('wechat_user');
        //$this->userid = 23;
        $this->user = User::get($this->userid);

        if( !$this->user ){
            Session::delete('wechat_user');
            $this->oauthLogin(); //微信登录
        }

        if( $this->user->level <= 0 ){
            return '<script type="text/javascript">alert("对不起, 你不是管理员");</script>';
        }
    }

    //页面显示
    public function index()
    {
        //$this->assign('user',$user);
        
        return $this->fetch('manage/index');
    }

    
}
