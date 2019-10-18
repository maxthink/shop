<?php

/**
 * 微信后台
 *
 */

namespace app\wx\controller;


use app\common\model\User;
use app\common\model\Bill as Mbill;
use app\common\model\Income;
use app\common\model\Cash;
use app\common\model\CashAccount;
use think\facade\Session;


class Manage extends Common {

    private $userid = null;

    protected $user = null; //用户信息


    //获取用户id
    protected function initialize()
    {
        //echo '<br>Manage init 初始化: '.memory_get_usage();
        $this->oauthLogin(); //微信登录
        $this->userid = Session::get('wechat_user');
        //$this->userid = 23;
        $this->user = User::get($this->userid);

        if( !$this->user ){
            Session::delete('wechat_user');
            $this->oauthLogin(); //微信登录
        }
    }

    //页面显示
    public function index()
    {
        if( $this->user->level <= 0 ){
            return '<p style="display:block;color:#FE2372;font-size:48px; width:100%;text-align:center; margin-top:250px;">抱歉, 您没有权限查看</p>';
        }
        //$this->assign('user',$user);
        $uid = str_pad($this->userid,5,'0',STR_PAD_LEFT);
        $this->assign('userid',$uid);
        return $this->fetch();
    }

    //收入统计
    public function count()
    {
        $tcount = Mbill::todayCount($this->user->id);  //今日账单总计
        $ycount = Mbill::yestodayCount($this->user->id);     //昨日账单总计
        $Iycount = Income::yestodayCount($this->user->id);     //昨日收入总计
        $Iacount = Income::allCount($this->user->id);      //所有收入总计
        $Emoney = Cash::moneyused($this->user->id);

        return apiJson(['tc'=>$tcount,'yc'=>$ycount,'iyc'=>$Iycount,'iac'=>$Iacount,'ema'=>$Emoney],0,'');
    }

    //账单列表接口 今日
    public function todaylist()
    {
        $page = input('get.page',1,'intval');
        $size = input('get.size',15,'intval');

        if($this->user){

            $data = Mbill::todaylist( $this->user->id, $page, $size );

            return apiJson($data, 0, '' );
        }else{
            return apiJson('',2,'未登录');
        }

    }

    //账单列表接口 昨日
    public function yestodaylist()
    {
        $page = input('get.page',1,'intval');
        $size = input('get.size',15,'intval');

        if($this->user){
            $data = Mbill::yestodaylist($this->user->id, $page, $size );

            return apiJson($data, 0, '' );
        }else{
            return apiJson('',2,'未登录');
        }

    }

    //历史账单, 昨日之前的
    public function historylist()
    {
        $page = input('get.page',1,'intval');
        $size = input('get.size',15,'intval');

        if($this->user){
            $data = Mbill::historylist($this->user->id, $page, $size );
            return mcJson('',0 , $data );
        }else{
            return mcJson('未登录', 2);
        }

    }

    //收入记录
    public function income()
    {
        $page = input('get.page',1,'intval');
        $size = input('get.size',15,'intval');

        if($this->user){
            $data = Income::historylist($this->user->id, $page, $size );

            return mcJson('',0 , $data );
        }else{
            return mcJson('未登录', 2);
        }
    }

    //提现列表
    public function cashlist()
    {
        if(request()->isPost())
        {
            $page = input('get.page',1,'intval');
            $size = input('get.size',15,'intval');

            if($this->user){
                $data = Cash::list_client($page, $size, ['uid='.$this->user->id ] );

                return mcJson('', 0, $data );
            }else{
                return apiJson('',2,'未登录');
            }
        }else{
            $Mcash = new Cash();
            $this->assign('Mcash',$Mcash);
            return $this->fetch();
        }
    }

    //提现
    public function cash()
    {
        if(request()->isPost())
        {
            $price = input('post.price',0,'intval');
            $aid = input('post.aid',0,'intval');
            $Iacount = Income::allCount($this->user->id);      //所有收入总计
            $Emoney = Cash::moneyused($this->user->id);          //可提数

            //500元起,每次至少加100, 提现服务费2000元以下按2%收取, 2000元(含)以上按1%收取
            if($price<500){
                return mcJson('提现金额至少500元',1 );
            }

            if($Iacount-$Emoney-$price < 0 ){
                return mcJson('提现金额超过账户剩余金额',1 );
            }

            //服务费 2000以下 2%; 2000以上1%;
            $service = 0;
            if( $price < 2000 ){
                $service = $price*0.02;
            }else{
                $service = $price*0.01;
            }
            

            $account = CashAccount::get($aid);

            $M = new Cash();
            $M->uid = $this->user->id;
            $M->money = $price*100;
            $M->service = $service*100;
            $M->money_true = ($price-$service) * 100;
            $M->type = 3;
            $M->aid = $aid;
            $M->account_name = $account->name;
            $M->account_bank = $account->bank;
            $M->account_number = $account->account;
            $M->account_tax = $account->tax;

            $M->time_apply = time();
            $M->timeline = time();  //统一显示用时间

            try{
                $M->save();
                //hlog(__FILE__.'/'.__LINE__, $M->getlastsql() );
                return mcJson('提现成功',0 );
            } catch ( \Exception $e){
                //todo 提现出错记录下来
                hlog(__FILE__.'/'.__LINE__, $e->getMessage().Cash::getlastsql() );
                return mcJson(' 数据处理未知错误',1 );
            }

        }else{
            return $this->fetch();
        }
        
    }

    //提现页面初始数据
    public function cashinit()
    {
        try{
            //可提金额
            $Iacount = Income::allCount($this->user->id);      //所有收入总计
            $Emoney = Cash::moneyused($this->user->id);
            $less = $Iacount-$Emoney;

            //默认提现账户信息
            $account = CashAccount::getDefault($this->userid);
            return mcJson('',0,['less'=>$less,'account'=>$account]);
        }catch( \Exception $e){
            hlog(__METHOD__, $e->getMessage().CashAccount::getlastsql() );
            return mcJson('error:'.$e->getMessage(),1);
        }
    }

    //对公账户添加/更新 , 前端方便, 添加更新就放一块了......
    public function account_update()
    {
        $data['name']=input('post.name','','strip_tags');
        $data['account']=input('post.account','strip_tags');
        $data['bank']=input('post.bank','','strip_tags');
        $data['tax']=input('post.tax','','strip_tags');
        $data['uid']=$this->userid;
        //$data['default']=1;

        $id = input('post.id',0,'intval');
        if($id)
        {
            //有id, 更新现有代码
            $M = CashAccount::get($id);
            if($M)
            {
                try{
                    $M->save($data);
                    return mcJson('',0,['id'=>$M->id,'name'=>$M->name,'account'=>$M->account]);
                }catch(\Exception $e) {
                    hlog(__METHOD__, $e->getMessage() );
                    return mcJson('添加出错',1);
                }
            }else{
                hlog(__METHOD__, '修改出错, 没有id为'.$id.'的对公账户' );
                return mcJson('修改出错',1);
            }
                
        }else{

            $count = CashAccount::getCountByUid($this->userid);
            if($count>=5){
                return mcJson('最多添加五个对公账户',1);
            }

            try{
                $M = new CashAccount();
                $M->save($data);
                return mcJson('',0,['id'=>$M->id,'name'=>$M->name,'account'=>$M->account]);
            }catch(\Exception $e){
                hlog(__METHOD__, $e->getMessage().CashAccount::getlastsql() );
                return mcJson('添加出错',1);
            }
        }
         
        
    }

    //收款账户列表
    public function account_list(){
        try{
            $accounts = CashAccount::getListByUid($this->userid);
            return mcJson('',0,$accounts);
        }catch( \Exception $e ){
            hlog(__METHOD__, $e->getMessage() );
            return mcJson('错误', 1);
        }
    }

    //获取默认收款账户
    public function account_default()
    {
        try{
            $account = CashAccount::getDefault($this->userid);
            return mcJson('',0,$account);
        }catch( \Exception $e ){
            hlog(__METHOD__, $e->getMessage().CashAccount::getlastsql() );
            return mcJson('错误', 1);
        }
    }

}
