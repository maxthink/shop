<?php

/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
use Workerman\Lib\Timer;
//require_once __DIR__ . '/vendor/autoload.php';

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events {

    /**
     * 保存数据库实例
     */
    public static $db = null;

    /**
     * 保存redis实例
     */
    public static $redis = null;
    
    public static $logname = null;

    /**
     * 进程启动后初始化数据库连接, redis连接
     * 此属于 BusinessWorker进程启动事件
     */
    public static function onWorkerStart($worker) {

        ini_set('default_socket_timeout', -1); //redis不超时

        self::$db = new \Workerman\MySQL\Connection('127.0.0.1', '3306', 'www', 'guoqu123!@#', 'liangzi');

        self::$redis = new \Redis();
        self::$redis->connect('127.0.0.1', 6379);
        self::$redis->auth('csgx@)!(csgx@)!(');
        self::$redis->select(1);

        self::$logname = 'log/tcp'.date('Ymd').'.html';
    }

    /**
     *  BusinessWorker 进程事件, 进程关闭
     */
    public static function onWorkerStop($worker) {
        self::$redis->close();
        self::$db->closeConnection();
    }

    /**
     * 当客户端连接时触发, 客户端事件
     * 如果业务不需此回调可以删除onConnect
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id) {

        // 向当前client_id发送数据
        //Gateway::sendToClient($client_id, "Hello,you client id is: $client_id\r\n");
    }

    /**
     * 当客户端发来消息时触发, 客户端事件
     * @param int $client_id 连接id
     * @param mixed $data 具体消息
     * wkey : redis 键. 设备工作状态, 判断设备是否工作状态用.
     * hkey : redis 键. 设备心跳, 在线状态, 给web显示用
     */
    public static function onMessage($client_id, $data) {

        self::$logname = 'log/tcp'.date('Ymd').'.html';

        self::log(date('H:i:s').' message: ' . $data);
    //------跟web项目交互------    
        if (0 === strpos($data, 'command')) {    // check is not server command ?
            self::log('data is command ');
            //todo 检查数据
            $command = self::commandCheck($data, $client_id);
            //self::log('command contents: '.var_dump($command));
            if (false === $command['ok']) {
                Gateway::sendToClient($client_id, $command['msg']);
                return;
            }
            $_imei = $command['imei'];
            //self::log(' imei : '.$_imei );
            

            //todo 检查要执行命令的客户端是否在线
            $wkey = self::RWkey($_imei);
            self::log(' wkey : '.$wkey );
            $_client_id = Gateway::getClientIdByUid($_imei); //一个uid可以绑定多个客户端id, 所以返回的是数组. 我们用的是一对一绑定
            self::log('command cliend_id: ', var_dump($_client_id));
            if ( !isset($_client_id[0]) || !Gateway::isOnline($_client_id[0])) {
                self::$redis->setex($wkey, 10, '404');    //client is offline
                Gateway::sendToClient($client_id, 'client offline');
                return;
            }

            //todo 执行命令
            $has = self::$redis->get($wkey);
            switch ($command['type']) {

                case 'start':   //启动设备
                    //执行命令前检查命令是否重复 check device is not working. from redis, if redis key is exists, and value is 200, it means device is working
                    if ('200' == $has) {
                        Gateway::sendToClient($client_id, '设备运行中');   //device is working
                        return;
                    } else {
                        $_t = json_decode($has, true);
                        if (isset($_t['status']) || $_t['status'] == '202') {
                            Gateway::sendToClient($client_id, '启动命令已发送');   ////设备启动命令已发送
                            return;
                        }
                    }

                    $_temp = json_encode(['status' => 202, 'long' => $command['long'], 'dr_id' => $command['dr_id'] ]);
                    self::$redis->setex($wkey, 65, $_temp);    //exec ing , wait client return
                    Gateway::sendToClient($_client_id[0], self::result( '000', $_imei , 'QD' , $command['long'] ) ); //send start command to client
                    Gateway::sendToClient($client_id, '启动命令已发送');   ////device is starting
                    
                    //  延迟检查启动命令是否执行
                    Timer::add( 10, function( $_client_id, $imei, $count )
                    {
                        $wkey = self::RWkey($imei);
                        $has = self::$redis->get( $wkey );
                        $_t = json_decode($has, true);
                        if (isset($_t['status']) && $_t['status'] == '202') {
                             Gateway::sendToClient($_client_id, self::result( '000', $imei , 'QD' , $_t['long'] ) ); 
                        }
                    }, array($_client_id[0], $_imei, 3) , false );
                    self::log('timer after ');
                    
                    break;
                case 'stop':    //关闭设备运行
                    //check device working status . from redis, if redis key is exists, and value is 500, it means device is stoped
                    if ('501' == $has) {  //the device is stoping
                        Gateway::sendToClient($client_id, '501');
                    } else if ('500' == $has) {
                        Gateway::sendToClient($client_id, '500');   //the device is stoped
                    }

                    self::$redis->setex($wkey, 65, '501');    //stoping , wait client return
                    Gateway::sendToClient($_client_id[0], self::result( '000', $_imei , 'ST' ) ); //send command to client
                    
                    Gateway::sendToClient($client_id, 'stoping');   ////device is starting
                    
                    //机器停止后, 重启后会继续记忆时间, 所以给运行1秒钟来停止
                    Timer::add( 1, function( $_client_id, $imei )
                    {
                        Gateway::sendToClient($_client_id, self::result( '000', $imei , 'QD', 1 ) );
                    }, array($_client_id[0], $_imei) , false );


                    break;
                default : Gateway::sendToClient($client_id, 'command err');   ////device is starting
            }
            return;
        } //end server command

    //------跟设备交互---------
        //todo 检查数据完整性, 小于3字节的为心跳数据
        if( strlen($data)>3 ){
            $data_len = intval(substr($data, 0,3));
            if( strlen($data) >= $data_len ){           //多个命令到达, 只处理第一个
                $data = substr ($data, 3, $data_len-3 );
                if ( preg_match('/\d{18}[A-Z]{2}\d?+/', $data) ) {
                    $status = substr($data, 0, 3);
                    $imei = substr($data, 3, 15);
                    $command_type = substr($data, 18, 2);  
                    $param = substr($data, 20, $data_len-3);                  
                } else {
                     Gateway::sendToClient($client_id, self::result('403'));    //命令数据格式不对
                     return;
                }
            }elseif(strlen($data) < $data_len){
                Gateway::sendToClient($client_id, self::result('402')); //命令数据长度不对
                return;
            }else{
                $data = substr($data, 3);
                if ( preg_match('/\d{18}[A-Z]{2}/', $data) ) {
                    $status = substr($data, 0, 3);
                    $imei = substr($data, 3, 15);
                    $command_type = substr($data, 18, 2);                    
                } else {
                     Gateway::sendToClient($client_id, self::result('403'));    //命令数据格式不对
                     return;
                }
            }
        } else {
            if (!isset($_SESSION[$client_id])){
                Gateway::sendToClient($client_id, self::result('404')); //不在线
                return;
            }else{
                self::flushRedisHeart( $_SESSION[$client_id] );   //刷新服务, 不返回任何数据
                Gateway::sendToClient($client_id, 'OK' ); //ceshi
                return;
            }            
        }
        
        self::log('data check ok, imei:'.$imei. ' type:'.$command_type . ' status:'.$status);
        
        if (!isset($_SESSION[$client_id])) { //初次连接, 或者是掉线重连
                
            self::log('heart imei: ' . $imei);

            $devid = self::getDevidByImei($imei);

            if ($devid) {
                $_SESSION[$client_id] = $imei;  // ***** imporment
                //todo 一对一关系, 去掉之前连的客户端
                $clients_last = Gateway::getClientIdByUid($imei);
                if($clients_last){
                    foreach ($clients_last as $last_client){
                        Gateway::closeClient($last_client);
                    }
                }
                Gateway::bindUid($client_id, $imei);    //下面会根据imei执行command
                
                self::log('bind: imei: '.$imei.' client_id:'.$client_id);
            } else {
                Gateway::sendToClient($client_id, self::result('300',$imei) );   //tell client server not found devid by this imei
                return;
            }

            if ('HT' == $command_type) {  //clent send heart
                Gateway::sendToClient($client_id, self::result('200',$imei,'HT'));   //tell client server is recivied
                return;
            } else {
                //非心跳数据, 但又是数据, 下面处理
            }
                
        }
        
        self::log('begin process Command ');
        
        $_imei = $_SESSION[$client_id];
        if ($_imei != $imei) {
            Gateway::sendToClient($client_id, self::result('401') );   //tell client command error ,imei no eq imei in server
            return;
        }
        $devid = self::getDevidByImei($imei);
        //todo 刷新心跳 ***
        self::flushRedisHeart($imei);
   

        //todo 处理命令数据

        if ('HT' == $command_type) { //request tel number , from client
            self::log(' client heart 2 '.$client_id);
            Gateway::sendToClient($client_id, self::result('200',$imei,'HT'));
        } elseif ('TN' == $command_type) {

            Gateway::sendToClient($client_id, self::result( '200',$imei,'TN',self::getTelnumber() ) );
            
        } elseif ('QR' == $command_type) { //request qrcode , from client
            
            Gateway::sendToClient($client_id, self::result( '200',$imei,'QR', self::getQrcode($devid) ) );
            
        } elseif ('QD' == $command_type) {  // the result of client exec , from client
            
            self::log('result of client exec ');
            $wkey = self::RWkey($imei);
            
            if (200 == $status) {   //设备启动成功
                $res = self::processClientQD($wkey, $devid);
                Gateway::sendToClient($client_id, self::result( $res,$imei,'QD' ) );
            } elseif (201 == $status) {
                self::$redis->set($wkey, '500');  //working now
                
                Gateway::sendToClient($client_id, self::result('200',$imei,'QD') );
            } elseif (202 == $status) {
                self::$redis->set($wkey, '505');  //命令imei跟设备imei不一致
                Gateway::sendToClient($client_id, self::result('200',$imei,'QD') );
            } elseif (203 == $status) { //设备正在运行, 还有多久停止
                 
                if(isset($param) && $param>0){  //存在且大于0秒, 就存redis, 可以刷新某些不正常状态
                    self::$redis->setex($wkey, $param ,'200');  //正在运行中
                }
                Gateway::sendToClient($client_id, self::result('200',$imei,'QD') );
            } else {
                self::$redis->setex($wkey, 50, 'no know error');
                Gateway::sendToClient($client_id, self::result('200',$imei,'QD') );
            }
        } elseif ('ST' == $command_type) {  // the result of client exec , from client
            
            self::log('result of client ST ');

            $wkey = self::RWkey($imei);
            
            if (200 == $status) {
                $res = self::processClientSt($wkey, $devid);
                Gateway::sendToClient($client_id, self::result($res,$imei,'ST') );
            } elseif (201 == $status) {
                self::$redis->set($wkey, '500');  //working now
                Gateway::sendToClient($client_id, self::result('200',$imei,'ST') );
            } elseif (202 == $status) {
                self::$redis->set($wkey, '505');  //命令imei跟设备imei不一致
                Gateway::sendToClient($client_id, self::result('200',$imei,'ST') );
            } else {
                self::$redis->setex($wkey, 50, 'no know error');
                Gateway::sendToClient($client_id, self::result('200',$imei,'ST') );
            }
            
        } else {
            // nothing to do
            self::log('do not know what data is will do, may be heart ');
        }
    }

// end function

    /**
     * 当用户断开连接时触发, 客户端事件
     * @param int $client_id 连接id
     */
    public static function onClose($client_id) {

        $imei = $_SESSION[$client_id];
        self::log('on close dev id: ' . $imei);
        self::$redis->del(self::RHkey($imei));    //删除心跳
        //self::$redis->del( self::RWkey($imei) );    //注意, 不要删除工作状态
    }

    private static function log($text = '') {
        echo $text . PHP_EOL;
        file_put_contents( self::$logname, date('H:i:s') . '  ' . $text . PHP_EOL, FILE_APPEND);
    }

    private static function result($status, $imei='', $type='', $data='')
    {
        $result = $status;
        if(''!==$imei && 15== strlen($imei)){
            $result .= $imei;
        }
        if(''!==$type && 2== strlen($type)){
            $result .= $type;
        }
        if( strlen($data)>0 ){
            $result .=$data;
        }
        //return $data;
        return str_pad(strlen($result)+3,3,'0',STR_PAD_LEFT).$result;
    }
    /**
     * 返回redis 心跳键
     * @param type $imei
     */
    private static function RHkey($imei) {
        if ('' != $imei) {
            return 'h_' . $imei;
        } else {
            //todo record err
        }
    }

    private static function RWkey($imei) {
        if ('' != $imei) {
            return 'w_' . $imei;
        } else {
            //todo record err
        }
    }
    
    /**
     * 刷新服务器redis心跳
     * @param type $imei
     */
    private static function flushRedisHeart($imei)
    {
        if (0 === self::$redis->exists(self::RHkey($imei))) {
            $devid = self::$db->single('select id from bg_device where imei=' . $imei); //冗余, 防止实际没掉线, redis却过时了
            self::$redis->setnx(self::RHkey($imei), $devid);
        }
        $devid = self::$redis->get( self::RHkey($imei) );
        self::$db->query('update bg_device set lasttime='.time().' where id='.$devid );
        self::$redis->expire(self::RHkey($imei), 100 );    //心跳时间更新, 此心跳给web端展示用.....
    }

    /**
     * 根据imei号获取devid,
     * @param type $imei
     */
    private static function getDevidByImei($imei) {

        if (0 === self::$redis->exists(self::RHkey($imei))) {
            $devid = self::$db->single('select id from bg_device where imei=' . $imei); //冗余, 防止实际没掉线, redis却过时了
            if($devid){
                self::$redis->setnx(self::RHkey($imei), $devid); //不知道这之间有没有变动, 用 setnx 保险
                self::$redis->expire(self::RHkey($imei), 100 );
                return $devid;
            }else{
                return false;   //没找到
            }
        } else {
            return self::$redis->get(self::RHkey($imei));
        }
    }
                

    /**
     * 检查数据是否合规   data: command{imei:2259853,type:start,long:0900 } command{imei:2259853,type:stop }    command{appid:1;dr_id:232;imei:123456787654321;type:start;long:900}
     * @param type $data
     * @return array
     */
    private static function commandCheck($data) {
        self::log('command: '.$data);
        $command = json_decode(substr($data, 7), true);   //data: command{imei:2259853,type:start,long:0900 } command{imei:2259853,type:stop }

        if (true !== $command && false !== $command && null !== $command) {

            //todo auth
            //todo 检查命令是否合法

            if (isset($command['imei']) && isset($command['type'])) {
                $command['ok'] = true;
                return $command;
            } else {
                //Gateway::sendToClient($client_id, 'err: imei no found');
                return ['ok' => false, 'msg' => 'imei no found'];
            }
        } else {
            //Gateway::sendToClient($client_id, 'command err');
            return ['ok' => false, 'msg' => 'command err'];
        }
    }

    /**
     * 获取联系电话
     * @return string
     */
    private static function getTelnumber() {
        self::log(' client request Telnumber ');
        //todo get telnumber, and send to client,
        return '400-106-2599';
    }

    /**
     * 获取二维码
     * @param type $devid
     * @return type
     */
    private static function getQrcode($devid) {
        self::log(' client request qrcode ');
        //todo get qrcode, and send to client
        return 'http://csgxjk.com/wx/device/work/' . $devid;
    }

    /**
     * 返回客户端命令执行结果, imei码(15字节)命令(2字节)执行结果(1字节)
     * @param type $data
     * @return type
     */
    private static function getClientTypeStatus($data) {
        return substr($data, 17, 1);
    }

    /**
     * 处理 客户端命令执行情况 EX
     * @param type $wkey
     * @param type $devid
     */
    private static function processClientQD($wkey, $devid) {
        self::log( 'processClientQD wkey:'.$wkey.' devid:'.$devid );

        $has = self::$redis->get($wkey);
        $_t = json_decode($has, true);
        if (isset($_t['status']) && $_t['status'] == '202') {
            self::$redis->set($wkey, '200');  //client exec ok
            self::$redis->expire($wkey, $_t['long']);    //工作时长, 在线状态使用
            //self::$db->insert('record_work')->cols(['appid' => $_t['appid'], 'devid' => $devid, 'start_time' => time(), 'long' => $_t['long']])->query();
            //self::$db->update('h_device_record')->cols( ['usetime' => time(), 'dev_id' => $devid, 'status'=>1 ] )->where( 'id='.$_t['dr_id'] )->query();
            $row_count = self::$db->query('update h_device_record set usetime='.time().', dev_id='.$devid.', status=1 where id='.$_t['dr_id']);
            self::log('更新了'.$row_count.'line ');
            
            return '200';
        } elseif ($has == '200') {
            return '200';
        } else {
            return '200';
        }
    }

    /**
     * 处理客户端停止命令执行情况
     * @param type $wkey
     * @param type $devid
     */
    private static function processClientSt($wkey, $devid) {
        self::$redis->set($wkey, '500');  //client exec ok
        self::$redis->expire($wkey, 3);    //working, stop working after 900 secends
        //todo  记录手工停止, 停止都是手工的
        //self::$db->insert('record_work')->cols(['appid' => $_t['appid'], 'devid' => $devid, 'start_time' => time(), 'long' => $_t['long']])->query();
        return '200';
    }

}
