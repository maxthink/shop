{include file="public/header" /}
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    
                    <div class="layui-inline">
                        <button id="btnAdd" class="layui-btn icon-btn"><i class="layui-icon">&#xe654;</i>添加新设备</button>
                    </div>
                </div>
            </div>

            <table class="layui-table" id="userTable" lay-filter="userTable"></table>
        </div>
    </div>
</div>

<!-- 表格操作列 -->
<script type="text/html" id="tableBar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
</script>

<!-- 表单弹窗 -->
<script type="text/html" id="modelObj">
    <form id="modelObjForm" lay-filter="modelObjForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        <div class="layui-form-item">
            <label class="layui-form-label">设备名称:</label>
            <div class="layui-input-block">
                <input name="name" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">厂家名称:</label>
            <div class="layui-input-block">
                <input name="factory" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系方式:</label>
            <div class="layui-input-block">
                <textarea name="connect" placeholder="" class="layui-textarea" rows="5"
                       lay-verType="tips" lay-verify="required" required/></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">设备介绍:</label>
            <div class="layui-input-block">
                <textarea name="intro" placeholder="" class="layui-textarea" rows="5"
                       lay-verType="tips" lay-verify="required" required/></textarea>
            </div>
        </div>

        <div class="layui-form-item text-right">
            <button class="layui-btn layui-btn-primary" type="button" tw-event="closePageDialog">取消</button>
            <button class="layui-btn" lay-filter="modelObjSubmit" lay-submit>保存</button>
        </div>
    </form>
</script>

<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
<script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script>
<script>
    /*
     * qrcode 来源: https://larsjung.de/jquery-qrcode/latest/demo/
     */
    
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
            url: '{:url('devtype/index')}',
            method: 'post', //如果无需自定义HTTP类型，可不加该参数
            page: true,
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: '编号'},
                {field: 'name', title: '设备名称'},
                {align: 'center', toolbar: '#tableBar', title: '操作', minWidth: 100}
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
            insTb.reload({where: {searchKey: key, searchValue: value}});
        });

        // 工具条点击事件
        table.on('tool(userTable)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            if (layEvent === 'edit') { // 修改
                showEditModel(data);
            } else if (layEvent === 'del') { // 删除
                doDel(data.id, data.nickName);
            } else if (layEvent === 'reset') { // 重置密码
                resetPsw(data.id, data.username);
            } else if (layEvent === 'qrcode' ) {    //显示二维码
                showQrcode(data.id);
            }
        });

        // 显示表单弹窗
        function showEditModel(M) {
            admin.open({
                type: 1,
                title: (M ? '修改' : '添加') + '设备类型',
                content: $('#modelObj').html(),
                success: function (layero, dIndex) {
                    $(layero).children('.layui-layer-content').css('overflow', 'visible');
                    var url = M ? '{:url('devtype/update')}' : '{:url('devtype/add')}';
                    // 回显数据
                    if (M) {
                        form.val('modelObjForm', M);
                    } else {
                        form.render('radio');
                    }

                    // 表单提交事件
                    form.on('submit(modelObjSubmit)', function (data) {
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
        
        // 修改锁定状态
        form.on('switch(ckState)', function (obj) {
            layer.load(2);
            
            $.post('{:url('devtype/lock')}', {
                Id: obj.elem.value,
                state: obj.elem.checked ? 0 : 1
            }, function (res) {
                layer.closeAll('loading');
                if ( res.code == 1 ) {
                    layer.msg(res.msg, {icon: 2});
                } else if( res.code == 0 ) {
                    layer.msg(res.msg, {icon: 1});
                    $(obj.elem).prop('checked', obj.elem.checked);
                    form.render('checkbox');
                }else if(res.code == 2) {
                    top.location.href = location.href;
                }
            }, 'json');
        });

         

    });
</script>

</body>
</html>