<?php
/**
 * 设备绑定, 审核模块
 */

namespace app\admin\controller;

use app\common\model\Bind as Mbind;
use app\common\model\User;
use app\common\model\Device;
use think\facade\Request;

class Bind extends Common
{
    /**
     * 绑定申请列表
     */
    public function index()
    {
        if( Request::instance()->isPost() ) {    //post 方式, 获取数据
            $page = input('page',1,'intval');
            $limit = input('limit',10,'intval');
            
            $imei = input('s1','');
            $nickname = input('s2','','addslashes');
            $status = input('s3','');   //默认申请的?

            $where[]= "1=1";
            if( ''!==$status ){
                $where[]= "status=".$status;
            }
            if(!empty($nickname) ){
                $where[]= "nickname like '%$nickname%' ";
            }
            if(!empty($imei) && preg_match('/\d+/', $imei) ){
                $where[]= "imei like '%$imei%' ";
            }

            $dmodel = new Mbind();
            $list = $dmodel->list_admin($page,$limit,$where);
            //echo $dmodel->getLastSql();
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面

            $this->assign('Mbind',new Mbind() );
            return $this->fetch();
        }
        
    }

    /**
     * 审核
     */
    public function update()
    {
        if ( Request::instance()->isPost() ) {

            $params = input('post.');

            $Mdev = Device::get($params['device_id']);
            
            if(0!=$Mdev->userid){
                return apiJson('',1,'修改失败: 该设备已有管理员绑定.');
            }

            try
            {
                \think\Db::startTrans();
                $M = Mbind::get($params['id']);
                $M->status = $params['status'];
                $M->comment = $params['comment'];
                $M->time_verify = time();
                $M->tmeline = time();
                
                if ( $M->save() ) {

                    if( Mbind::BIND_PASS == $M->status) //审核通过需要更新user realname pid, device userid
                    {
                        $Mdev->userid = $params['wx_uid'];
                        $Mdev->save();

                        // 添加用户实际姓名, 上级uid
                        $Muser = User::get($params['wx_uid']);
                        $Muser->realname = $params['realname'];
                        $Muser->pid = $params['p_uid'];
                        $Muser->save();
                    }
                    
                    \think\Db::commit();
                    
                    $this->level($params['wx_uid']);    //计算等级去, 此处可以用消息队列
                    if( 0!=$params['p_uid'] ){
                        $this->level($params['p_uid']); //计算完本人再计算刚刚本人的上级
                    }
                    
                    return apiJson('',0,'修改成功');
                } else {
                    return apiJson('',1,'修改失败'.$M->getError());
                }

            } catch (\Exception $e) {
                // 回滚事务
                \think\Db::rollback();
                return apiJson('',1,'修改失败'.$e->getMessage());
            }
                
             
        } else {
            return apiJson('',1,'没有参数');
        }
    }
    
    

    //解绑
    public function unbind()
    {
        if ( Request::instance()->isPost() ) {
            
            $params = input('post.');

            $M = Mbind::get($params['id']);
            $M->status = Mbind::BIND_UNBIND; //解绑
            $M->comment = $params['comment'];
            $M->time_unbind = time();
            $M->tmeline = time();
            
            try
            {
                \think\Db::startTrans();
                $M->save();

                $Mdev = Device::get( $M->device_id );
                $Mdev->userid = 0;
                $Mdev->bindpassword = get_randomstr(6);
                $Mdev->save();

                \think\Db::commit();
                return apiJson('',0,'解绑成功');

            } catch (\Exception $e) {
                // 回滚事务
                \think\Db::rollback();
                return apiJson('',1,'解绑失败'.$e->getMessage() );
            }

        }
    }
    
    
    /*
     * 计算用户等级
     * 等级只能从低到高
     */
    private function level($uid)
    {
        $re = \think\Db::query('select level from h_user where id='.$uid);
        $level = $re[0]['level'];

        //自己手里设备数
        $res = \think\Db::query( 'select count(*) as cou from bg_device where userid='.$uid );
        $couMyself = $res[0]['cou']; 

        //推荐的人手里设备数
        $res2 = \think\Db::query( 'select count(*) as cou from bg_device where userid in (select id from h_user where pid='.$uid.')' );
        $couDaili = $res2[0]['cou'];
        
        //如果自己有至少一台设备, 并且推荐的至少有三台, 代理商
        if( $couMyself>0 && ($couMyself+$couDaili)>=4 ){
            Hlog(__METHOD__,' 二级 ' );
            //判断是否可以是合伙人
            if($couDaili>=500){
                //查询推荐的代理商有多少个.
                $res3 = \think\Db::query('select count(*) cou from h_user where level=2 and pid='.$uid);
                $couDailiBelong = $res3[0]['cou'];
                if($couDailiBelong >= 10){
                    if( $level<3 ){  //只能升级, 不能降级
                        \think\Db::query('update h_user set level=3 where id='.$uid );
                    }
                }
            }else{
                Hlog(__METHOD__,' 变二级 ' );
                if( $level<2 ){  //只能升级, 不能降级
                    Hlog(__METHOD__,' 执行二级 ' );
                    \think\Db::query('update h_user set level=2 where id='.$uid );
                }
            }

        } elseif ( $couMyself>0 ) {

            if( $level<1 ){  //只能升级, 不能降级
                \think\Db::query('update h_user set level=1 where id='.$uid );
            }
        }

        Hlog(__METHOD__,' 最后执行sql: '.\think\Db::getlastsql() );

    }
    
    
}
