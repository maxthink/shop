<?php
/**
 * Created by PhpStorm.
 * User: nango
 * Date: 2018/1/6
 * Time: 18:06
 */

namespace app\admin\model;

use app\common\model\Common;
use think\facade\Request;

class Log extends Common {

    protected $insert = ['ip','time'];

    public function setIpAttr() {
        return request()->ip();
    }

    public function setTimeAttr() {
        return request()->time();
    }
    
    /**
     * 添加新记录
     * @param type $uid
     * @param type $type
     * @param type $content
     * @param type $username
     */
    public function add($uid, $type, $content='', $username='' ){
        
        $this->userid = $uid;
        $this->type = $type;
        
        //$this->content = $content;
        $this->username = $username;
        
        $this->content = request()->route();

        if( $this->save() ){
            return true;
        } else {
            return false;
        }
    }
    
}