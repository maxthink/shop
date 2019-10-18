{include file="public/header" /}

<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label w-auto">搜索：</label>
                        <div class="layui-input-inline mr0">
                            <input id="edtSearch" class="layui-input" type="text" placeholder="输入关键字"/>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button id="btnSearch" class="layui-btn icon-btn"><i class="layui-icon">&#xe615;</i>搜索</button>
                        <button id="btnAdd" class="layui-btn icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>
                    </div>
                </div>
            </div>

            <table class="layui-table" id="roleTable" lay-filter="roleTable"></table>
        </div>
    </div>
</div>

<!-- 表格操作列 -->
<script type="text/html" id="tableBar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    <a class="layui-btn layui-btn-xs" lay-event="auth">权限分配</a>
</script>
<!-- 表单弹窗 -->
<script type="text/html" id="modelRole">
    <form id="modelRoleForm" lay-filter="modelRoleForm" class="layui-form model-form">
        <div class="layui-form-item">
            <label class="layui-form-label">ID</label>
            <div class="layui-input-block">
                <input name="id" placeholder="ID" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色名</label>
            <div class="layui-input-block">
                <input name="name" placeholder="请输入角色名" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="remark" placeholder="请输入内容" class="layui-textarea" maxlength="200"></textarea>
            </div>
        </div>
        <div class="layui-form-item text-right">
            <button class="layui-btn layui-btn-primary" type="button" tw-event="closePageDialog">取消</button>
            <button class="layui-btn" lay-filter="modelRoleSubmit" lay-submit>保存</button>
        </div>
    </form>
</script>

<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/static/admin/libs/zTree/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
<link rel="stylesheet" href="/static/admin/libs/zTree/css/zTreeStyle/zTreeStyle.css">
<script>
    layui.use(['layer', 'form', 'table', 'util', 'admin'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var util = layui.util;
        var admin = layui.admin;

        // 渲染表格
        var insTb = table.render({
            elem: '#roleTable',
            url: '{:url('role/index')}',
            method: 'post',
            page: false,
            cellMinWidth: 100,
            cols: [[
                {type: 'numbers'},
                {field: 'id', title: '权限编号' },
                {field: 'name', title: '角色名', align: 'center'},
                {field: 'remark', title: '备注', minWidth:260 },
                {
                    templet: function (d) {
                        return util.toDateString(d.createTime*1000);
                    }, title: '创建时间'
                },
                {align: 'center', toolbar: '#tableBar', title: '操作', minWidth: 200}
            ]]
        });

        // 添加
        $('#btnAdd').click(function () {
            showEditModel();
        });

        // 搜索
        $('#btnSearch').click(function () {
            insTb.reload({where: {keyword: $('#edtSearch').val()} ,page: {page: 1 }});
        });

        // 工具条点击事件
        table.on('tool(roleTable)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            if (layEvent === 'edit') { // 修改
                showEditModel(data);
            } else if (layEvent === 'del') { // 删除
                doDel(obj);
            } else if (layEvent === 'auth') {  // 权限管理
                showPermModel(data.id);
            }
        });

        // 删除
        function doDel(obj) {
            top.layer.confirm('确定要删除“' + obj.data.name + '”角色吗？', {
                skin: 'layui-layer-admin'
            }, function (i) {
                top.layer.close(i);
                layer.load(2);
                $.post('{:url('role/del')}', {
                    roleId: obj.data.id
                }, function (res) {
                    layer.closeAll('loading');
                    if (res.code == 0) {
                        layer.msg(res.msg, {icon: 1});
                        obj.del();
                    } else if(res.code == 2) {
                        top.location.href = location.href;
                    } else {
                        layer.msg(res.msg, {icon: 2});
                    }
                }, 'json');
            });
        }

        // 显示编辑弹窗
        function showEditModel(mRole) {
            admin.open({
                type: 1,
                title: (mRole ? '修改' : '添加') + '角色',
                content: $('#modelRole').html(),
                success: function (layero, dIndex) {
                    var url = mRole ? '{:url('role/update')}' : '{:url('role/add')}';
                    form.val('modelRoleForm', mRole);  // 回显数据
                    // 表单提交事件
                    form.on('submit(modelRoleSubmit)', function (data) {
                        layer.load(2);
                        $.post(url, data.field, function (res) {
                            layer.closeAll('loading');
                            if (res.code == 0 ) {
                                layer.close(dIndex);
                                layer.msg(res.msg, {icon: 1});
                                insTb.reload();
                            } else {
                                layer.msg(res.msg, {icon: 2});
                            }
                        }, 'json');
                        return false;
                    });
                }
            });
        }

        // 权限管理
        function showPermModel(roleId) {
            admin.open({
                title: '角色权限分配',
                btn: ['保存', '取消'],
                content: '<ul id="treeAuth" class="ztree"></ul>',
                success: function (layero, index) {
                    $(layero).children('.layui-layer-content').css({'max-height': '300px', 'overflow': 'auto'});
                    layer.load(2);
                    var setting = {check: {enable: true}, data: {simpleData: { enable: true, idKey: "id", pIdKey: "pid", rootPId: null } } };
                    $.get(
                        '{:url('role/authtree')}', 
                        { roleId: roleId}, 
                        function (res) {
                            $.fn.zTree.init($('#treeAuth'), setting, res.data);
                            layer.closeAll('loading');
                        },
                        'json'
                    );
                },
                yes: function (index) {
                    layer.load(2);
                    var treeObj = $.fn.zTree.getZTreeObj('treeAuth');
                    var nodes = treeObj.getCheckedNodes(true);
                    var ids = new Array();
                    for (var i = 0; i < nodes.length; i++) {
                        ids[i] = nodes[i].id;
                    }
                    $.post('{:url('role/authtree')}', {
                        roleId: roleId,
                        authIds: JSON.stringify(ids)
                    }, function (res) {
                        layer.closeAll('loading');
                        if ( 0 == res.code) {
                            layer.msg(res.msg, {icon: 1});
                            layer.close(index);
                            top.location.href = top.location.href;
                        } else {
                            layer.msg(res.msg, {icon: 2});
                        }
                    }, 'json');
                }
            });
        }

    });
</script>

</body>
</html>