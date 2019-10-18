<?php
// 应用公共文件

if (!function_exists('apiMcJson')) {
    /**
     * json数据统一输出 ,只有结果和消息,  mc: msg + code  ,非默认值, 必须加 msg
     * @param string $msg 提示消息
     * @param int $code 结果对错， 默认0 表示正常
     */
    function apiMcJson( $msg='', $code=0 ){
        return json_encode( [ 'code'=>$code, 'msg'=>$msg ] );
    }
}