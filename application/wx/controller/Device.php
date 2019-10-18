<?php

/**
 * 设备控制
 */

namespace app\wx\controller;

//use app\wxapi\model\Log;
use app\common\model\Device as Mdev;
use app\common\model\Bill;
use app\common\model\User;
use app\common\model\Meal;
use app\common\model\Bind;
use app\common\model\Devicerecord;
use think\facade\Session;



class Device extends Common {

    public function work($id ) 
    {
        $this->oauthLogin(); //微信登录
        
        $this->user = User::get(Session::get('wechat_user'));

        if( !$this->user ){
            Session::delete('wechat_user');
            $this->oauthLogin(); //微信登录
        }

        if(0===$id){
            return $this->error('设备编码呢?');
        }

        $dev = Mdev::get($id);

        //绑定设备关系, 管理用
        if(0==$dev->userid)
        {
            //先获取跟用户有关的绑定状态, 没有再获取设备绑定状态, 没有设置状态为默认的 unbind
            $bindinfo = ['current_uid'=>$this->user->id,'imei'=>$dev->imei,'comment'=>'' ];

            $bind = Bind::getLastByWxuidAndDeviceid( $id, Session::get('wechat_user') );
            $bind_dev = Bind::getLastModifyByDeviceid($id);

            if( $bind_dev ) //有绑定数据, 就一定有设备绑定数据(用户绑定数据是设备绑定数据的子集)
            {
                if( $bind ) {
                    if($bind->id==$bind_dev->id)  //用户绑定数据跟设备绑定数据是同一条数据
                    {
                        $bindinfo['uid']=$bind->wx_uid;
                        $bindinfo['status']=$bind->status;
                        $bindinfo['status_last']=$bind->status;
                        $bindinfo['comment']=$bind->comment;
                    } elseif( $bind->id < $bind_dev->id ) { //用户绑定数据一定是小于等于设备绑定数据
                        $bindinfo['uid']=$bind->wx_uid;     //用 用户的数据
                        $bindinfo['status']=$bind->status;  //用 用户的数据
                        $bindinfo['status_last']=$bind_dev->status; //用设备数据
                        $bindinfo['comment']=$bind->comment;
                    }else{

                    }
                } else {
                    $bindinfo['uid']=$bind_dev->wx_uid;
                    $bindinfo['status']=$bind_dev->status;
                    $bindinfo['status_last']=$bind_dev->status;
                }
                
            }
            else  //没有绑定信息, 出一个默认的绑定信息给页面js判断用.
            {
                $bindinfo['uid']=0;
                $bindinfo['status']=Bind::BIND_UNBIND;
                $bindinfo['status_last']=Bind::BIND_UNBIND;
            }

            $this->assign('bindinfo', $bindinfo );
            $this->assign('bind', new Bind() );
            return $this->fetch('device/bind');
            
        }else{
            header('location: http://'.$_SERVER['HTTP_HOST'].'/wxs/index/sellpack/'.$id);
            //return $this->fetch('index/index');
        }        
        
    }

    //用户设备关系绑定
    public function bind()
    {
        $realname = input('post.realname','','addslashes');
        $password = input('post.password','');
        $p_uid = input('post.uid',0,'intval'); //必须intval, 前端 p_uid 是前导补0 的
        if( $p_uid == Session::get('wechat_user') ){
            return mcJson('推荐人不可以是自己',1);
        }

        $devId =  $this->getDevId();

        $mdev = Mdev::get($devId);
        if( strtolower($password) != strtolower($mdev->bindpassword) ) // should get from device 
        {
            return mcJson('授权码错误',1);
        }

        $Mbind = new Bind();
        $bind = $Mbind->getLastModifyByDeviceid($devId);

        if($bind && $bind['status'] & Bind::BIND_APPLY)
        {
            return mcJson('已经有人申请绑定该设备, 请联系客服处理',1);   //此处需完善逻辑
        }
        else
        {
            if( Bind::addBind( Session::get('wechat_user'), $realname, $devId, $p_uid) )
            {
                return mcJson('申请提交成功, 请联系客服通过审核, 该收益才会与你绑定!');
            }else{
                return mcJson('申请提交失败',1);
            }
        }
    }


    private function getDevId(){

        $devId = intval( substr($_SERVER["HTTP_REFERER"], strrpos($_SERVER["HTTP_REFERER"],'/')+1 ) );
        //$devId = input('get.did',0,'intval');

        if( !is_numeric($devId) || 0===$devId){
            echo json_encode(  mcJson('参数错误', 1 ) );exit;
        }
        return $devId;
    }

    /**
     * 获取设备编码
     * @return [type] [description]
     */
    public function no()
    {
        $devId =  $this->getDevId();

        $dev = Mdev::get($devId);
        if($dev){
            echo json_encode(  mcJson('', 0, $dev['imei'] ) );exit;
        } else {
            echo json_encode(  mcJson('数据错误', 1 ) );
        }

    }

    //查询设备数据和状态(第一次查询设备imei信息 以后不查询, 只返回设备状态)
    public function status()
    {

        $devId =  $this->getDevId();

        $dev = Mdev::get($devId);

        //todo 判断设备在线, 工作状态
        $online = $this->device_online($dev['imei']);

        $msg = '设备未在线';
        if($online){
            $msg = '设备在线未使用';
            $workstatus = $this->device_work_status($dev['imei']);

            if($workstatus){
                $msg = '设备工作中';
            }
        }

        //$data['status'] = $msg;

        echo json_encode( mcJson('', 0, $msg ) );

    }



    
    /**
     * 获取设备套餐数据
     */
    public function meal()
    {
        $devId =  $this->getDevId();

        $dev = Mdev::get($devId);
        
        $setmeal = Meal::all($dev['setmealId']);

        echo json_encode( mcJson('', 0, $setmeal) ); exit;
    }
    
    //下单, 顺序: 1,判断设备是否在线, 2, 判断设备是否运行中, 3, 下单, 4, 启动设备命令下发
    public function bill(){
        
        $devId =  $this->getDevId();

        $mealid = input('post.id',0,'intval');
        
        $dev = Mdev::get($devId);
        if(1==$dev['lock']){
            echo json_encode( mcJson('', 1, '设备已锁定') ); exit;
        }
        
        //todo 判断设备在线, 工作状态
        $online = $this->device_online($dev['imei']);
        $workstatus = 0;
        if($online){
            $workstatus = $this->device_work_status($dev['imei']);
            if($workstatus){
                echo json_encode( mcJson('设备正在工作中', 1 ) ); exit;
            }
        }else{
            echo json_encode( mcJson('设备不在线', 1 ) ); exit;
        }
        
        //todo 从套餐id获取价钱
        $price = 0;
        $meal = Meal::get($mealid);
        if($meal){
            $price = $meal['price'];
        } else {
            echo json_encode( mcJson('套餐错误: 没有找到该套餐!', 1 ) ); exit;
        }
        
        
        //todo 保存订单
        $Bill = new Bill();
        $Bill->code = date('YmdHis').random_int(1000,9999);
        $Bill->user_id = Session::get('wechat_user');
        // $Bill->user_id = 2;
        $Bill->price = $price;
        $Bill->meal_id = $mealid;
        $Bill->dev_id = $devId;
        $Bill->owner_uid = $dev['userid'];
        $Bill->createtime = time();
        
        if( $Bill->save() ) {    
            $user = new User();
            $openid = $user->getOpenidByUid( Session::get('wechat_user') );
            
            if($openid){
 
                $result = $this->wxapp_pay->order->unify([
                    'body' => '创世共想 - 理疗订单',
                    'out_trade_no' => $Bill->code,
                    'total_fee' => (int)$Bill->price,
                    'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].\think\facade\Url::build('device/wxPayNotice',''), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                    'trade_type' => 'JSAPI',
                    'openid' => $user->getOpenidByUid( Session::get('wechat_user') ),
                ]);
                //print_r($result);

                //预下单成功,
                if( $result['return_code']=='SUCCESS' && $result['return_msg'] =='OK' ){
                    $json = $this->wxapp_pay->jssdk->bridgeConfig($result['prepay_id'],false);  //必须加false, 返回array类型
                    echo json_encode( mcJson('', 0, $json) ); exit;
                } elseif($result['return_code']=='FAIL') {
                    //Log::record();
                    echo json_encode( mcJson('微信下单出错: '.$result['return_msg'], 1 ) ); exit;
                }
            } else {
                echo json_encode( mcJson('用户不存在', 1 ) ); exit;
            }
        } else {    
            echo json_encode( mcJson('下单出错', 1 ) ); exit;
        }
        
    }
    
    //获取设备使用状况列表
    public function record($id)
    {
        
        return $this->fetch();
    
    }

    public function list1()
    {
        if(request()->isget()) {
            $page = input('get.page',1,'intval');
            $uid = Session::get('wechat_user');
            // $uid = 2;
            if($uid){
                $data = Devicerecord::getRecordByUid($uid, $page);
                //print_r($data);exit;
                
                echo json_encode( [ 'msg'=>'' , 'code'=>0,  'data'=>$data['data'], 'limit'=>$data['limit'] ] );
            } else {
                echo json_encode( mcJson('未登录', 2, '') );
            }
        } else {
            return;
        }
    }

    
    //设备启动
    public function run()
    {
        //获取用户id
        $uid = Session::get('wechat_user');
        // $uid = 2;
        if(!$uid){
            echo json_encode(  mcJson( '请登录', 2 ) );exit;
        }

        //获取设备id
        $url = $_SERVER["HTTP_REFERER"];
        $devId = intval( substr($url, strrpos($url,'/')+1 ) );
        if(''==$devId){
            echo json_encode( mcJson('参数错误', 1) ); exit;
        }

        //获取 记录id
        $dr_id = input('post.id',0,'intval');   //dr_id  device_reocrd_id 缩写
        if(0===$dr_id){
            echo json_encode(  mcJson( '设备启动记录出错', 1 ) );exit;
        }
        
        $m = Devicerecord::get($dr_id);
        if(1==$m['status']){
            echo json_encode(  mcJson( '已使用', 1 ) );exit;
        }
        
        if($m->user_id != $uid){
            echo json_encode(  mcJson( '该订单非你所有', 1 ) );exit;
        }

        //当前设备id
        //$curdev_id = input('post.did',0,'intval');

        if ( 0 !== $m->dev_id && $m->dev_id != $devId ) {

            $M_dev = Mdev::get($m->dev_id);
            echo json_encode(  mcJson('当前设备与下订单设备不是同一台设备, 请在设备编码为 '.$M_dev->imei.' 的设备上使用', 1 ) );exit;

        } elseif ( 0 == $m->dev_id ) { //优惠券, 启动当前机器

            $m->dev_id = $devId;
        }
        
        //todo 启动设备
        $re = $this->device_work($dr_id, $m->dev_id, $m->long );
        echo json_encode(  mcJson($re['msg'], $re['code'] ) );
        
    }

    /**
     * 查看订单状态, 方便客户端显示
     * @return [type] [description]
     */
    public function drstatus()
    {
        $dr_id = input('post.id',0,'intval');
        if(0===$dr_id){
           echo json_encode( mcJson('参数错误', 1));exit;
        }
        $m = Devicerecord::get($dr_id);
        if(1==$m['status']){
            echo json_encode( mcJson('', 0));exit;
        }else{
            echo json_encode( mcJson('', 1));exit;
        }
    }
    
    /**
     * 下发设备工作命令
     * @param type $dr_id 数据库设备使用记录id
     * @param type $devId
     * @param type $long
     * @return type
     */
    private function device_work($dr_id, $devId, $long)
    {
        //请求启动去
        $appid = 1;
        $secret = 'csgx@xt@eUmkdL#2H';
        $timestamp = time();
        $command = 'start';
        $sign = md5($appid.$dr_id.$devId.$command.$long.$timestamp.$secret);
        
        $data = 'appid='.$appid.'&secret='.$secret.'&timestamp='.$timestamp.'&drid='.$dr_id.'&devid='.$devId.'&command='.$command.'&long='.$long.'&sign='.$sign;

        $res = $this->post('http://csgxjk.com/api/device/100/work', $data);

        return json_decode($res, true);
    }
    
    //获取设备在线状态
    private function device_online($imei)
    {
        $heartkey = 'h_'.$imei;
        return cache($heartkey);
    }
    
    //获取设备工作状态
    private function device_work_status($imei)
    {
        $heartkey = 'w_'.$imei;
        return cache($heartkey);
    }
    
    /**
     * 微信支付结果异步通知, 启动机器, 记录日志, 添加缓存
     */
    public function wxPayNotice()
    {
        $content = file_get_contents('php://input');
        file_put_contents('tttdd.html', $content, FILE_APPEND);
        $response = $this->wxapp_pay->handlePaidNotify(function ($message, $fail) {
            
            //file_put_contents('tttdd.html', print_r($message,1), FILE_APPEND );
            
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $bill = Bill::getBillByPaycode($message['out_trade_no']);
            //file_put_contents('tttdd.html', $bill->getlastsql(), FILE_APPEND );
            //file_put_contents('tttdd.html', print_r($bill,1), FILE_APPEND );

            if ( !$bill || $bill->paytime ) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// <- 建在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                
                if ( $message['result_code'] === 'SUCCESS' ) { // 用户是否支付成功
                    $bill->paytime = time(); // 更新支付时间为当前时间
                    $bill->status = '1';   //订单状态: 0:下订单,未支付, 1: 已支付, 2: 支付失败, 3:申请退款, 4:已退款,
                    
                // 用户支付失败
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    $bill->status = '2';
                }
            } else {
                return $fail('通信失败，请稍后再通知');
            }
            
            
            //$dev_re = $this->device_work($bill['dev_id'], $bill['long']); //下发启动命令
            $bill->save(); // 保存订单
            
            //todo 添加设备未使用记录
            $meal  = Meal::get($bill->meal_id);   //获取套餐内容
            if($meal){
                Devicerecord::add($bill->user_id, $bill->id, $meal['sum'], $meal['long'], $bill['dev_id']);
            }else{
                //todo 
            }

            //todo 默认执行第一条记录: 获取用户最新一条记录, 执行之
            $drObj = Devicerecord::getLastOne($bill->user_id);
            $this->device_work($drObj->id, $drObj->dev_id, $drObj->long);
            
            
            return true; // 返回处理完成
        });

        $response->send(); // Laravel 里请使用：return $response;
    }
    
    /*
     * 用code通过微信接口获取openid
     * @param string code  code码 
     * return mix(array)
     */
    private function getOpenidByCode($code)
    {
        $appid = C('APP_ID');//'wxd6b167b3a6ed3c15';
        $secret = C('APP_SECRET');//'304af29631b18a32eb30b7a8121006ad'; // 密匙appsecret
        $apiurl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';

        $ch = curl_init($apiurl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($ch);
        $httpStatus = curl_getinfo($ch);
        curl_close($ch);
        if($httpStatus['http_code']==200)
        {
            $_d = json_decode($result,true);
            return $_d['openid'];
        }else
        {
            return false;
        }
    }
    
    
    
    /**
    * 发起POST请求
    *
    * @access public
    * @param string $url
    * @param array $data
    * @return string
    */
   private function post($url, $data = '', $cookie = '', $type = 0)
   {
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查  
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_HEADER, 0);
       if($cookie){
           curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
           curl_setopt ($ch, CURLOPT_REFERER,'https://wx.qq.com');
       }
       if($type){
           $header = array(
           'Content-Type: application/json',
           );
       curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
       }

       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_USERAGENT,isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'' );
       curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
       $output = curl_exec($ch);
       curl_close($ch);
       return $output;
   }

}
