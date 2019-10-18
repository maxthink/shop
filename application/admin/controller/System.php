<?php
/**
 * 设置 控制器
 */

namespace app\admin\controller;

class System extends Common
{

    /**
     * pc站
     */
    public function pc()
    {
        return $this->fetch();
    }
    
    /*
     * 邮箱设置
     */
    public function email()
    {
	return $this->fetch();
    }

    /**
     * 后台
     */
    public function bg()
    {
        
        return $this->fetch();
    }
    
}
