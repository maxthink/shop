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
                        <button id="btnExpand" class="layui-btn icon-btn">全部展开</button>
                        <button id="btnFold" class="layui-btn icon-btn">全部折叠</button>
                    </div>
                </div>
            </div>

            <table class="layui-table" id="authTable" lay-filter="authTable"></table>
        </div>
    </div>
</div>

<!-- 表格操作列 -->
<script type="text/html" id="tableBar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="addnext">添加下级</a>
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<!-- 表单弹窗 -->
<script type="text/html" id="modelAuth">
    <form id="modelAuthForm" lay-filter="modelAuthForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        <div class="layui-form-item">
            <label class="layui-form-label">上级菜单</label>
            <div class="layui-input-block">
                <select name="pid">
                    <option value="">请选择上级菜单</option>
		    <?php if($menu){ foreach($menu as $val ): ?>
                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
		    <?php endforeach; } ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限名称</label>
            <div class="layui-input-block">
                <input name="name" placeholder="请输入权限名称" type="text" class="layui-input" maxlength="50"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限类型</label>
            <div class="layui-input-block">
                <input name="isMenu" type="radio" value="1" title="菜单" checked/>
                <input name="isMenu" type="radio" value="0" title="按钮"/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">菜单url</label>
            <div class="layui-input-block">
                <input name="router" placeholder="请输入菜单url" type="text" class="layui-input"/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">菜单图标</label>
            <div class="layui-input-block">
                <input name="menuIcon" placeholder="请输入菜单图标" type="text" class="layui-input"/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序号</label>
            <div class="layui-input-block">
                <input name="orderNumber" placeholder="请输入排序号" type="number" class="layui-input" min="0" max="1000"
                       lay-verType="tips" lay-verify="required|number" required/>
            </div>
        </div>
        <div class="layui-form-item text-right">
            <button class="layui-btn layui-btn-primary" type="button" tw-event="closePageDialog">取消</button>
            <button class="layui-btn" lay-filter="modelAuthSubmit" lay-submit>保存</button>
        </div>
    </form>
</script>

<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
<script>
    layui.use(['layer', 'form', 'table', 'admin', 'treetable'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var admin = layui.admin;
        var treetable = layui.treetable;

        // 渲染表格
        function renderTable() {
            treetable.render({
                treeColIndex: 1,
                treeSpid: 0,    //select 根节点id值
                treeIdName: 'id',
                treePidName: 'pid',
                elem: '#authTable',
                url: '{:url('menu/index')}',
                cellMinWidth: 100,
                cols: [[
                    //{type: 'numbers'},
                    {field: 'id', title: '编号'},
                    {field: 'name', title: '权限名称', minWidth: 160 },
                    {field: 'router', title: '菜单url', minWidth: 160 },
                    {field: 'orderNumber', title: '排序号', align: 'center' },
                    {
                        title: '类型', templet: function (d) {
                            var strs = [ '<span class="layui-badge layui-bg-gray">按钮</span>', '<span class="layui-badge-rim">菜单</span>'];
                            return strs[d.isMenu];
                        }, align: 'center'
                    },
                    {templet: '#tableBar', title: '操作', align: 'center', minWidth: 120}
                ]]
            });
        }

        renderTable();

        // 添加按钮点击事件
        $('#btnAdd').click(function () {
            showEditModel();
        });

        // 工具条点击事件
        table.on('tool(authTable)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            if (layEvent === 'edit') { // 修改
                showEditModel(data);
            } else if (layEvent === 'del') { // 删除
                doDel(data.id, data.name);
            } else if (layEvent === 'addnext') { // 添加下级
                addNext(data);
            }
        });

        // 删除
        function doDel(authorityId, authorityName) {
            top.layer.confirm('确定要删除“' + authorityName + '”吗？', {
                skin: 'layui-layer-admin'
            }, function (index) {
                top.layer.close(index);
                layer.load(2);
                $.get('{:url('menu/del')}', {
                    id: authorityId
                }, function (res) {
                    layer.closeAll('loading');
                    if (res.code == 0 ) {
                        layer.msg(res.msg, {icon: 1});
                        renderTable();
                    } else if(res.code == 2) {
                        top.location.href = location.href;
                    } else {
                        layer.msg(res.msg, {icon: 2});
                    }
                }, 'json');
            });
        }

        // 显示表单弹窗
        function showEditModel(objdata) {
            admin.open({
                type: 1,
                title: (objdata ? '修改' : '添加') + '权限',
                content: $('#modelAuth').html(),
                success: function (layero, dIndex) {
                    $(layero).children('.layui-layer-content').css('overflow', 'visible');
                    var url = objdata ? '{:url('menu/update')}' : '{:url('menu/add')}';
                    if (objdata && objdata.isMenu == '1') {
                        $('input[name="isMenu"][value="1"]').prop("checked", true);
                    }
                    if (objdata && objdata.isMenu == '0') {
                        $('input[name="isMenu"][value="0"]').prop("checked", true);
                    }
                    form.val('modelAuthForm', objdata);  // 回显数据
                    // 表单提交事件
                    form.on('submit(modelAuthSubmit)', function (data) {
                        if (data.field.pid == '') {
                            data.field.pid = '0';
                        }
                        layer.load(2);
                        $.post(url, data.field, function (res) {
                            layer.closeAll('loading');
                            if (res.code == 0 ) {
                                layer.close(dIndex);
                                layer.msg(res.msg, {icon: 1});
                                top.location.href = top.location.href;
                                //renderTable();
                            } else if(res.code == 2) {
                                top.location.href = location.href;
                            } else {
                                layer.msg(res.msg, {icon: 2});
                            }
                        }, 'json');
                        return false;
                    });
                }
            });
        }

        //添加下级菜单
        function addNext(objdata) {
            admin.open({
                type: 1,
                title: '添加下级',
                content: $('#modelAuth').html(),
                success: function (layero, dIndex) {
                    $(layero).children('.layui-layer-content').css('overflow', 'visible');
                    var url = '{:url('menu/add')}';
                    var upobj = {};
                    upobj.pid = objdata.id;
                    upobj.router = objdata.router;
                    form.val('modelAuthForm', upobj );  // 回显数据
                    // 表单提交事件
                    form.on('submit(modelAuthSubmit)', function (data) {
                        if (data.field.pid == '') {
                            data.field.pid = '0';
                        }
                        layer.load(2);
                        $.post(url, data.field, function (res) {
                            layer.closeAll('loading');
                            if (res.code == 0 ) {
                                layer.close(dIndex);
                                layer.msg(res.msg, {icon: 1});
                                renderTable();
                            }else if(res.code == 2) {
		                        top.location.href = location.href;
		                    } else {
                                layer.msg(res.msg, {icon: 2});
                            }
                        }, 'json');
                        return false;
                    });
                }
            });
        }



        // 搜索按钮点击事件
        $('#btnSearch').click(function () {
            $('#edtSearch').removeClass('layui-form-danger');
            var keyword = $('#edtSearch').val();
            var $tds = $('#authTable').next('.treeTable').find('.layui-table-body tbody tr td');
            $tds.css('background-color', 'transparent');
            if (!keyword) {
                layer.msg("请输入关键字", {icon: 5, anim: 6});
                $('#edtSearch').addClass('layui-form-danger');
                $('#edtSearch').focus();
                return;
            }
            var searchCount = 0;
            $tds.each(function () {
                if ($(this).text().indexOf(keyword) >= 0) {
                    $(this).css('background-color', '#FAE6A0');
                    if (searchCount == 0) {
                        $('body,html').stop(true);
                        $('body,html').animate({scrollTop: $(this).offset().top - 150}, 500);
                    }
                    searchCount++;
                }
            });
            if (searchCount == 0) {
                layer.msg("没有匹配结果", {icon: 5, anim: 6});
            } else {
                treetable.expandAll('#authTable');
            }
        });

        $('#btnExpand').click(function () {
            treetable.expandAll('#authTable');
        });

        $('#btnFold').click(function () {
            treetable.foldAll('#authTable');
        });

    });
</script>

</body>
</html>