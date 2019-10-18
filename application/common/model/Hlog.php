<?php
/**
 *  前端用户操作日志
 */

namespace app\common\model;

class Hlog extends Common
{
    
    protected $pk = 'id';
    protected $table = 'h_log';
    
    /**
     * 新增的时候进行字段的自动完成机制
     * @var array
     */
    protected $insert = ['timeline'];
    
    public function setTimelineAttr()
    {
        return request()->time();
    }
    

}