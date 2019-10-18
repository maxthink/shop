{include file="public/header" /}
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <!--div class="layui-inline">
                        <select id="sltKey">
                            <option value="">请选择搜索条件</option>
                            <option value="username">套餐名</option>
                            <option value="nick_name">套餐类型</option>
                            <option value="sex">次数</option>
                            <option value="sex">时长</option>
                            <option value="sex">价格</option>
                        </select>
                    </div>
                    <div class="layui-inline">
                        <input id="edtSearch" class="layui-input" type="text" placeholder="输入关键字"/>
                    </div-->
                    <div class="layui-inline">
                        <!--button id="btnSearch" class="layui-btn icon-btn"><i class="layui-icon">&#xe615;</i>搜索</button-->
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
    <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
</script>
 
<!-- 表格状态列 -->
<script type="text/html" id="tableState">
    <input type="checkbox" lay-filter="ckState" value="{{d.id}}" lay-skin="switch"
           lay-text="正常|锁定" {{d.lock==0 ?'checked':''}}/>
</script>
<!-- 表单弹窗 -->
<script type="text/html" id="modelUser">
    <form id="modelUserForm" lay-filter="modelUserForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        <div class="layui-form-item">
            <label class="layui-form-label">套餐名:</label>
            <div class="layui-input-block">
                <input name="name" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">套餐类型:</label>
            <div class="layui-input-block">
                <input type="radio" name="type" value="1" title="倒计时" checked="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">次数:</label>
            <div class="layui-input-block">
                <input type="number" min="1" name="sum" placeholder="" type="text" class="layui-input" maxlength="2"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">时长:</label>
            <div class="layui-input-block">
                <input type="number" name="long" placeholder="分钟" type="text" class="layui-input" maxlength="2"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">价格:</label>
            <div class="layui-input-block">
                <input type="number" name="price" placeholder="人民币,最小到分" type="text" class="layui-input" maxlength="5"
                       lay-verType="tips" lay-verify="required" required/>
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
            url: '{:url('setmeal/index')}',
            method: 'post', //如果无需自定义HTTP类型，可不加该参数
            page: true,
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: '编号'},
                {field: 'name', title: '名称'},
                {field: 'type', title: '套餐类型',templet:function(d){
                        switch(d.type){
                            case 1: return '倒计时';
                            default: return '未知类型';
                        }
                }},
                {field: 'sum', title: '次数'},
                {field: 'long', title: '时长',templet:function(d){
                        return d.long/60 + '分钟';
                },align:'right'},
                {field: 'price', title: '价格',templet:function(d){
                        return d.price/100;
                },align:'right'},                
                {templet: '#tableState', title: '状态'},
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
            insTb.reload({where: {searchKey: key, searchValue: value} ,page: {page: 1 }});
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
                title: (M ? '修改' : '添加') + '套餐',
                content: $('#modelUser').html(),
                success: function (layero, dIndex) {
                    $(layero).children('.layui-layer-content').css('overflow', 'visible');
                    var url = M ? '{:url('setmeal/update')}' : '{:url('setmeal/add')}';
                    // 回显数据
                    if (M) {
                        //$('input[name="username"]').attr('readonly', 'readonly');
                        M.long = M.long/60;
                        M.price = M.price/100;
                        form.val('modelUserForm', M);
                    } else {
                        form.render('radio');
                    }

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
        
        
        // 修改数据锁定状态
        form.on('switch(ckState)', function (obj) {
            layer.load(2);
            
            $.post('{:url('setmeal/lock')}', {
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
                }
            }, 'json');
        });

         

    });
</script>

</body>
</html>