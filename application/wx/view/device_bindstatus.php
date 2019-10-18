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
body{ padding:0px; margin:0px; background-color: #eaeaea;  font-size:14px; }
.frushtoday{float:right;display:block;}
.tdate{width:30%;min-width:140px;}
.tstatus{width:10%;min-width: 60px;}
.tprice{width:10%;min-width:20px;}
.tnickname{width:50%;min-width:100px}
</style>
</head>
<body>
    
    <div class="layui-row" >
        <div class="layui-card" style="background-color:#F2F2F2;clear:both;color:#666">
            <div class="layui-card-body">
            为了您的账户和设备安全，请根据提示框填写真实信息，“设备授权码”请联系客服电话（<a href="tel:400-106-2599" style="color:#ff3300">400-106-2599</a>）获取。
            <br/>设备授权成功后可在此微信内查看设备运营账单、财务统计、申请提现等操作。
            </div>
        </div>

        <div  style="padding:8% 4%;text-align:center;">
            <div class="layui-form-item">
                <p style="font-size:20px;" >
                <?php 
                    if($bind){
                        switch($bind->status)
                        {
                            case $bind::BIND_APPLY:
                                echo '<span style="color:#1E9FFF;"><i class="layui-icon layui-icon-log" style="font-size:40px;"></i><br/>申请已提交</span>';
                                break;
                            case $bind::BIND_PASS: //没这一项.....跑不到这里
                                echo '<span style="color:#009688;"><i class="layui-icon layui-icon-ok" style="font-size:40px;"></i><br/>审核通过</span>';
                                break;
                            case $bind::BIND_REFUSE:
                                echo '<span style="color:#FF5722;"><i class="layui-icon layui-icon-close" style="font-size:40px;"></i><br/>申请驳回,原因:<br/>'.$bind->comment.'</span>';
                        }
                    }
                ?>
                </p>
            </div>

            <div class="layui-form-itme form-apply">
                <p style="color:#666; font-size:14px; text-align:center;" >
                <?php 
                    if(0==$bind->status){
                        echo '审核通过后扫描设备上的二维码会到设备使用界面. 若长时间没有审核通过, 请联系客服及时审核, 确保您的权益.';
                    }
                ?>
                </p>
            </div>

            <div class="layui-form-itme form-refuse">
                <p style="color:#666; font-size:14px; text-align:center;" >
                <?php 
                    if(0==$bind->status){
                        echo '审核通过后扫描设备上的二维码会到设备使用界面. 若长时间没有审核通过, 请联系客服及时审核, 确保您的权益.';
                    }
                ?>
                </p>
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
        
        var status={$bind->status};

        if( {$bind::BIND_APPLY} == status )
        {
            $('.form-apply').show();    //审核中, 显示提醒
        }
        else if( {$bind::BIND_REFUSE} == status )
        {
            $('.form-refuse').show();   //拒绝了, 显示再提交
        }
        
        // 表单提交事件
        form.on('submit(gogogo)', function (data) {
            //data.field.roleIds = formSelects.value('roleId', 'valStr');

            $.post('{:url('device/bind')}', data.field, function (res) {
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
