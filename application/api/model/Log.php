<?php

/*
 * 记录微信操作记录.
 */

/**
 * Description of Log
 *
 * @author mljm
 */

namespace app\wxapi\model;

use think\Model;

class Log extends model {

    protected $name = 'admin';
    protected $pk = 'id';

    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['time'];

    public function setTimeAttr() {
        return time();
    }
    
    /**
     * 记录日志
     * @param type $message
     */
    public function record( &$message )
    {
        $data = [
                'pcid' => $message['ToUserName'],   //接收方帐号（公众号id）
                'user' => $message['FromUserName'], //发送方帐号（OpenID, 代表用户的唯一标识）
                'time' => $message['CreateTime'],     //消息创建时间
                'msgtype'=> $message['MsgType'],
                'content' => $message['Content'],
                'msgid' => $message['MsgId'],
            ];
        $this->save($data);
    }

}
