{include file="public/header" /}
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <select id="sltKey">
                            <option value="">请选择搜索条件</option>
                            <option value="username">账号</option>
                            <option value="nick_name">用户名</option>
                            <option value="sex">性别</option>
                        </select>
                    </div>
                    <div class="layui-inline">
                        <input id="edtSearch" class="layui-input" type="text" placeholder="输入关键字"/>
                    </div>
                    <div class="layui-inline">
                        <button id="btnSearch" class="layui-btn icon-btn"><i class="layui-icon">&#xe615;</i>搜索</button>
                        <button id="btnAdd" class="layui-btn icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>
                    </div>
                </div>
            </div>

            <table class="layui-table" id="userTable" lay-filter="userTable"></table>
        </div>
    </div>
</div>

<!-- 表格操作列 -->
<script type="text/html" id="tableBar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    <a class="layui-btn layui-btn-xs" lay-event="reset">重置密码</a>
</script>
<!-- 表格状态列 -->
<script type="text/html" id="tableState">
    <input type="checkbox" lay-filter="ckState" value="{{d.id}}" lay-skin="switch"
           lay-text="正常|锁定" {{d.islock==0?'checked':''}}/>
</script>
<!-- 表单弹窗 -->
<script type="text/html" id="modelUser">
    <form id="modelUserForm" lay-filter="modelUserForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        <div class="layui-form-item">
            <label class="layui-form-label">账号</label>
            <div class="layui-input-block">
                <input name="username" placeholder="请输入账号" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-block">
                <input name="realname" placeholder="请输入用户名" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-block">
                <input name="email" placeholder="请输入邮箱" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色</label>
            <div class="layui-input-block">
                <select name="roleId" xm-select="roleId" lay-verType="tips" lay-verify="required">
                    <?php foreach( $roleids as $role ): ?>
                        <option value="<?php echo $role['id']?>"><?php echo $role['name']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item text-right">
            <button class="layui-btn layui-btn-primary" type="button" tw-event="closePageDialog">取消</button>
            <button class="layui-btn" lay-filter="modelUserSubmit" lay-submit>保存</button>
        </div>
    </form>
</script>

<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
<script>
    layui.use(['layer', 'form', 'table', 'util', 'admin', 'formSelects'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var util = layui.util;
        var admin = layui.admin;
        var formSelects = layui.formSelects;

        // 渲染表格
        var insTb = table.render({
            elem: '#userTable',
            url: '{:url('admin/index')}',
            method: 'post', //如果无需自定义HTTP类型，可不加该参数
            page: true,
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: '编号'},
                {field: 'username', title: '账号'},
                {field: 'realname', title: '姓名'},
                {field: 'email', title: '邮箱'},
                {
                    templet: function (d) {
                        return util.toDateString(d.createtime*1000);
                    }, title: '创建时间', minWidth:180
                },
                {templet: '#tableState', title: '状态'},
                {align: 'center', toolbar: '#tableBar', title: '操作', minWidth: 200}
            ]]
        });

        // 添加
        $('#btnAdd').click(function () {
            showEditModel();
        });

        // 搜索
        $('#btnSearch').click(function () {
            var key = $('#sltKey').val();
            var value = $('#edtSearch').val();
            if (value && !key) {
                layer.msg('请选择搜索条件', {icon: 2});
            }
            insTb.reload({where: {searchKey: key, searchValue: value ,page: {page: 1 }}});
        });

        // 工具条点击事件
        table.on('tool(userTable)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            if (layEvent === 'edit') { // 修改
                showEditModel(data);
            } else if (layEvent === 'del') { // 删除
                doDel(data.id, data.realname);
            } else if (layEvent === 'reset') { // 重置密码
                resetPsw(data.id, data.username);
            }
        });

        // 显示表单弹窗
        function showEditModel(mUser) {
            admin.open({
                type: 1,
                title: (mUser ? '修改' : '添加') + '用户',
                content: $('#modelUser').html(),
                success: function (layero, dIndex) {
                    $(layero).children('.layui-layer-content').css('overflow', 'visible');
                    var url = mUser ? '{:url('admin/update')}' : '{:url('admin/add')}';
                    // 回显数据
                    var roleIds = new Array();
                    if (mUser) {
                        $('input[name="username"]').attr('readonly', 'readonly');
                        form.val('modelUserForm', mUser);
                        var roles = mUser.role_id.split(',');
                        console.log( typeof(roles) );
                        for (var i = 0; i < roles.length; i++) {
                            roleIds.push(roles[i]);
                        }
                    } else {
                        form.render('radio');
                    }
                    formSelects.render('roleId', {init: roleIds});
                    // 表单提交事件
                    form.on('submit(modelUserSubmit)', function (data) {
                        //data.field.roleIds = formSelects.value('roleId', 'valStr');
                        layer.load(2);
                        $.post(url, data.field, function (res) {
                            layer.closeAll('loading');
                            if (res.code == 0) {
                                layer.close(dIndex);
                                layer.msg(res.msg, {icon: 1});
                                insTb.reload();
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

        // 删除
        function doDel(userId, nickName) {
            top.layer.confirm('确定要删除“' + nickName + '”吗？', {
                skin: 'layui-layer-admin'
            }, function (i) {
                top.layer.close(i);
                layer.load(2);
                $.post('{:url('admin/del')}', {
                    userId: userId
                }, function (res) {
                    layer.closeAll('loading');
                    if (res.code == 200) {
                        layer.msg(res.msg, {icon: 1});
                        insTb.reload();
                    } else if(res.code == 2) {
                                top.location.href = location.href;
                    } else {
                        layer.msg(res.msg, {icon: 2});
                    }
                }, 'json');
            });
        }

        // 修改用户锁定状态
        form.on('switch(ckState)', function (obj) {
            layer.load(2);
            
            $.post('{:url('admin/lock')}', {
                userId: obj.elem.value,
                state: obj.elem.checked ? 0 : 1
            }, function (res) {
                layer.closeAll('loading');
                if ( res.code == 1 ) {
                    layer.msg(res.msg, {icon: 2});
                } else if(res.code == 2) {
                    top.location.href = location.href;
                } else if( res.code == 0 ) {
                    layer.msg(res.msg, {icon: 1});
                    $(obj.elem).prop('checked', obj.elem.checked);
                    form.render('checkbox');
                }
            }, 'json');
        });

        // 重置密码
        function resetPsw(userId, nickName) {
            top.layer.confirm('确定要重置“' + nickName + '”的登录密码吗？', {
                skin: 'layui-layer-admin'
            }, function (i) {
                top.layer.close(i);
                layer.load(2);
                $.post('{:url('admin/resetpasswd')}', {
                    userId: userId
                }, function (res) {
                    layer.closeAll('loading');
                    if (res.code == 0) {
                        layer.msg(res.msg, {icon: 1});
                    } else if(res.code == 2) {
                        top.location.href = location.href;
                    } else {
                        layer.msg(res.msg, {icon: 2});
                    }
                }, 'json');
            });
        }
        
        //设置权限
        function setAuth() {
            
        }

    });
</script>

</body>
</html>