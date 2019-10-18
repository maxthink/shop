<?php /*a:1:{s:51:"/data/www/csgx2/application/wx/view/device_work.php";i:1561097135;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<title>北京创世共想科技有限公司</title>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<style>
body{ background-color: #071a5a; color:#ffffff; }
a:active a:before a:after{color:#ffffff; }

.title{margin: 15px; font-size:22px; font-weight:blod; color:#fff;  }
.title_taocan{color:#fff; }

.tc{ width:100%; height:80px; background-color:#f4f3f9; color:#000; text-align: center; display:inline-block; margin-bottom:1em; }
.tc p{ margin:0.5em;}
.devstatus{ color: #FF0; font-size:20px; text-align:center; }

#nav {width:100%; position:fixed; z-index: 10; bottom: 1px;}
#nav a{ width:49.1%; height:40px; display:inline-block; text-align:center; line-height:40px; background-color:#fff; text-decoration:none;  }
</style>
</head>
<body>
    <div><p class="title">量子微磁仪</p></div>
    <hr>
    <div><p class="title_taocan" >设备编码: <span id="devcode"><?php echo htmlentities((isset($dev['imei']) && ($dev['imei'] !== '')?$dev['imei']:'5201314')); ?></span> </p></div>
    <div><p class="title_taocan devstatus" >........</p></div>
    <div><p class="title_taocan">套餐选择</p></div>
    <?php foreach( $setmeal as $obj ): ?>
    <div class="tc" data-origal="<?php echo $obj['id']?>" >
        <p><?php echo $obj['name']?></p>
        <p><?php echo $obj['sum'] ?>次 <?php echo intval($obj['long']/60) ?>分钟 <?php echo ($obj['price']/100) ?>元</p>
    </div>              
    <?php endforeach; ?>
    
    <div id="nav">
        <a href="javascript: return false;"> <span>首页</span> </a>
        <a href="<?php echo url('wx_dev_record','id',''); ?>/<?php echo htmlentities($dev['id']); ?>"> <span>记录</span> </a>
    </div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script>
<script>
    
    $('.tc').click(function(obj){
        var mid = $(this).attr('data-origal');
        pushBill( <?php echo htmlentities($dev['id']); ?>, mid );
    }); 
    
    function pushBill( devid, mid )
    {
        $.post(
            '<?php echo url('wx_dev_bill'); ?>',
            { did:devid, mealid:mid },
            function(res){
                if(res.status === 0){
                    //todo 下单支付

                    WeixinJSBridge.invoke(
                        'getBrandWCPayRequest', res.data ,
                        function(res){
                            if(res.err_msg === "get_brand_wcpay_request:ok" ) {
                                 // 使用以上方式判断前端返回,微信团队郑重提示：
                                 // res.err_msg将在用户支付成功后返回
                                 // ok，但并不保证它绝对可靠。
                                 
                                 setTimeout(function(){
                                    location.href = '<?php echo url('wx_dev_record','id',''); ?>/'+<?php echo htmlentities($dev['id']); ?>;
                                },2000);
                            }else if( res.err_msg === "get_brand_wcpay_request:cancel" ){
                                //alert('取消支付');
                            }else{

                            }
                        }
                    );

                }else{
                    alert(res.msg);
                }
            }
        );
    }

    //更新设备状态
    function device_status(devid){
        if(0===devid){
            clearInterval(myVar);
        }
        $.post(
            '<?php echo url('device/status'); ?>',
            { devid:devid },
            function(res){
                if(res.code === 0){
                    $('.devstatus').html('设备未在线');
                }else if(res.code===1){
                    $('.devstatus').html('设备在线, 未运行');
                }else if(res.code===2){
                    $('.devstatus').html('设备运行中');
                }
            }
        );
    }

    device_status(<?php echo htmlentities((isset($dev['id']) && ($dev['id'] !== '')?$dev['id']:0)); ?>);
    var myVar = setInterval('device_status(<?php echo htmlentities((isset($dev['id']) && ($dev['id'] !== '')?$dev['id']:0)); ?>)', 15000);

</script>
</html>
