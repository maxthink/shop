<?php /*a:2:{s:59:"/data/www/tour/application/admin/view/dashboard_welcome.php";i:1562206288;s:55:"/data/www/tour/application/admin/view/public_header.php";i:1564456502;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>管理中心</title>
    <link rel="stylesheet" href="/static/admin/libs/layui/css/layui.css"/>
    <link rel="stylesheet" href="/static/admin/module/admin/admin.css?v=311"/>
    <link rel="stylesheet" href="/static/admin/module/formSelects/formSelects-v4.css"/>
</head>
<body>

<!-- 页面加载loading -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>


<div class="layui-card-body" style="text-align: center;">
    <h2 style="margin-top: 170px;margin-bottom: 20px;font-size: 28px;color: #91ADDC;">欢迎使用ThinkWeb管理系统 !</h2>
    <img src="/static/admin/images/welcome.png" style="max-width: 100%;">
</div>

<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>

<script>
    layui.use(['layer'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
    });
</script>
</body>

</html>