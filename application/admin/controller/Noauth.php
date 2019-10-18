<?php
/**
 * 通用, 跟common 比较, 不需要权限验证的都继承此 controller
 */
namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Noauth extends Controller
{
    /*
     * 数据表前缀
     */
    protected $prefix = '';
    
    protected $model;   //对应默认 模型
    
    function __construct()
    {
        parent::__construct();
        $this->prefix = config('database.prefix');
        $this->checkLogin();
    }

    /**
     * 验证是否登录
     */
    protected function checkLogin(){

        //验证是否登录成功
        if (!Session::has('userinfo') || !$uname = Session::get('userinfo.name')) {
            if( request()->isAjax() ){
                header('<script>top.location.href="/"</script>');
                return apiJson('', 2, '登录失效, 请重新登录系统 ! ');
            }else {
                $this->redirect('login/');
            }

            
        }
        //登录是否过期 无操作1h即为过期
        $login_time = Session::get('userinfo.login_time');
        if ( request()->time() - $login_time > 3600 ) {
            Session::clear();
            if( request()->isAjax() ){
                header('<script>top.location.href="/"</script>');
                return apiJson('', 2, '登录失效, 请重新登录系统 ! ');
            }else {
                $this->redirect('login/');
            }
        }
        Session::set('userinfo.login_time',time());
        $this->assign('username', $uname);
    }
    
    /**
     * 一般用户修改密码
     */
    public function modifypasswd()
    {
        
    }

    
}
