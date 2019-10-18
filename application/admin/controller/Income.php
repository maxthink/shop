<?php
/**
 * 收入分佣
 */
namespace app\admin\controller;

use think\facade\Request;
use app\common\model\Income as Mincome;

class Income extends Common
{

    /**
     * 列表页
     */
    public function index()
    {
        if( Request::instance()->isPost() )     //post 方式, 获取数据
        {
            $page = input('post.page',1,'intval');
            $limit = input('post.limit',15,'intval');
            
            $uid = input('post.uid',0,'intval');          //用户id
            $imei = input('post.imei','' );               //设备编码
            $did = input('post.did',0,'intval');          //设备id
            $status = input('post.status',0,'intval');    //收入类别
            
            $where[]  = '1=1';
            if( 0!==$uid && preg_match('/\d+/', $uid) ){
                $where[] = 'uid='.$uid ;
            }
            if( ''!==$imei && preg_match('/\d+/', $imei) ){
                $where[] = "imei like '%$imei%' ";
            }
            if( 0!==$did && preg_match('/\d+/', $did) ){
                $where[] = 'b.dev_id='.$did ;
            }
            if( 0!==$status && preg_match('/\d/', $status) ){
                $where[] = 'type&'.$status ;
            }
            
            $list = Mincome::list_admin( $page, $limit, $where );
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面
            $MIncome = new Mincome();
            $this->assign('Model',$MIncome);
            return $this->fetch();
        }
    }
    
    /**
     * 退款
     */
    public function back()
    {
        if ( Request::instance()->isPost() ) {

            $params = input('post.');

            $user = Mbill::get($params['id']);
            $user->back = $params['back'];
            

            if (false !== $user->save()) {
                return apiJson(['user/index'],0,'修改成功');
            } else {
                return apiJson('',1,'修改失败');
            }
        } else {
            return apiJson('',1,'没有参数');
        }
    }
    
    /**
     * 营业额
     */
    public function count()
    {
        if( Request::instance()->isPost() )     //post 方式, 获取数据
        {
            $page = input('post.page',1,'intval');
            $limit = input('post.limit',15,'intval');
            $devid = input('post.devid',0,'intval');
            
            $where = [];
            if( 0!==$devid && preg_match('/\d+/', $devid) ){
                $where[]= 'dev_id='.$devid ;
            }
            
            $M = new Mbill();
            $list = $M->count( $page, $limit, $where );
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面

            return $this->fetch();
        }
    }
    
    /**
     * 提现
     */
    public function exchange()
    {
        if( Request::instance()->isPost() )     //post 方式, 获取数据
        {
            $page = input('post.page',1,'intval');
            $limit = input('post.limit',15,'intval');
            
            $where = [];
            if( 0!==$devid && preg_match('/\d+/', $devid) ){
                $where[]= 'dev_id='.$devid ;
            }
            
            $M = new Mbill();
            $list = $M->count( $page, $limit, $where );
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面

            return $this->fetch();
        }
    }


}
