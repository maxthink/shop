<?php
/**
 * 商店
 */
namespace app\admin\controller;

use think\facade\Request;
use app\common\model\Bill as Mbill;

class Personal extends Common
{
    
    /**
     * 列表页
     */
    public function index()
    {
        if( Request::instance()->isPost() )     //post 方式, 获取数据
        {

            $uid = input('post.uid',0,'intval');
            
            $where = [];
            if( 0!==$uid && preg_match('/\d+/', $uid) ){
                $where[]= 'user_id='.$uid ;
            }
            
            $list = Mbill::page( $page, $limit, $where );
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面

            return $this->fetch();
        }
    }
    

}
