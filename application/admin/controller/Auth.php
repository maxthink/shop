<?php
/**
 * 通用, 跟common 比较, 不需要权限验证的都继承此 controller
 */
namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Auth extends Controller
{
    /*
     * 数据表前缀
     */
    protected $prefix = '';
    
    protected $model;   //对应默认 模型
    
    function __construct()
    {        
        $this->checkLogin();
        parent::__construct();
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
                //return true;
            }
            
        }
    }
    
    //判断某一权限是否拥有
    public function verify()
    {
        $authStr = input('post.authstr','','');

        $ok = has_auth_by_route($authStr);
        if($ok){
            return apiJson('', 0, '');
        }else{
            return apiJson('', 1, '');
        }
    }



    
}
