{include file="public/header" /}
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <select id="sltKey">
                            <option value="">根据省份搜索店铺</option>
                            {foreach name="province" item="vo"}
                            <option value="{$vo}">{$vo}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="layui-inline">
                        <input id="edtSearch" class="layui-input" type="text" placeholder="根据IMEI号搜索"/>
                    </div>
                    <div class="layui-inline">
                        <input id="Search" class="layui-input" type="text" placeholder="根据负责人搜索"/>
                    </div>
                    <div class="layui-inline">
                        <input id="phoneSearch" class="layui-input" type="text" placeholder="根据联系方式搜索"/>
                    </div>
                    <div class="layui-inline">
                        <button id="btnSearch" class="layui-btn icon-btn"><i class="layui-icon">&#xe615;</i>搜索</button>
                        <a href="{:url('store/add')}" style="margin-right: 10px">
                            <button class="layui-btn">添加</button>
                        </a>
                    </div>
                </div>
            </div>
            <table class="layui-table" id="store_listTable" lay-filter="store_listTable"></table>
        </div>
    </div>
</div>
<!-- 表格操作列 -->
<script type="text/html" id="tableBar">
    <a class="layui-btn  layui-btn-xs layui-btn-normal" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    <a class="layui-btn layui-btn-xs" lay-event="dta">查看详情</a>
</script>

<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
<script>
    layui.use(['layer', 'form', 'table','util'], function () {
        var $ = layui.jquery,
            layer = layui.layer,
            form = layui.form,
            table = layui.table,
            util = layui.util;
        
        var insTb = table.render({
            elem: '#store_listTable',
            url: '{:url('store/index')}',
            method: 'post', //如果无需自定义HTTP类型，可不加该参数
            page: {groups:15, limit:20},
            limit:10,
            limits:[10,20,30,50],  //数据分页条
            cellMinWidth: 100,
            cols: [[
                {field:'store_name', width:'12.5%', title: '店铺名称'}
                ,{field:'store_principal', width:'12.5%', title: '负责人'}
                ,{field:'store_phone', width:'12.5%', title: '联系电话'}
                ,{field:'store_address', width:'22.5%', title: '店铺地址'}
                ,{field:'province_name', width:'12.5%', title: '所属地区'}
                , {
                    templet: function (d){
                        return util.toDateString(d.ctime*1000);
                    }, title: '添加时间', minWidth:180
                },
                {align: 'center', toolbar: '#tableBar', title: '操作', minWidth: 200}
            ]]
        });

        table.on('tool(store_listTable)', function(obj){
            var data = obj.data;
            var layEvent = obj.event;
            if(layEvent === 'edit'){
                location.href="{:url('store/edit')}?id="+data.id;
            } else if(layEvent === 'del'){
                doDel(data.id);
            } else if (layEvent === 'dta'){
                location.href="{:url('store/detail')}?id="+data.id;
            }
        });

        // 删除
        function doDel(ID) {
            top.layer.confirm('确定要删除该店铺信息吗？', {
                skin: 'layui-layer-admin'
            }, function (i) {
                top.layer.close(i);
                layer.load(2);
                $.post("{:url('store/delete')}", {
                    id: ID
                }, function (res) {
                    layer.closeAll('loading');
                    if (res.status == 1) {
                        layer.msg(res.msg, {icon: 1});
                        var key = $('#sltKey').val();
                        var value = $('#edtSearch').val();
                        var phone = $('#phoneSearch').val();
                        var search  = $('#Search').val();
                        insTb.reload({where: {province_name: key,imei:value,phone:phone,search:search}});
                    } else {
                        layer.msg(res.msg, {icon: 2});
                    }
                }, 'json');
            });
        }

        $('#btnSearch').click(function () {
            var key = $('#sltKey').val();
            var value = $('#edtSearch').val();
            var phone = $('#phoneSearch').val();
            var search  = $('#Search').val();
            if (!key && !value && !phone && !search) {
                layer.msg('请选择搜索条件', {icon: 2});
            }
            insTb.reload({where: {province_name: key,imei:value,phone:phone,search:search},page: {page: 1 } });
        });
    })
</script>
</body>
</html>