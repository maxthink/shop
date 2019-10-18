<?php

namespace app\wx\controller;

use think\Controller;
use EasyWeChat\Factory;
use think\facade\Session;
use think\facade\Request;
use app\common\model\User;

class Common extends Controller {

    protected $user;
    protected $wxapp;
    protected $wxapp_pay;

    public function __construct()
    {
        //echo '<br>common __construct 初始化: '.memory_get_usage();
        
        $this->wxapp = Factory::officialAccount( $this->config() );
        //echo '<br>common wxapp 初始化完: '.memory_get_usage();
        
        $this->wxapp_pay = Factory::payment( $this->payConfig() );
        //echo '<br>common wxapp_pay 初始化完: '.memory_get_usage();
        // $this->responseEchostr();
        //$this->oauthLogin();    //必须微信登录
        //Session::delete('wechat_user');
        
        parent::__construct();
        //echo '<br>common parent__construct 初始化完: '.memory_get_usage();
    }
    
    public function oauthLogin(){

        // 未登录
        if ( !Session::has('wechat_user') ) {
            Session::set('target_url', curPageURL() ) ;

            if ( !$this->wxapp->oauth->getRequest()->get('code')) {
                $this->wxapp->oauth->redirect( Request::url(true) )->send();
                //$this->wxapp->oauth->redirect()->send();
            } else {
                
                //todo 判断用户是否存在数据库
                $wxuser = $this->wxapp->oauth->user()->getOriginal();
                $User = new User();
                $id = $User->getUserIdByOpenid( $wxuser['openid'] );
                
                if(!$id){
                    $uid = $User->addUserByWx( $wxuser );   //用原始数据, 兼容性好
                    Session::set('wechat_user', $uid );
                } else {
                    Session::set('wechat_user', $id );
                }
                
            }
        }
    }

     /**
     * 微信接入地址,验证echostr
     * @return [type] [description]
     */
    public function responseEchostr()
    {
        $this->wxapp->server->serve()->send();
        die;
    }
    
    public function json( $data='', $status=0, $msg='' ){
    
        return ['status'=>$status, 'msg'=>$msg, 'data'=>$data];
    }

    private function config() {
        return [
            'app_id' => 'wx2e83e6ecf1127685',
            'secret' => 'c508d4e7bc5586141d315ee9c5038ef5', //微信公众号秘钥
            'token' => 'gogogogo', //微信公众号 token
            'response_type' => 'array',
            'aes_key' => 'y7d4oizYlIn9eZH8aQ0yuWD4ZAX79P2sDeuMpBz9H1s',
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
                'callback' => '/wx/callback.html',
            ],
            
        ];
    }

    private function payConfig(){
        return [
            'app_id' => 'wx2e83e6ecf1127685',
            'mch_id' => '1530967061', //商户号
            //'key'    => 'y7d4oizYlIn9eZH8aQ0yuWD4ZAX79P2sDeuMpBz9H1s',   // API 密钥
            'key'    => 'y7d4oizYlIn9eZH8aQ0yuWD4ZAX79P2s',   // API 密钥
            
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'  => 'path/to/your/cert.pem',     // XXX: 绝对路径！！！！
            'key_path'   => 'path/to/your/key',           // XXX: 绝对路径！！！！
            
            'notify_url' => 'http://csgxjk.com/wx/pay/notice',        // 你也可以在下单时单独设置来想覆盖它
        ];
    }
}
