<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

// 说明：获取完整URL


function curPageURL() 
{
    $pageURL = 'http';

    if ( isset($_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on") 
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ( isset($_SERVER["HTTPS"] ) && $_SERVER["SERVER_PORT"] != "80") 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } 
    else 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

if (!function_exists('apiJson')) {
    /**
     * 数据表格 json 数据统一输出
     * @param array $data 要输出的数据
     * @param int $code 结果对错， 默认0 表示正常
     * @param string $msg 提示消息
     */
    function apiJson( $res='', $code=0, $msg='' ){
        //header('content-type:application/json;charset=utf-8'); //框架默认返回json格式.
        if(''===$res){ $res=[]; }
        $count = $res['count'] ?? (is_array($res) ? count($res) : 0);
        $sum = $res['sum'] ?? 0;
        $data = $res['data'] ?? $res; //前面有部分用的 $data, 表格分页加上了 总数 count,改为这种兼容做法
        
        return [ 'code'=>$code, 'msg'=>$msg, 'count'=>$count,'data'=>$data,'sum'=>$sum ];
    }
}

if (!function_exists('mcJson')) {
    /**
     * json数据统一输出 ,只有结果和消息,  mc: msg + code  ,非默认值, 必须加 msg
     * @param string $msg 提示消息
     * @param int $code 结果对错， 默认0 表示正常
     */
    function mcJson( $msg='', $code=0, $data='' ){
        return [ 'msg'=>$msg , 'code'=>$code,  'data'=>$data];
    }
}

if (!function_exists('xtime')) {
    /**
     * j性能分析用
     * 
     */
    function xtime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}

/*
 * 前端日志内容
 * level 等级, info, error , 不用int, 因为会扩展
 */
function hlog($messagePosition, $content,$level='info')
{
    app\common\model\Hlog::create(['router'=>$messagePosition, 'content'=>$content, 'level'=>$level]);

}

 