<?php
/**
 * 提现
 */
namespace app\admin\controller;

use think\facade\Request;
use app\common\model\Exchange as M;

class Exchange extends Common
{
    /**
     * 列表页
     */
    public function index()
    {
        if( Request::instance()->isPost() )     //post 方式, 获取数据
        {
            //todo 获取提现记录
            $page = input('post.page',1,'intval');
            $limit = input('post.limit',15,'intval');

            $where = [];
            
            $uid = session('userinfo.id');
            if(1!==$uid){
                $where[]= 'user_id='.$uid ;
            }
            
            $list = M::page( $page, $limit, $where );
            
            return apiJson($list);

        } else {    //一般 get 请求, 展示页面
            
            //todo 获取账户余额
            
            //todo 获取未到账金额
            
            //todo 获取可提取金额
            
            //todo 获取历史总收入
                        
            $m = new M();
            //$money = $m->moneyAll();
            //$this->assign('money', $money);
            return $this->fetch();
        }
    }
    
    
}
