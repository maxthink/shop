<?php /*a:1:{s:54:"/data/www/csgx2/application/wxapi/view/device_work.php";i:1560222026;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<title>创世共想 量子消融仪 设备编号<?php echo htmlentities($dev['id']); ?></title>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<style>
body{ background-color: #071a5a; }

.title{margin: 15px; font-size:22px; font-weight:blod; color:#fff;  }
.title_taocan{color:#fff; }

.tc{ width:100%; height:100px; background-color:#f4f3f9;  text-align: center; display:inline-block; margin-bottom:1em; }
.tc p{ margin:0.5em;}
.devstatus{ color: #FF0; font-size:20px; text-align:center; }

</style>
</head>
<body>
    <div><p class="title">北京创世共想</p></div>
    <hr>
    <div><p class="title_taocan" >设备编码: <span id="devcode"><?php echo htmlentities((isset($dev['imei']) && ($dev['imei'] !== '')?$dev['imei']:'5201314')); ?></span> </p></div>
    <div><p class="title_taocan devstatus" ><?php 
            if($online){
                if($workstatus==0){
                    echo '设备在线, 未运行';
                }else{
                    echo '设备正在运行中';
                }
            }else{
                echo '设备未在线';
            }
        
        ?></p></div>
    <div><p class="title_taocan">套餐选择</p></div>
    <?php foreach( $setmeal as $obj ): ?>
    <div class="tc" data-origal="<?php echo $obj['id']?>" >
        <p><?php echo $obj['name']?></p>
        <p><?php echo intval($obj['long']/60) ?>分钟</p>
        <p><?php echo ($obj['price']/100) ?>元</p>
    </div>              
    <?php endforeach; ?>

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
                        'getBrandWCPayRequest', res.data.json,
                        function(res){
                            if(res.err_msg === "get_brand_wcpay_request:ok" ) {
                                 // 使用以上方式判断前端返回,微信团队郑重提示：
                                 // res.err_msg将在用户支付成功后返回
                                 // ok，但并不保证它绝对可靠。
                                 alert('支付ok');
                            }
                        }
                    );

                }else{
                    alert(res.msg);
                }
            }
        );
    }
</script>
</html>
