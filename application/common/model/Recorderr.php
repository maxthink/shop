<?php
/**
 *  设备api请求记录
 */

namespace app\common\model;

class Recorderr extends Common
{
    
    protected $pk = 'id';
    protected $table = 'record_err';
    
    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['createtime'];
    
    public function setCreatetimeAttr()
    {
        return request()->time();
    }    
    
    public function add( $type, $content )
    {
        return $this->save( [ 'type'=>$type,'content'=>$content,'time'=>time() ] );
    }
    

}