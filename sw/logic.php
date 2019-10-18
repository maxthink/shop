<?php

// 处理类
class Logic
{
    
    public function __construct() {
        
    }
    //有客户端连接上
    public static function onConnect( $serv, $fd, $from_id ) {
        $serv->send( $fd, "Hello {$fd}!" );
    }

    //收到客户端数据
    public static function onReceive( swoole_server $serv, $client, $work_id, $data ) {
        
        //todo 检查数据完整性, 小于3字节的为心跳数据
        if( strlen($data)>3 ){
            $data_len = intval(substr($data, 0,3));
            if( strlen($data) >= $data_len ){
                $data = substr ($data, 3, $data_len-3 );
                if ( preg_match('/\d{18}[A-Z]{2}\d?/', $data) ) {
                    $status = substr($data, 0, 3);
                    $imei = substr($data, 3, 15);
                    $command_type = substr($data, 18, 2);
                    $_data = substr($data, 20);
                    $serv->send($client, command_process($status, $imei, $command_type, $_data) );
                } else {
                     $serv->send( $client, self::result('403') );    //命令数据格式不对
                     return;
                }
            }elseif(strlen($data) < $data_len){
                $serv->send( $client, self::result('402') ); //命令数据长度不对
                return;
            }else{
                $data = substr($data, 3);
                if ( preg_match('/\d{18}[A-Z]{2}/', $data) ) {
                    $status = substr($data, 0, 3);
                    $imei = substr($data, 3, 15);
                    $command_type = substr($data, 18, 2);   
                    $_data = substr($data, 20);
                    $serv->send($client, command_process($status, $imei, $command_type, $_data) );
                } else {
                     $serv->send( $client, self::result('403') );    //命令数据格式不对
                     return;
                }
            }
        } else {
            $fdinfo = $serv->getClientInfo($client);
            if ( !isset($fdinfo['uid']) ){
                $serv->send( $client, self::result('404')); //不在线
                return;
            }else{
                self::flushRedisHeart( $fdinfo['uid'] );   //刷新服务, 不返回任何数据
                $serv->send( $client, 'OK' ); //ceshi
                return;
            }            
        }
        
        
    }

    //与客户端的连接关闭
    public static function onClose( $serv, $fd, $from_id ) {
        $imei = $_SESSION[$client_id];
        self::log('on close dev id: ' . $imei);
        self::$redis->del(self::RHkey($imei));    //删除心跳
        //self::$redis->del( self::RWkey($imei) );    //注意, 不要删除工作状态
    }
    
    /**
     * 处理客户端 请求/命令, 返回输出对象
     * @param number $status
     * @param number $imei
     * @param string $command_type
     * @param int $_data
     * @return string
     */
    private static function command_process( $status, $imei, $command_type, $_data)
    {
        
        return self::result('', $imei, $command_type, $_data);
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
        self::$redis->expire(self::RHkey($imei), 165);    //心跳时间更新, 此心跳给web端展示用.....
    }

    /**
     * 根据imei号获取devid,
     * @param type $imei
     */
    private static function getDevidByImei($imei) {

        if (0 === self::$redis->exists(self::RHkey($imei))) {
            $devid = self::$db->single('select id from bg_device where imei=' . $imei); //冗余, 防止实际没掉线, redis却过时了
            if($devid){
                self::$redis->setnx(self::RHkey($imei), $devid);
                return $devid;
            }else{
                return false;   //没找到
            }
        } else {
            return self::$redis->get(self::RHkey($imei));
        }
    }
                

    /**
     * 检查数据是否合规
     * @param type $data
     * @return array
     */
    private static function commandCheck($data) {
        $command = json_decode(substr($data, 7), true);   //data: command{imei:2259853,type:start,long:0900 } command{imei:2259853,type:stop }

        if (true !== $command && false !== $command && null !== $command) {

            //todo auth
            //todo 检查命令是否合法

            if (isset($command['imei']) && isset($command['type'])) {
                $command['ok'] = true;
                return $command;
            } else {
                //$serv->send( $fd, 'err: imei no found');
                return ['ok' => false, 'msg' => 'imei no found'];
            }
        } else {
            //$serv->send( $fd, 'command err');
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
        return '400-200-5000';
    }

    /**
     * 获取二维码
     * @param type $devid
     * @return type
     */
    private static function getQrcode($devid) {
        self::log(' client request qrcode ');
        //todo get qrcode, and send to client
        return 'http://csgxjk.com/wx/dev/' . $devid;
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
        $has = self::$redis->get($wkey);
        $_t = json_decode($has, true);
        if (isset($_t['status']) && $_t['status'] == '202') {
            self::$redis->set($wkey, '200');  //client exec ok
            self::$redis->expire($wkey, $_t['long']);    //working, stop working after 900 secends
            self::$db->insert('record_work')->cols(['appid' => $_t['appid'], 'devid' => $devid, 'start_time' => time(), 'long' => $_t['long']])->query();
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
        self::$redis->expire($wkey, 65);    //working, stop working after 900 secends
        //todo  记录手工停止, 停止都是手工的
        //self::$db->insert('record_work')->cols(['appid' => $_t['appid'], 'devid' => $devid, 'start_time' => time(), 'long' => $_t['long']])->query();
        return '200';
    }
}