<?php
/**
 * 通用, 要权限验证的都必须继承此 controller
 */
namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Common extends Controller
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
        if (!has_auth_by_route()) {
            if (request()->isAjax()) {
                
                exit(json_encode(['status' => 0, 'msg' => '未获取权限，请联系超级管理员开通相应权限！']));
            } else {
                echo '<div style="width:600px;margin:0 auto;margin-top:20%;font-size:26px;font-weight:bolder">未获取权限，请联系超级管理员开通相应权限！</div>';exit;
            }
        }
        $this->assign('web_site',$this->request->domain());
        $this->assign('all_nav', getAllCategory('all'));//获取所有导航
    }

    /**
     * 验证是否登录
     */
    protected function checkLogin(){

        if (request()->isAjax()) {
            //验证是否登录成功
            if (!Session::has('userinfo') || !$uname = Session::get('userinfo.name')) {
                return apiJson('',2);
            }
            //登录是否过期 无操作1h即为过期
            $login_time = Session::get('userinfo.login_time');
            if ( request()->time() - $login_time > 3600 ) {
                Session::clear();
                return apiJson('',2);
            }
        } else {
            //验证是否登录成功
            if (!Session::has('userinfo') || !$uname = Session::get('userinfo.name')) {
                //header('<script>top.location.href="/"</script>');
                //$this->redirect('login/');
                header('location: /admin');
            }
            //登录是否过期 无操作1h即为过期
            $login_time = Session::get('userinfo.login_time');
            if ( request()->time() - $login_time > 3600 ) {
                Session::clear();
                //$this->redirect('login/');
                header('location: /admin');
            }
        }
        Session::set('userinfo.login_time',time());
        
    }

    /**
     * 无刷新重载栏目
     * @return json
     */
    function reloadCategory(){
        $cate = getAllCategory('all');
        exit(json_encode($cate));
    }
    /*
     * 空操作
     */
    public function _empty()
    {
        abort(404,'页面不存在 ');
    }
    
    /**
     * 锁定
     */
    public function lock()
    {
        $id = input('param.Id/d', 0);
        $state = input('param.state/d', '');
        if( 0 == $id ){
            return apiJson('', 1, '参数错误');
        }
        
        //获取对象
        $M = $this->model::get($id);

        if( $this->model::ISLOCK_YES===$state && $state==$M->lock ){
            return apiJson('',0,'已是锁定状态'); 
        }elseif( $this->model::ISLOCK_NO===$state && $state==$M->lock ){
            return apiJson('',0,'已是正常状态'); 
        }
        
        //todo 赋值状态,改变
        $M->lock = $state;
        
        if( $this->model::ISLOCK_NO===$state ){
            if ( $M->save()) {
                return apiJson('',0,'解锁成功');
            } else {
                return apiJson('', 1, '解锁失败');
            }
        } if( $this->model::ISLOCK_YES===$state ) {
            if ( $M->save()) {
                return apiJson('',0,'锁定成功');
            } else {
                return apiJson('', 1, '锁定失败');
            }
        }
         
    }
    
    
}
