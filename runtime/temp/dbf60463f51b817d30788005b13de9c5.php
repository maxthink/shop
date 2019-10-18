<?php /*a:2:{s:51:"/data/www/tour/application/admin/view/log_index.php";i:1557741452;s:55:"/data/www/tour/application/admin/view/public_header.php";i:1564456502;}*/ ?>
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

<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label w-auto">账号ID：</label>
                        <div class="layui-input-inline mr0">
                            <input id="edtAccount" class="layui-input" type="text" placeholder="请输入账号ID"/>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label w-auto">日期：</label>
                        <div class="layui-input-inline mr0">
                            <input id="edtDate" class="layui-input date-icon" type="text" placeholder="请选择日期范围"/>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button id="btnSearch" class="layui-btn icon-btn"><i class="layui-icon">&#xe615;</i>搜索</button>
                        <button id="btnExp" class="layui-btn icon-btn"><i class="layui-icon">&#xe67d;</i>导出</button>
                    </div>
                </div>
            </div>

            <table class="layui-table" id="logTable" lay-filter="logTable"></table>
        </div>
    </div>
</div>

<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
<script>
    layui.use(['layer', 'form', 'table', 'util', 'laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var util = layui.util;
        var laydate = layui.laydate;

        // 渲染表格
        var insTb = table.render({
            elem: '#logTable',
            url: '<?php echo url('log/index'); ?>',
            method: 'post',
            page: true,
            cellMinWidth: 100,
            cols: [[
                {type: 'checkbox'},
                {field: 'id', title: '日志编号'},
                {field: 'userid', title: '用户id'},
                {field: 'username', title: '用户名'},
                {field: 'ip', title: 'IP'},
                {field: 'device', title: '设备'},
                {field: 'osName', title: '设备类型'},
                {field: 'browserType', title: '浏览器'},
                {
                    templet: function (d) {
                        return util.toDateString(d.time*1000);
                    }, title: '操作时间'
                }
            ]]
        });

        // 时间范围
        laydate.render({
            elem: '#edtDate',
            type: 'date',
            range: true,
            theme: 'molv'
        });

        // 搜索按钮点击事件
        $('#btnSearch').click(function () {
            var searchDate = $('#edtDate').val().split(' - ');
            var searchAccount = $('#edtAccount').val();
            insTb.reload({
                where: {
                    startDate: searchDate[0],
                    endDate: searchDate[1],
                    account: searchAccount
                }
            });
        });

        // 导出excel
        $('#btnExp').click(function () {
            var checkRows = table.checkStatus('logTable');
            if (checkRows.data.length == 0) {
                layer.msg('请选择要导出的数据', {icon: 2});
            } else {
                table.exportFile(ins1.config.id, checkRows.data, 'xls');
            }
        });

    });
</script>

</body>
</html>