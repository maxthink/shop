<?php

/**
 * 微信接口  主 控制器
 */

namespace app\wx\controller;
 

class Index extends Common {
    
    public function index()
    {
        return $this->fetch();
    }

    

}
