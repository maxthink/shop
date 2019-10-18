<?php
/**
 *  设备api请求记录
 */

namespace app\common\model;

class Recordapi extends Common
{
    
    protected $pk = 'id';
    protected $table = 'record_api';
    
    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['createtime'];
    
    public function setCreatetimeAttr()
    {
        return request()->time();
    }    
    
    public function add($appid, $devid, $long )
    {
        return $this->save(['appid'=>$appid,'devid'=>$devid,'start_time'=>time(), 'long'=>$long]);
    }
    

}