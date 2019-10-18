<?php

/**
 * 设备控制
 */

namespace app\api\controller;

use app\common\model\Device as Mdev;
use app\common\model\Recordapi as Mrecord;
use app\common\model\Recorderr as Mlogerr;

use think\Controller;
use think\facade\Cache;

class Api100 extends Controller {

    public $appid;      //应用id, 区分是哪里启动机器
    public $dr_id;      //设备使用记录id, 记录使用状态用 device_record 表
    public $devid;      //设备id
    public $long;       //启动时长
    public $contrl;     //操作命令
    

    /**
    *  设备启动接口
    *
    *  使用此接口地方:  wx/device/work/record  device_work 方法.  微信启动设备
    *  
    */
    public function work() {
        
        $re_check = $this->check();
        if( 0 != $re_check['code'] ){
            return apiMcJson($re_check['msg'], $re_check['code'] );
        }
        
        $dev = Mdev::get($this->devid);
        if(!$dev){
            $mlogerr = new Mlogerr();
            $mlogerr->add('dev_api', 'Api100: 设备id: '.$this->devid.' 不存在');
            return apiMcJson('没有找到对应的设备', 1);
        }
        $imei = $dev['imei'];

        $re = Cache::get('w_'.$imei);

        if(200==$re) {
            return apiMcJson( '设备正在工作中', 1 );         //设备正在工作中
        }elseif(201==$re){
            return apiMcJson( '启动命令已发送设备', 0 );        //命令已经发送         
        }elseif(404==$re){
            return apiMcJson( '设备不在线', 1 );      //设备不在线       
        } else {
            $js = json_decode($re, true);
            if( isset($js['status']) && 202==$js['status'] ){
                return apiMcJson( '启动命令已发送设备', 0);    //正在启动
            } else {
                $h = Cache::get('h_'.$imei);
                if( 0==$h ) {
                    return apiMcJson( '设备不在线', 1 );  //设备不在线
                } else {
                    $MrecordApi = new Mrecord();
                    $MrecordApi->add( $this->appid, $this->devid, $this->long ); //记录启动记录
                    
                    //command{imei:2259853,type:start,long:0900 } command{imei:2259853,type:stop } 
                    // 建立连接，@see http://php.net/manual/zh/function.stream-socket-client.php
                    $client = stream_socket_client('tcp://172.17.161.47:7273');
                    if(!$client){
                        Mlogerr::add('dev_api', 'Api100: tcp://172.17.161.47:7273 连接不上');
                        return apiMcJson('服务器错误', 1);                        
                    }
                    // 模拟超级用户，以文本协议发送数据，注意Text文本协议末尾有换行符（发送的数据中最好有能识别超级用户的字段），这样在Event.php中的onMessage方法中便能收到这个数据，然后做相应的处理即可
                    $re = fwrite($client, 'command{"dr_id":"'.$this->dr_id.'","imei":"'.$imei.'","type":"start","long":"'.$this->long.'"}'."\n");

                    $msg = fread($client, 1024);
                    return apiMcJson($msg, 0);    //命令发送, 

                }
            }                
        }
        
    }
    
    private function check()
    {
        //请求时间戳, 正负超过300秒, 请求无效
        $timestamp = input('post.timestamp',0,'intval');  #
        if( abs(request()->time() -$timestamp) > 300 ){
            //return ['msg'=>time(), 'code'=>401];
        }
        
        $this->appid = input('post.appid',0);         //appid
        $secret = '';
        if(1== $this->appid){
            $secret = 'csgx@xt@eUmkdL#2H';      //应用秘钥
        }

        $this->dr_id = input('post.drid', 0, 'intval');             //设备使用记录id
 
        $this->devid = input('post.devid','');             //设备id

        $this->command = input('post.command','');             //命令

        $this->long = input('post.long',0,'intval');  //启动多久, 单位秒

        $sign = input('post.sign','');          //签名

        
        if(!in_array($this->command, ['start','stop'])) {
            return ['msg'=>'command err', 'code'=>403];
        }
        
        


        $signArr = [ $this->appid, $this->dr_id, $this->devid, $this->command, $this->long, $timestamp, $secret ];
        $_sign = md5( implode('',$signArr) );
        
        if($_sign === $sign){
            return [ 'code'=>0 ];
        }else{
            //return [ 'code'=>402, 'msg'=>'sign err '.$sign.' right sign: '.$_sign. ' signstring: '.implode('',$signArr) .' postconent:' ];
            return [ 'code'=>402, 'msg'=>'签名错误'];
        }
    }

}
