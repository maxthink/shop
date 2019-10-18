<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>管理中心</title>
    <link rel="stylesheet" href="/static/admin/libs/layui/css/layui.css"/>
    <!--link rel="stylesheet" href="/static/admin/module/admin/admin.css?v=311"/-->
    <link rel="stylesheet" href="/static/admin/module/formSelects/formSelects-v4.css"/>
</head>
<body>

<!-- 页面加载loading -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
 
<div id="index" class="mainBox" style="padding-top:18px;height:auto!important;height:550px;min-height:550px;">
    

    <!-- 加载动画，移除位置在common.js中 -->
    <div class="page-loading">
        <div class="ball-loader">
            <span></span><span></span><span></span><span></span>
        </div>
    </div>


    <!-- 正文开始 -->
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        访问量<span class="layui-badge layui-bg-blue pull-right">周</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">99,666</p>
                        <p>总计访问量<span class="pull-right">88万 <i class="layui-icon layui-icon-flag"></i></span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        下载<span class="layui-badge layui-bg-black pull-right">月</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">33,555</p>
                        <p>新下载<span class="pull-right">10% <i class="layui-icon">&#xe601</i></span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        Start<span class="layui-badge layui-bg-green pull-right">周</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">99,666</p>
                        <p>总Start数<span class="pull-right">88万 <i class="layui-icon layui-icon-rate"></i></span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        活跃用户<span class="layui-badge layui-bg-orange pull-right">月</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">66,666</p>
                        <p>最近一个月<span class="pull-right">15% <i class="layui-icon layui-icon-user"></i></span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row layui-col-space15">
            <div class="layui-col-lg8 layui-col-md7">
                <div class="layui-card">
                    <div class="layui-card-header">登陆日志</div>
                    <div class="layui-card-body">
                        <table class="layui-table layui-text">
                            <thead>
                                <tr>
                                    <th style="text-align: center">IP地址</th>
                                    <th style="text-align: center">操作时间</th>
                                </tr>

                            </thead>
                            <tbody>
                                {volist name="login_list" id="vo"}
                                <tr>
                                    <td align="center">{$vo.ip}</td>
                                    <td align="center">{$vo.time|date='Y-m-d H:i:s'}</td>
                                </tr>
                                {/volist}

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="layui-col-lg4 layui-col-md5">
                <div class="layui-card">
                    <div class="layui-card-header">网站基本信息</div>
                    <div class="layui-card-body">
                        <table class="layui-table layui-text">
                            <colgroup>
                                <col width="100">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td>单页面数：</td>
                                    <td><strong>{$page_count}</strong></td>
                                </tr>
                                <tr>
                                    <td>文章总数：</td>
                                    <td>
                                        <strong>{$article_count}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>产品总数：</td>
                                    <td><strong>{$product_count}</strong></td>
                                </tr>
                                <tr>
                                    <td>系统语言：</td>
                                    <td>
                                        <strong>zh_cn</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>版本：</td>
                                    <td>
                                        <strong>v1.0</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>移动端模板：</td>
                                    <td>
                                        <strong>{$mobile_theme}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>编码：</td>
                                    <td>
                                        <strong>UTF-8</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="layui-card">
                    <div class="layui-card-header">服务器信息</div>
                    <div class="layui-card-body">
                        <table class="layui-table layui-text">
                            <colgroup>
                                <col width="100">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td>PHP 版本：</td>
                                    <td>{$software.php}</td>
                                </tr>
                                <tr>
                                    <td>MySQL 版本：</td>
                                    <td>{$software.mysql}</td>
                                </tr>
                                <tr>
                                    <td>服务器操作系统</td>
                                    <td>{$software.os}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="layui-card">
                    <div class="layui-card-header">友情链接</div>
                    <div class="layui-card-body">
                        <div class="layui-carousel admin-carousel admin-news">
                            <div carousel-item>
                                <div>
                                    <a href="#" target="_blank" class="layui-bg-cyan">
                                        三方组件平台
                                    </a>
                                </div>
                                <div>
                                    <a href="#" target="_blank" class="layui-bg-blue">
                                        最佳案例
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>