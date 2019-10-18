<?php
/**
 * 后台主面板 控制器
 */
namespace app\admin\controller;

//use think\Request;

class Console extends Common
{

    /**
     * 控制台 
     */
    public function index()
    {
        return $this->fetch();
    }
    
    /**
     * 分析页
     */
    public function analysis()
    {
        return $this->fetch();
    }

    /**
     * 欢迎页
     */
    public function welcome()
    {
        return $this->fetch();
    }

}
