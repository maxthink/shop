<?php

/**
 * 优惠券
 */

namespace app\wx\controller;

use app\common\model\Device as Mdev;

use app\common\model\User;

use app\common\model\Devicerecord;
use app\common\model\Coupon as Mcoupon;
use app\common\model\Couponrecord;
use think\facade\Session;


class Coupon extends Common {

    /**
     * 领优惠券页面
     * @return [type] [description]
     */
    public function quan() {
        $this->oauthLogin(); //微信登录
        header('location: http://'.$_SERVER['HTTP_HOST'].'/wxs/ticket');
        //return $this->fetch();
    }

    /**
     * 领优惠券页面
     * @return [type] [description]
     */
    public function quan1() {
        $this->oauthLogin(); //微信登录
        header('location: http://'.$_SERVER['HTTP_HOST'].'/wxt/ticket');
        //return $this->fetch('index/index');
    }

    public function list()
    {
        header('Content-type: application/json');

        $uid = Session::get('wechat_user');
        //$uid = 2;
        if(!$uid){
            echo json_encode( mcjson('请登录', 2 ) ); exit;
        }

        $list = Mcoupon::getlist($uid);

        foreach($list as $key=>$val){

            if( Null == $val['uid'] ){
                $list[$key]['status']=0;
            }else{
                $list[$key]['status']=1;
            }
            unset($list[$key]['uid']);
        }

        echo json_encode( mcjson('', 0, $list) );
        
    }


    /**
     * 免费领两次优惠券
     * @return [type] [description]
     */
    public function free() {
        header('Content-type: application/json');

        $uid = Session::get('wechat_user');
        //$uid = 2;
        if(!$uid){
            echo json_encode( mcjson('请登录', 2 ) );exit;
        }

        $qid = input('post.id',0,'intval');
        if( 0 !== $qid ){
            $istaked = Couponrecord::istake($uid,$qid);
            if($istaked){
                echo json_encode(  mcjson('已领取过该优惠券',1) );
            } else {
                $Mcoupon = Mcoupon::get($qid);
                if($Mcoupon)
                {
                    //等于0的是免费, 大于零的到99, 是折扣
                    if( 0 < $Mcoupon['price'] ){

                    } elseif( 0 >= $Mcoupon->price ) {

                        //添加设备记录
                        Devicerecord::add($uid, $qid, $Mcoupon['sum'], $Mcoupon['long'], 0, 0 );
                        // 添加领券记录, 不可重复领取
                        Couponrecord::add($uid,$qid);
                        
                        echo json_encode( mcjson('领券成功, 请扫设备上的二维码使用吧', 0 ) );
                    }
                }
            }
        }
        
        
    }
    
    
    
    
    

}
