<?php
/**
 * 账单
 */
namespace app\admin\controller;

use think\facade\Request;
use app\common\model\Bill as Mbill;

class Bill extends Common
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
            
            $bid = input('post.bid',0,'intval');
            $uid = input('post.uid',0,'intval');                //用户id
            $imei = input('post.imei','' );                     //设备编码
            $did = input('post.did',0,'intval');                //设备id
            $status = input('post.status','');    //支付状态
            
            $where[]  = '1=1';
            if( 0!==$bid && preg_match('/\d+/', $bid) ){
                $where[] = 'b.id='.$bid ;
            }
            if( 0!==$uid && preg_match('/\d+/', $uid) ){
                $where[] = 'b.owner_uid='.$uid ;
            }
            if( ''!==$imei && preg_match('/\d+/', $imei) ){
                $where[] = "imei like '%$imei%' ";
            }
            if( 0!==$did && preg_match('/\d+/', $did) ){
                $where[] = 'd.id='.$did ;
            }
            if( preg_match('/\d/', $status) ){
                $where[] = 'b.status='.$status ;
            }
            
            $list = Mbill::list_admin( $page, $limit, $where );
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面
            $Mbill = new Mbill();
            $this->assign('Mbill',$Mbill);
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
            $where[]= 'status&1';
            
            $M = new Mbill();
            $list = $M->count( $page, $limit, $where );
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面

            return $this->fetch();
        }
    }
    

}
