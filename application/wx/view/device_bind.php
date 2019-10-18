<!DOCTYPE html>
<html>
<head>
<title>设备授权</title>
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
.dev-header{background-color:#0093D3;color:#fff;padding:16px 15px;font-size:16px;}
.container{padding:0 10px;}
.layui-form-mid{padding:2% 0!important;margin-right:0;}
.layui-input{height:44px;}
</style>
</head>
<body>
    <div class="dev-header">设备编码:{$bindinfo.imei}</div>

        <div class="layui-form container" style="display:none;">
            <div class="layui-form-mid layui-word-aux">请输入您购买设备时登记的真实姓名</div>
            <input type="text" name="realname" required  lay-verify="required" placeholder="管理员真实姓名" autocomplete="off" class="layui-input">
            <div class="layui-form-mid layui-word-aux">请输入设备授权码</div>
            <input type="password" name="password" required lay-verify="required" placeholder="设备授权码" autocomplete="off" class="layui-input">
            <div class="layui-form-mid layui-word-aux">请输入推荐人ID，没有留空</div>
            <input type="text" name="uid" placeholder="推荐人ID" autocomplete="off" class="layui-input">
            <div class="layui-form-mid layui-word-aux">请联系客服电话（400-106-2599）提供您的设备编号尾号{$bindinfo.imei|substr=-5}获取设备授权码。</div>
            <br/>
            <div class="layui-card" style="background-color:#F2F2F2;margin:5% 0;clear:both;color:#666">
                <div class="layui-card-body">
                为了您的账户和设备安全，请根据提示框填写真实信息，“设备授权码”请联系客服电话（<a href="tel:400-106-2599" style="color:#ff3300">400-106-2599</a>）获取。
                <br/>设备授权成功后可在此微信内查看设备运营账单、财务统计、申请提现等操作。
                </div>
            </div>
            <div>
                <button class="layui-btn layui-btn-radius layui-btn-fluid" lay-submit lay-filter="gogogo" style="background-color:#519DDA;" >立即提交</button>
            </div>
        </div>

        <div class="apply_ok" style="padding:8% 4%;text-align:center;display:none;" >
            <div class="layui-form-item" > 
                <p style="color:#fff; font-size:20px; text-align:center;" >
                    <span style="color:#1E9FFF;">
                        <i class="layui-icon layui-icon-log" style="font-size:40px;"></i>
                        <br/>您的授权申请已提交，请等待审核
                    </span>
                </p>
            </div>
        </div>

        <div class="bind_status" style="padding:8% 4%;text-align:center;">
            <div class="layui-form-item bind_apply" style="display:none;">
                <p style="font-size:20px;" >
                    <span style="color:#1E9FFF;"><i class="layui-icon layui-icon-log" style="font-size:40px;"></i><br/>您的授权申请已提交，请等待审核</span>
                </p>
            </div>
            <div class="layui-form-item bind_apply_other" style="display:none;">
                <p style="font-size:20px;" >
                    <span style="color:#1E9FFF;"><i class="layui-icon layui-icon-log" style="font-size:40px;"></i><br/>该设备已被别人申请授权</span>
                </p>
            </div>
            <div class="layui-form-item bind_refuse" style="display:none;">
                <p style="font-size:20px;" >
                    <span style="color:#FF5722;"><i class="layui-icon layui-icon-close" style="font-size:40px;"></i><br/>申请驳回,原因:<br/>{$bindinfo.comment}</span>
                    <br/>
                    <div style="margin-top:20px" ><button class="showagain layui-btn layui-btn-radius layui-btn-fluid" style="background-color:#519DDA;" >再次提交</button> </div>
                    
                </p>
            </div>
        </div>

</body>
<!--script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script-->
<script>
     
    layui.use(['form','jquery'], function(){
        //var table = layui.table;  
        var form = layui.form;
        var $ = layui.$; //重点处
        
        
        var curr_uid={$bindinfo.current_uid };
        var bind_uid={$bindinfo.uid};
        var status={$bindinfo.status};
        var status_last = {$bindinfo.status_last};

        if( {$bind::BIND_UNBIND} == status  )
        {
            $('.container').show();    //未绑定, 显示绑定
        }
        else if( {$bind::BIND_APPLY} == status  )
        {
            if(curr_uid==bind_uid){
                $('.bind_apply').show();    //审核中, 是自己提交
            } else {
                $('.bind_apply_other').show();    //审核中, 不是自己提交
            }
        }
        else if( {$bind::BIND_REFUSE} == status )
        {
            if(curr_uid==bind_uid){
                $('.bind_refuse').show();    //驳回, 是自己提交, 
            } else {
                $('.container').show();    //驳回, 不是自己提交, 显示授权form
            }
        }

        $('.showagain').on('click',function(){
            if(status_last!=status){
                if(status_last=={$bind::BIND_APPLY}){
                    $('.bind_refuse').hide(); 
                    $('.bind_apply_other').show();    //审核中, 不是自己提交
                }else{
                    $('.container').show();
                    $('.bind_status').hide();
                }
            }else{
                $('.container').show();
                $('.bind_status').hide();
            }
            
        });

        
        // 表单提交事件
        form.on('submit(gogogo)', function (data) {
            //data.field.roleIds = formSelects.value('roleId', 'valStr');

            $.post('{:url('device/bind')}', data.field, function (res) {
                layer.closeAll('loading');
                if (res.code === 0) {
                    layer.msg(res.msg, {icon: 1}, function(){
                        $('.apply_ok').show();
                        $('.container').hide();
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
