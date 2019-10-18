<?php

/**
 * 微信接口  主 控制器
 */

namespace app\wx\controller;


use app\wx\model\Log;
use \EasyWeChat\Kernel\Messages\Message;


class Msg extends Common {
    
    public function index()
    { 
        //$this->responseEchostr(); exit; //配置公众号用, 
        //file_put_contents('wxtest.html', file_get_contents('php://input'));
        $this->wxapp->server->push(function ($message) {
            
            //file_put_contents('wxtest.html', print_r($message,1), FILE_APPEND );
            //Log::record($message);
            // Array
            // (
            //     [ToUserName] => gh_0d3009238407
            //     [FromUserName] => o6sbr5v20xvofCAisVoBbJM12WW8
            //     [CreateTime] => 1561714014
            //     [MsgType] => text
            //     [Content] => 你好
            //     [MsgId] => 22358369593331214
            // )
            // 
            
            //$event = \think\facade\App::controller('Event', 'event');
           

            switch ( $message['MsgType'] ) {
                case 'event':
                     
                    switch ( $message['Event'] ){
                        case 'subscribe':
                            return '欢迎关注本公众号, <a href="http://csgxjk.com/wx/coupon/quan">点击领取优惠券</a> 免费体验';
                        default:
                            return '';
                    }
                    

                    break;
                case 'text':
                    //return $event->text($message['Content']);
                    if( preg_match('/.*(优惠券|领取).*/', $message['Content'] ) ){
                        return '<a href="http://csgxjk.com/wx/coupon/quan">点击领取优惠券</a>';
                    }

                    if( preg_match('/.*(账单|帐单|查账|zhangdan|cz|zd).*/', $message['Content'] ) ){

                        return '<a href="http://csgxjk.com/wx/manage/">点击进入个人账单页</a>';
                    }

                    if( preg_match('/.*测试.*/', $message['Content'] ) ){

                        return '<a href="http://test.csgxjk.com/wx/manage/">点击进入测试账单页</a>';
                    }

                    if( preg_match('/\d{15}/', $message['Content'] ) ){
                        return '你是要查看账单么? <a href="http://csgxjk.com/wx/manage/">点击进入个人账单页</a>';
                    }

                    return '不知道什么消息';

                    
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        $response = $this->wxapp->server->serve();

        // 将响应输出
        $response->send();
        exit;
    }

}
