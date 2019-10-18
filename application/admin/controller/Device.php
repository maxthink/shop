<?php
/**
 * 设备 控制器
 */

namespace app\admin\controller;

use app\common\model\Device as DevModel;
use app\common\model\Meal;
use app\common\model\Devtype;
use think\facade\Request;

class Device extends Common
{

    /**
     * 设备列表
     */
    public function index()
    {
        if( Request::instance()->isPost() ) {    //post 方式, 获取数据
            $page = input('page',1,'intval');
            $limit = input('limit',10,'intval');
            $imei = input('imei','');
            $type = input('type',0,'intval');
            
            $where[] = '1=1';
            if(!empty($imei) && preg_match('/\d+/', $imei) ){
                $where[]= "imei like '%$imei%' ";
            }
            if(0!==$type && is_int($type)){
                $where[]= 'type='.$type ;
            }
            $order = ['lasttime desc'];
            
            $dmodel = new DevModel();            
            $list = $dmodel->list_admin($page,$limit,$where,$order);
            //echo $dmodel->getLastSql();
            
            $_list = $list['data'];
            foreach($_list as $key=>$val){
                $online = cache('h_'.$val['imei']);
                if($online){
                    $list['data'][$key]['online']='在线';
                }else{
                    $list['data'][$key]['online']='';
                }
            }
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面

            $objarr = Meal::all();
            $this->assign('objarr', $objarr);
            $devtype = Devtype::all();
            $this->assign('devtype', $devtype);
            return $this->fetch();
        }
        
    }
    
    /*
     * 添加设备
     */
    public function add()
    {
        if ( Request::instance()->isPost() ) {
            
            $params = input('post.');

            $M = new DevModel();
            $M->type = $params['type'];
            $M->imei = $params['imei'];
            $M->setmealId = $params['setmealId'];
            $M->bindpass = get_randomstr(6);
            
            if ( $M->save() ) {
                return apiJson('', 0, '添加成功');
            } else {
                return apiJson('', 1, '添加出错');
            }
        } else {
            
            //$this->assign('role', AdminRole::all());
            return $this->fetch();
        }
    }

    /**
     * 修改设备信息
     */
    public function update()
    {
        if ( Request::instance()->isPost() ) {

            $params = input('post.');

            //验证规则
            //$result = $this->validate($params, 'app\admin\validate\Admin.edit');
            //if ($result !== true) {
            //    return ['status' => 0, 'msg' => $result, 'url' => ''];
            //}

            $M = DevModel::get($params['id']);
            $M->type = $params['type'];
            $M->imei = $params['imei'];
            $M->setmealId = $params['setmealId'];

            if ( $M->save() ) {
                return apiJson('',0,'修改成功');
            } else {
                return apiJson('',1,'修改失败'.$M->getError());
            }
        } else {
            return apiJson('',1,'没有参数');
        }
    }
    
    //远程操作设备
    public function work()
    {
        $devid = input('post.id',0,'intval');
        $type = input('post.type','');
        $long = input('post.long',0,'intval')*60; //运行时间, 客户端显示单位是分钟, 服务器用的是秒
        $imei = DevModel::getimeibyid($devid);

        if('start'==$type)
        {
            $re = cache('w_'.$imei);
            if(200==$re) {
                return apiJson('', 0, '设备正在运行中');
            }elseif(201==$re){
                return apiJson('', 0, '设备正在启动中');                
            }elseif(404==$re){
                return apiJson('', 0, '设备不在线');                
            }else{
                $js = json_decode($re, true);
                if( isset($js['status']) && 202==$js['status'] ){
                    return apiJson('', 0, '设备正在启动中');
                }else{
                    $h = cache('h_'.$imei);
                    if( 0==$h ){
                        return apiJson('', 0, '设备不在线');
                    }else{
                        //command{imei:2259853,type:start,long:0900 } command{imei:2259853,type:stop } 
                        // 建立连接，@see http://php.net/manual/zh/function.stream-socket-client.php
                        $client = stream_socket_client('tcp://172.17.161.47:7273');
                        if(!$client){ exit("can not connect"); }
                        // 模拟超级用户，以文本协议发送数据，注意Text文本协议末尾有换行符（发送的数据中最好有能识别超级用户的字段），这样在Event.php中的onMessage方法中便能收到这个数据，然后做相应的处理即可
                        $re = fwrite($client, 'command{"dr_id":"0","imei":"'.$imei.'","type":"start","long":"'.$long.'"}'."\n");

                        //命令里的 record_id , 0 是管理后台直接下发命令, 
                        $msg = fread($client, 1024);
                        return apiJson('', 0, $msg);
                       
                    }
                }                
            }
            
        }
        else if('stop'==$type)
        {
            $re = cache('w_'.$imei);
            if(500==$re) {
                return apiJson('', 0, '设备已停止');
            }elseif(501==$re){
                return apiJson('', 0, '设备正在停止中');                
            }elseif(404==$re){
                return apiJson('', 0, '设备不在线');                
            }else{

                $h = cache('h_'.$imei);
                if( 0==$h ){
                    return apiJson('', 0, '设备不在线');
                }else{
                    //command{imei:2259853,type:start,long:0900 } command{imei:2259853,type:stop } 
                    // 建立连接，@see http://php.net/manual/zh/function.stream-socket-client.php
                    $client = stream_socket_client('tcp://172.17.161.47:7273');
                    if(!$client){ exit("can not connect"); }
                    // 模拟超级用户，以文本协议发送数据，注意Text文本协议末尾有换行符（发送的数据中最好有能识别超级用户的字段），这样在Event.php中的onMessage方法中便能收到这个数据，然后做相应的处理即可
                    $re = fwrite($client, 'command{"dr_id":"1","imei":"'.$imei.'","type":"stop","long":"'.$long.'"}'."\n");

                    $msg = fread($client, 1024);
                    return apiJson('', 0, $msg);

                }
                              
            }
        }
        
        
    }
    
    /**
     * 锁定设备,不让使用
     */
    public function lock()
    {
        $id = input('param.Id/d', 0);
        $state = input('param.state/d', '');
        if( 0 == $id ){
            return apiJson('', 1, '参数错误');
        }
        
        //获取管理员对象
        $Mobj = DevModel::get($id);

        if( DevModel::ISLOCK_YES===$state && $state==$Mobj->lock ){
            return apiJson('',0,'已是锁定状态'); 
        }elseif( DevModel::ISLOCK_NO===$state && $state==$Mobj->lock ){
            return apiJson('',0,'已是正常状态'); 
        }
        
        //todo 赋值状态,改变
        $Mobj->lock = $state;
        
        if( DevModel::ISLOCK_NO===$state ){
            if ( $Mobj->save()) {
                return apiJson('',0,'解锁成功');
            } else {
                return apiJson('', 1, '解锁失败');
            }
        } if( DevModel::ISLOCK_YES===$state ) {
            if ( $Mobj->save()) {
                return apiJson('',0,'锁定成功');
            } else {
                return apiJson('', 1, '锁定失败');
            }
        }
         
    }

    /**
     * 删除信息
     */
    public function del()
    {
        $id = input('param.Id/d', 0);
        $Mobj = DevModel::get($id);
        $Mobj->lock = DevModel::ISLOCK_YES;
        if ($Mobj->save()) {
            return apiJson();
        } else {
            return apiJson('', 1, '删除失败');
        }
    }
    
    
}
