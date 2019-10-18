<?php

/**
 * 微信接口  主 控制器
 */

namespace app\wx\controller;

use think\Controller;
use EasyWeChat\Factory;
use app\wx\model\Log;

class Pay extends Controller {

    public function index() {

        $app = Factory::payment($this->config());

        $app->server->push(function ($message) {
            $data = [
                'FromUserName' => $message['FromUserName'],
                'CreateTime' => $message['CreateTime'],
                'msgtype'=> $message['MsgType'],
                'content' => $message['Content'],
                'MsgId' => $message['MsgId'],
            ];
            Log::save($data);

            switch ($message['MsgType']) {
                case 'event':
                    //return '收到事件消息';
                    
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
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

        $response = $app->server->serve();

        // 将响应输出
        $response->send();
        exit;
    }

    private function config() {
        return [
            'app_id' => 'wxd3798655fad9690e',
            'secret' => 'd329a35d1a3048fe93821daa14ab76e4',
            'token' => 'gogogogo',
            'response_type' => 'array',
            'aes_key' => 'XXna8ikT2LBE7ezztEiXBxuxBxjUdSnDMNBsnBKo4FU',
            /**
             * 日志配置
             *
             * level: 日志级别, 可选为：
             *         debug/info/notice/warning/error/critical/alert/emergency
             * path：日志文件位置(绝对路径!!!)，要求可写权限
             */
            'log' => [
                'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => 'wechat.log',
                        'file' => 'wechat.log',
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => '/data/www/csgx2/runtime/log/easywechat.log',
                        'level' => 'info',
                    ],
                ],
            ],
            /**
            * OAuth 配置
            *
            * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
            * callback：OAuth授权完成后的回调页地址
            */
           'oauth' => [
               'scopes'   => ['snsapi_userinfo'],
               'callback' => '/wxapi/oauth_callback.html',
           ],
        ];
    }

}
