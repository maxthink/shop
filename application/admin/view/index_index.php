<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="/static/admin/images/favicon.ico" rel="icon">
    <title>创世共想后台管理</title>
    <link rel="stylesheet" href="/static/admin/libs/layui/css/layui.css"/>
    <link rel="stylesheet" href="/static/admin/module/admin/admin.css?v=301"/>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <!-- 头部 -->
    <div class="layui-header">
        <div class="layui-logo">
            <img src="/static/admin/images/logo.png"/>
            <cite>&nbsp;新疆旅行社&emsp;</cite>
        </div>
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item" lay-unselect>
                <a tw-event="flexible" title="侧边伸缩"><i class="layui-icon layui-icon-shrink-right"></i></a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a tw-event="refresh" title="刷新"><i class="layui-icon layui-icon-refresh-3"></i></a>
            </li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item" lay-unselect>
                <a tw-event="message" title="消息"><i class="layui-icon layui-icon-notice"></i></a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a tw-event="note" title="便签"><i class="layui-icon layui-icon-note"></i></a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect="">
                <a tw-event="theme" title="主题"><i class="layui-icon layui-icon-theme"></i></a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a tw-event="fullScreen" title="全屏"><i class="layui-icon layui-icon-screen-full"></i></a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a>
                    <img src="/static/admin/images/head.png" class="layui-nav-img">
                    <cite><?php echo $userinfo['name']; ?></cite>
                </a>
                <dl class="layui-nav-child">
                    <dd lay-unselect>
                        <a tw-href="{:url('personal/index')}">个人中心</a>
                    </dd>
                    <dd lay-unselect>
                        <a tw-event="psw" data-url="{:url('admin/updatepasswd')}" >修改密码</a>
                    </dd>
                    <hr>
                    <dd lay-unselect>
                        <a tw-event="logout" data-url="{:url('login/logout')}">退出</a>
                    </dd>
                </dl>
            </li>
            
        </ul>
    </div>

    <!-- 侧边栏 -->
    <div class="layui-side">
        <div class="layui-side-scroll">
            <ul class="layui-nav layui-nav-tree" lay-filter="admin-side-nav" lay-accordion="true"
                style="margin: 15px 0;">
                
                <?php if($menu){ foreach($menu as $cols ): ?>
                <li class="layui-nav-item">
                    <a><i class="<?php echo $cols['menuIcon'] ?? ''; ?>"></i>&emsp;<cite><?php echo $cols['name'] ?? ''; ?></cite></a>
                    <?php if(isset($cols['sub'])): ?>
                    <dl class="layui-nav-child">
                        <?php foreach ($cols['sub'] as $subMenu ): ?>
                        <dd><a lay-href="<?php echo url($subMenu['router']) ?>"><?php echo $subMenu['name'] ?></a></dd>
                        <?php endforeach;  ?>
                    </dl>
                    <?php endif; ?>
                </li>
                <?php endforeach; } ?>
            </ul>
        </div>
    </div>

    <!-- 主体部分 -->
    <div class="layui-body"></div>
    <!-- 底部 -->
    <div class="layui-footer">
        copyright © 2019 thinkweb all rights reserved. <span class="pull-right">Version 1.0.0</span>
    </div>
</div>

<!-- 加载动画，移除位置在common.js中 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>

<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=31"></script>
<script>
    layui.use(['index','layer'], function () {
        var $ = layui.jquery;
        var index = layui.index,
        layer = layui.layer;

        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){
            layer.alert('<p style="text-align:center;">微信中部分功能无法使用，请在其他浏览器中打开</p>', {closeBtn:false,btn:false,title:false})
        }
        // 默认加载主页
        index.loadHome({
            menuPath: '{:url('dashboard/welcome')}',
            menuName: '<i class="layui-icon layui-icon-home"></i>'
        });
    });
</script>
</body>
</html>
