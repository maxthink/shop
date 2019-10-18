<?php /*a:1:{s:57:"/data/www/csgx2/application/wx/view/device_bindstatus.php";i:1564544454;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<title>设备与微信绑定</title>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<link rel="stylesheet" href="/static/layui/css/layui.css" >
<link rel="stylesheet" href="/static/global.css" >
<script src="/static/layui/layui.js"></script>
<style>
body{ padding:0px; margin:0px; background-color: #071a5a;  font-size:14px; }
.frushtoday{float:right;display:block;}
.tdate{width:30%;min-width:140px;}
.tstatus{width:10%;min-width: 60px;}
.tprice{width:10%;min-width:20px;}
.tnickname{width:50%;min-width:100px}
</style>
</head>
<body class="layui-fluid " style="padding:15px;">
    
    <div class="layui-row" >
        <div class="layui-card">
            <div class="layui-card-header">设备编码:<?php echo htmlentities($dev['imei']); ?></div>
            <div class="layui-card-body">
            为了您的账户和设备安全，请根据提示框填写真
实信息，“绑定密码”请联系客服电话（400-106-2599）
获取。
        <br/>设备绑定成功后可在此微信内查看设备运营账单、
财务统计、申请提现等操作。
            </div>
        </div>

        <div  style="margin-top:40px;"  >
            
            <div class="layui-form-item">
                <p style="color:#fff; font-size:20px; text-align:center;" >
                <?php 
                    if($bind){
                        switch($bind->status)
                        {
                            case $bind::BIND_APPLY:
                                echo '授权申请已提交';
                                break;
                            case $bind::BIND_PASS: //没这一项.....跑不到这里
                                echo '审核通过';
                                break;
                            case $bind::BIND_REFUSE:
                                echo '授权申请驳回,原因:<br/>'.$bind->comment;
                        }
                    }
                ?>
                </p>
            </div>

            <div class="layui-form-item">
                <p style="color:#ccc; font-size:14px; text-align:center;" >
                <?php 
                    if(0==$bind->status){
                        echo '审核通过后扫描设备上的二维码会到设备使用界面. 若长时间没有审核通过, 请联系客服及时审核, 确保您的权益.';
                    }
                ?>
                    
                </p>
            </div>

            
            <div class="layui-form" style="display:none;"  >
                <h3 style="color:#fff;font-size:18px; margin:5 20px;">重新提交:</h3>
                <div class="layui-form-item">

                    <div class="layui-input-inline" style="margin:0 20px;" >
                        <input type="text" name="realname" required  lay-verify="required" placeholder="管理员真实姓名" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux"  style="margin:0 20px;" >请输入您购买设备时登记的真实姓名</div>
                </div>

                <div class="layui-form-item">

                    <div class="layui-input-inline"  style="margin:0 20px;" >
                        <input type="password" name="password" required lay-verify="required" placeholder="绑定密码" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux"  style="margin:0 20px;" >请联系客服电话（400-106-2599'）提供您的设备编号尾号<?php echo htmlentities(substr($dev['imei'],-5)); ?>获取绑定密码。</div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="gogogo" >再次提交</button>
                    </div>
                </div>
            </div>
        
        </div>

    </div>
</body>
<!--script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script-->
<script>
     
    layui.use(['form','jquery'], function(){
        //var table = layui.table;  
        var form = layui.form;
        var $ = layui.$ //重点处
        
        var status=<?php echo htmlentities($bind->status); ?>;
        if( <?php echo htmlentities($bind::BIND_REFUSE); ?> == status )
        {
            $('.layui-form').show();
        }
        
        // 表单提交事件
        form.on('submit(gogogo)', function (data) {
            //data.field.roleIds = formSelects.value('roleId', 'valStr');

            $.post('<?php echo url('device/bind'); ?>', data.field, function (res) {
                layer.closeAll('loading');
                if (res.code == 0) {
                    layer.msg(res.msg, {icon: 1}, function(){
                        //location.href = location.href;
                        window.reload();
                    });
                } else {
                    layer.msg(res.msg, {icon: 2});
                }
            }, 'json');
            return false;
        });


    
    });   

</script>
</html>
