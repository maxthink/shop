{include file="public/header" /}
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    
                    <div class="layui-inline">
                        <div class="layui-input-inline mr0">
                            <input id="s_id" class="layui-input" type="text" placeholder="订单id"/>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <div class="layui-input-inline mr0">
                            <input id="s_uid" class="layui-input" type="text" placeholder="请输入管理员id"/>
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <div class="layui-input-inline mr0">
                            <input id="s_imei" class="layui-input" type="text" placeholder="设备编号,模糊查询"/>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <div class="layui-input-inline mr0">
                            <input id="s_did" class="layui-input" type="text" placeholder="设备id号, 精确查询"/>
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <select id="s_status" lay-verify="required" class="layui-select">
                            <option value="">全部支付类型</option>
                            <option value="{$Mbill::PAY_NO}" >未支付</option>
                            <option value="{$Mbill::PAY_OK}" >已支付</option>
                            <option value="{$Mbill::PAY_BACK}" >申请退款</option>
                            <option value="{$Mbill::PAY_BACKOK}" >已退款</option>
                        </select>
                    </div>
                    
                    <div class="layui-inline">
                        <button id="btnSearch" class="layui-btn icon-btn"><i class="layui-icon">&#xe615;</i>搜索</button>

                    </div>
                </div>
            </div>

            <table class="layui-table" id="objTable" lay-filter="objTable"></table>
        </div>
    </div>
</div>

<!-- 表格操作列 -->
<script type="text/html" id="tableBar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="reback">退款</a>
</script>
<!-- 表单弹窗 -->
<script type="text/html" id="modelObj">
    <form id="modelObjForm" lay-filter="modelObjForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        <div class="layui-form-item">
            <label class="layui-form-label">用户ID:</label>
            <div class="layui-input-block">
                <input name="user_id" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">退款金额:</label>
            <div class="layui-input-block">
                <input name="price" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required />
            </div>
        </div>
        <div class="layui-form-item text-right">
            <button class="layui-btn layui-btn-primary" type="button" tw-event="closePageDialog">取消</button>
            <button class="layui-btn" lay-filter="modelObjSubmit" lay-submit>退款</button>
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
            elem: '#objTable',
            url: '{:url('bill/index')}',
            method: 'post', //如果无需自定义HTTP类型，可不加该参数
            page: {groups:15, limit:10},
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: 'ID'},
                {field: 'realname', title: '管理员姓名'},
                {field: 'owner_uid', title: '管理员ID'},
                {field: 'dev_id', title: '设备ID'},
                {field: 'imei', title: '设备编码',minWidth:150},
                {templet:function(d){
                    return util.toDateString(d.createtime*1000);
                }, title: '订单时间',minWidth:160},
                {field: 'user_id', title: '客户id'},
                {templet:function(d){
                    if({$Mbill::PAY_NO}===d.status) return '未支付';
                    if({$Mbill::PAY_OK}===d.status) return '已支付';
                    if({$Mbill::PAY_BACK}===d.status) return '申请退款';
                    if({$Mbill::PAY_BACKOK}===d.status) return '已退款';
                    return '未知';
                },title: '订单状态'},
                {field: 'price', title: '金额',templet: function(d) {
                    return d.price / 100 ;
                }}
                
            ]]
        });
        
        // 搜索
        $('#btnSearch').click(function () {
            var search = new Object();
            search.bid = $('#s_id').val();
            search.imei = $('#s_imei').val();
            search.uid = $('#s_uid').val();
            search.did = $('#s_did').val();
            search.status = $('#s_status').val();
            if ( !search.bid && !search.imei && !search.uid && !search.did && !search.status ) {
                layer.msg('没有搜索条件', {icon: 2});
                layer.confirm('没有搜索条件,是否执行查询', {icon: 3, title:'提示'},function(index){
                    search = new Object();
                    insTb.reload({where: search ,page:{page:1,groups:15}});
                    layer.close(index);
                });
            }else {
                insTb.reload({where: search ,page:{page:1,groups:15}}); //重新从第 1 页开始
            }
        });

        // 工具条点击事件
        table.on('tool(objTable)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            if (layEvent === 'reback') { // 退款
                showRebackModel(data);
            }
        });

        // 显示表单弹窗
        function showRebackModel( M ) {

            M.price = M.price/100;

            admin.open({
                type: 1,
                title: '退款',
                content: $('#modelObj').html(),
                success: function (layero, dIndex) {
                    //$(layero).children('.layui-layer-content').css('overflow', 'visible');
                    var url =   '{:url('bill/back')}';
                    // 回显数据
                    if ( M ) {
                        form.val('modelObjForm', M );
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

         

    });
</script>

</body>
</html>