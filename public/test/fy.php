<?php

/**
 * 分佣模块
 * 
 * 数据表说明
 *  h_bill :账单表, 包含收钱的设备id dev_id, 收钱时设备所属人owner_uid, 收的钱price, 是否执行分佣标记ischeck; 
 *  h_income:收入表, ; 
 *  h_user:用户表, 微信用户, 包含等级关系, 关系这块应该在一个单独的商户模块里; 
 * 
 * 处理逻辑
 *  按照代码里的 第一步, 第二步 说明
 * 
 */

$dbhost = '127.0.0.1';
$dbport = 3306;
$dbname = 'liangzi';
$dbusr = 'www';
$dbpass =  'guoqu123!@#';

$mysqli = new Mysqli($dbhost, $dbusr, $dbpass ,$dbname);
if($mysqli->connect_error){
        log('connect error:'.$mysqli->connect_errno);
        die( 'connect error:'.$mysqli->connect_errno);
}
$mysqli->set_charset('UTF-8'); // 设置数据库字符集
$mysqli->autocommit(FALSE);

//第一步: 获取昨日凌晨0点时间戳, 为每天的分佣截止时间, 怕有退款. 定时任务执行时间为凌晨2点
$yestodaytime = strtotime( date('Y-m-d',time() ) );

echo date_default_timezone_get();

//第二步: 获取没有执行分佣的账单数据.
$result = $mysqli->query(' select  id, price, dev_id, owner_uid, ischeck, createtime from h_bill  where ischeck=0 and status=1 and createtime<'.$yestodaytime );

//
//$result = $mysqli->query(' select  b.id, b.price, b.dev_id, d.userid, b.ischeck, b.createtime from h_bill b left join bg_device d on b.dev_id=d.id where b.status=1 and b.createtime>= '.$yestodaytime.' and b.createtime<'.($yestodaytime+86400) );
$data = $result->fetch_all(); // 从结果集中获取所有数据

foreach($data as $bill )
{
    if( 0==$bill[4] && 0!=$bill[3] ) //账单记录没分佣, 用户id不为 0 
    {
        if(6800==$bill[1])   //这里应该有个模型, 获取多少钱怎么分
        {
            //第三步: 分设备拥有者的部分, 70%的.
            $sql = 'insert into h_income (bid, uid, type, money, time) values (?,?,?,?,?)';
            if( $stmt = $mysqli->prepare($sql) )
            {
                $mysqli->query('begin');

                $stmt->bind_param('iiiii', $bid, $uid, $type, $money, $time );
                $bid = $bill[0];
                $uid = $bill[3];
                $type = 1;
                $money = 4800;
                $time = $bill[5];
                $stmt->execute();

                //第四部: 查看有没有上级, 查出上级用户的信息, 分每笔提成 7.35%(5元/68) or 8.88%
                if( $res = $mysqli->query('select id, level from h_user where id=( select pid from h_user where id='.$bill[3].')' ) )
                {
                    $user_p = mysqli_fetch_row($res);

                    $user_pid = $user_p[0];
                    $level = $user_p[1];
                    if ( 0 != $user_pid ) 
                    {
                        $money = 500;
                        if( 2 <= $level )//推荐人身份是代理商、合伙人、联创等, 分8.88%, 6元
                        {
                            $money = 600;
                        }

                        $uid = $user_pid;
                        $type = 2;
                        $stmt->execute();

                        if( 4==$level ){     //上级是联创
                            $type=4;
                            $money = 34;
                            $stmt->execute();

                            $type=8;
                            $money = 136;
                            $stmt->execute();

                        }

                        if( 3==$level ){    //如果上级是合伙人, 再加 市场管理补贴
                            $type=4;
                            $money = 204;
                            $stmt->execute();
                        }

                        //此后开始处理上面一级:合伙人(500台 && 10个代理商,  Are you kidding me ? lazy to coding )
                        //如果上一级管理员是代理商级别, 可以继续再上一级查找
                        if( 2 == $level || 1 == $level ) {
                            
                            if( $res = $mysqli->query('select id, level from h_user where id=( select pid from h_user where id='.$user_pid.')' ) ){
                                
                                $user_pp = mysqli_fetch_row($res);
                                $user_id_pp = $user_pp[0];
                                $user_level_pp = $user_pp[0];

                                $uid=$user_id_pp;

                                if( $user_level_pp == 4){   //如果是联创级别
      
                                    $type=4;
                                    $money = 34;
                                    $stmt->execute();

                                    $type=8;
                                    $money = 136;
                                    $stmt->execute();
                                }

                                if( $user_level_pp == 3){   //如果是合伙人级别, 计算
                                    
                                    $type=4;
                                    $money = 204;
                                    $stmt->execute();

                                    //合伙人可以再上找联创
                                    if( $res = $mysqli->query('select id, level from h_user where id=( select pid from h_user where id='.$user_pid.')' ) ){

                                        $user_pp = mysqli_fetch_row($res);
                                        $user_id_pp = $user_pp[0];
                                        $user_level_pp = $user_pp[0];
                                        
                                        $uid=$user_id_pp;

                                        if( $user_level_pp == 4){   //如果是联创级别
                                    
                                            $type=4;
                                            $money = 34;
                                            $stmt->execute();

                                            $type=8;
                                            $money = 136;
                                            $stmt->execute();
                                        }

                                    }
                                }




                            }
                        }
                        
                        
                        //此后开始处理合伙人上面一级:联创公司( he he he he  )

                    }
                }

                $mysqli->query('update h_bill set ischeck=1 where id='.$bill[0]);

                $mysqli->query('commit');
                $stmt->close();

            }else{
               // echo mysqli_stmt_error($stmt);
            }
        }

    }
}
