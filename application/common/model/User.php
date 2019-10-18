<?php
/**
 *  用户管理
 */

namespace app\common\model;

class User extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_user';

    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['createtime'];

    public function setCreatetimeAttr() {
        return request()->time();
    }

    const ISLOCK_NO = 0;
    const ISLOCK_YES = 1;

    static $lock_desc = [
        self::ISLOCK_NO => '否',
        self::ISLOCK_YES => '是',
    ];    
    
    /**
     * 根据微信openid获取用户信息
     * @param type $openid
     */
    function getUserIdByOpenid($openid):int
    {
        $uid = $this->where( ['thr_id'=>$openid ] )->value('id');
        if($uid){
            return $uid;
        } else {
            return 0;
        }
    }
    
    /**
     * 根据用户uid获取用户openid
     * @param type $uid
     */
    function getOpenidByUid($uid)
    {
        return $this->where( ['id'=>$uid ] )->value('thr_id');
    }
    
    /**
     * 把微信用户添加到用户表中
     * @param type $wxobj
     * @return boolean
     */
    function addUserByWx($wxobj)
    {
        $data = [];
        $data['usertype'] = 1;        
        $data['thr_id'] = $wxobj['openid'];
        $data['nickname'] = addslashes($wxobj['nickname']); //页面显示时需要用 stripslashes() 把 \ 去掉
        $data['sex'] = $wxobj['sex'];
        $data['avatar'] = $wxobj['headimgurl'];
        $data['country'] = $wxobj['country'];
        $data['province'] = $wxobj['province'];
        $data['city'] = $wxobj['city'];
        if( $this->data($data)->save() ) {
            return $this->id;
        } else {
            return false;
        }
        
    }


}