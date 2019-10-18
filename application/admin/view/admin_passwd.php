<!DOCTYPE html>
<html class="bg-white">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>修改密码</title>
<link rel="stylesheet" href="/static/admin/libs/layui/css/layui.css"/>
<link rel="stylesheet" href="/static/admin/module/admin/admin.css?v=311"/>
</head>
<body>
<div class="layui-form model-form" id="form-psw">
    <div class="layui-form-item">
        <label class="layui-form-label">原始密码:</label>
        <div class="layui-input-block">
            <input type="password" name="oldPsw" placeholder="请输入原始密码" class="layui-input"
                   lay-verType="tips" lay-verify="required" required/>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">新密码:</label>
        <div class="layui-input-block">
            <input type="password" name="newPsw" placeholder="请输入新密码" class="layui-input"
                   lay-verType="tips" lay-verify="required|psw" required/>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">确认密码:</label>
        <div class="layui-input-block">
            <input type="password" name="rePsw" placeholder="请再次输入新密码" class="layui-input"
                   lay-verType="tips" lay-verify="required|repsw" required/>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block text-right">
            <button class="layui-btn layui-btn-primary" type="button" tw-event="closeDialog">取消</button>
            <button class="layui-btn" lay-filter="submit-psw" lay-submit >保存</button>
        </div>
    </div>
</div>

<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
<script>
    layui.use(['layer', 'form', 'admin'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var admin = layui.admin;

        admin.iframeAuto();  // 让当前iframe弹层高度适应

        // 监听提交
        form.on('submit(submit-psw)', function (data) {
            //layer.load(2);
            $.post(
                '{:url('admin/updatepasswd')}',
                data.field ,
                function(d){
                    layer.closeAll('loading');
                    if( 0 === d.code ){
                        layer.msg(d.msg, {icon: 1});
                        setTimeout( "parent.layer.close(parent.layer.getFrameIndex(window.name))", 2000 );
                    } else if(res.code == 2) {
                        top.location.href = location.href;
                    } else {
                        layer.msg(d.msg, {icon: 2});
                    }
                }, 'json');
        });

        // 添加表单验证方法
        form.verify({
            psw: [/^[\S]{5,12}$/, '密码必须5到12位，且不能出现空格'],
            repsw: function (t) {
                if (t !== $('#form-psw input[name=newPsw]').val()) {
                    return '两次密码输入不一致';
                }
            }
        });

    });
</script>
</body>
</html>