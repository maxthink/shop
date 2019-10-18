<?php

namespace app\wx\controller;

use think\Controller;
use think\facade\Session;
use EasyWeChat\Factory;


class Callback extends Controller {


    /**
     * 只为了做接收auth认证结束后跳转用
     */
    function index() {

        $app = Factory::officialAccount($this->config());
        $oauth = $app->oauth;
        //print_r($oauth->redirect()); 

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user()->toArray();
        print_r($user);
        Session::set('wechat_user',$user);
        var_dump($_SESSION);
        $targetUrl = Session::has('target_url')  ? Session::get('target_url') : '/';
        unset($_SESSION['target_url'] );
exit;
        header('location:'. $targetUrl); // 跳转
    }

    private function config() {
        return [
            'app_id' => 'wxd3798655fad9690e',
            
            'secret' => 'd329a35d1a3048fe93821daa14ab76e4', //微信公众号秘钥
            'token' => 'gogogogo', //微信公众号 token
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
                'scopes' => ['snsapi_userinfo'],
                'callback' => '/wxapi/callback.html',
            ],
            
            'mch_id' => '1530926201', //商户号
            'key'                => 'key-for-signature',   // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path' => 'path/to/your/cert.pem',     // XXX: 绝对路径！！！！
            'key_path' => 'path/to/your/key',           // XXX: 绝对路径！！！！
            'notify_url' => 'http://csgxjk.com/wxapi/common',        // 你也可以在下单时单独设置来想覆盖它
        ];
    }

}
