{include file="public/header" /}
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    
                    <div class="layui-inline">
                        <div class="layui-input-inline mr0">
                            <input id="s_1" class="layui-input" type="text" placeholder="设备imei号,模糊匹配"/>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <div class="layui-input-inline mr0">
                            <input id="s_2" class="layui-input" type="text" placeholder="微信昵称,模糊匹配"/>
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <select id="s_3" lay-verify="required" class="layui-select">
                            <option value="">审核状态</option>
                            <option value="{$Mbind::BIND_APPLY}" >申请</option>
                            <option value="{$Mbind::BIND_PASS}" >通过</option>
                            <option value="{$Mbind::BIND_REFUSE}" >驳回</option>
                            <option value="{$Mbind::BIND_UNBIND}" >已解绑</option>
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
    <a class="layui-btn layui-btn-xs" lay-event="edit">审核</a>
</script>

<!-- 表格操作列 头像  -->
<script type="text/html" id="avatar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="avatar">头像</a>
</script>


<!-- 表单弹窗 -->
<script type="text/html" id="modelObj">
    <form id="modelObjForm" lay-filter="modelObjForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        <input name="wx_uid" type="hidden"/>
        <input name="device_id" type="hidden"/>
        <input name="p_uid" type="hidden"/>
        
        <div class="layui-form-item">
            <label class="layui-form-label">imei号:</label>
            <div class="layui-input-block">
                <input name="imei" placeholder="" type="number" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" readonly />
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">微信昵称</label>
            <div class="layui-input-block">
                <input name="nickname" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">实际姓名</label>
            <div class="layui-input-block">
                <input name="realname" type="text" class="layui-input" readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">审核:</label>
            <div class="layui-input-block">
                <select name="status" lay-filter="aihao">           
                    <option value="{$Mbind::BIND_PASS}" >通过</option>
                    <option value="{$Mbind::BIND_REFUSE}" >驳回</option>                    
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">备注:</label>
            <div class="layui-input-block">
                <textarea placeholder="审核失败需要告知理由, 牵连到用户实际收益, 请确认无误再通过! " class="layui-textarea" name="comment"></textarea>
            </div>
        </div>

        <div class="layui-form-item text-right">
            <button class="layui-btn layui-btn-primary" type="button" tw-event="closePageDialog">取消</button>
            <button class="layui-btn" lay-filter="modelObjSubmit" lay-submit>保存</button>
        </div>
    </form>
</script>


<!-- 表单弹窗 -->
<script type="text/html" id="unbind">
    <form id="unbindForm" lay-filter="unbindForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        <input name="device_id" type="hidden"/>
        <div class="layui-form-item">
            <label class="layui-form-label">解绑备注:</label>
            <div class="layui-input-block">
                <textarea placeholder="解绑后设备与绑定者再无关系，设备必须再次绑定某个拥有者才能正常使用。" class="layui-textarea" name="comment"></textarea>
            </div>
        </div>

        <div class="layui-form-item text-right">
            <button class="layui-btn layui-btn-primary" type="button" tw-event="closePageDialog">取消</button>
            <button class="layui-btn" lay-filter="unbindSubmit" lay-submit>确认解绑</button>
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
            url: "{:url('bind/index')}",
            method: 'post', //如果无需自定义HTTP类型，可不加该参数
            page: {groups:15, limit:10},
            cellMinWidth: 100,
            cols: [[
                { field: 'id', title: '申请编号', minWidth:40 },
                { field: 'imei', title: '设备编码',minWidth:152 },
                { field: 'wx_uid', title: '用户微信id' },
                { field: 'nickname', title: '微信昵称' },
                { field: 'realname', title: '实际姓名'  },
                { templet: '#avatar', title: '微信头像' },

                {field: 'p_uid', title: '推荐者id'  },
                {field: 'p_realname', title: '推荐者姓名'  },

                { templet:function(d){ return util.toDateString(d.time_apply*1000);}, title: '申请时间' },
                { templet:function(d){
                    if(d.time_verify==0){ return ''; }else{ return util.toDateString(d.time_verify*1000); }
                    }, title: '审核时间' },
                { templet:function(d){
                    if(d.time_unbind==0){ return ''; }else{ return util.toDateString(d.time_unbind*1000); }
                    }, title: '解绑时间' },
                                
                { templet: function (d) {
                       if(d.status=={$Mbind::BIND_APPLY} ) return '申请中';
                       if(d.status=={$Mbind::BIND_PASS} ) return '通过';
                       if(d.status=={$Mbind::BIND_REFUSE} ) return '驳回';
                       if(d.status=={$Mbind::BIND_UNBIND} ) return '已解绑';
                   }, title:'状态'},

                { templet: function(d){
                    if(d.status=={$Mbind::BIND_APPLY} ) return '<a class="layui-btn layui-btn-xs" lay-event="edit">审核</a>';
                    if(d.status=={$Mbind::BIND_PASS} ) return '<a class="layui-btn layui-btn-xs" lay-event="unbind">解绑</a>';
                    if(d.status=={$Mbind::BIND_REFUSE} ) return '<a class="layui-btn layui-btn-xs" lay-event="view">原因</a>';
                    if(d.status=={$Mbind::BIND_UNBIND} ) return '<a class="layui-btn layui-btn-xs" lay-event="unbinded">已解绑</a>';
                },title:'操作'}
            ]]
        });

 

        // 搜索
        $('#btnSearch').click(function () {
            var search = new Object();
            search.s1 = $('#s_1').val();
            search.s2 = $('#s_2').val();
            search.s3 = $('#s_3').val();
            if ( !search.s1 && !search.s2 && !search.s3 ) {
                layer.msg('没有搜索条件', {icon: 2});
                layer.confirm('没有搜索条件,是否执行查询', {icon: 3, title:'提示'},function(index){
                    search = new Object();
                    insTb.reload({where: search ,page: {page: 1 }});
                    layer.close(index);
                });
            } else {
                insTb.reload({where: search ,page: {page: 1 } });
            }
        });

        // 工具条点击事件
        table.on('tool(objTable)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            if (layEvent === 'edit') { // 修改
                showEditModel(data);
            }else if(layEvent === 'unbind'){    //解绑
                showUnbind(data);
            }else if(layEvent === 'view'){    //驳回, 查看驳回原因
                showView(data);
            }else if(layEvent === 'unbinded'){    // 查看解绑备注原因
                showunbinded(data);
            } else if (layEvent === 'avatar' ) {    //显示二维码
                showQrcode(data);
            } 
        });


        function showUnbind(data)
        {
            admin.open({
                type: 1,
                title: '解除用户和设备绑定关系? 请谨慎操作',
                content: $('#unbind').html(),
                success: function (layero, dIndex) {
                    $(layero).children('.layui-layer-content').css('overflow', 'visible');
                    form.val('unbindForm', data);
                    // 表单提交事件
                    form.on('submit(unbindSubmit)', function (data) {
                        //data.field.roleIds = formSelects.value('roleId', 'valStr');
                       
                        if(data.field.comment==''){
                            layer.msg('请输入解绑备注内容', {icon: 2});
                            return false;
                        }

                        layer.load(2);
                        $.post("{:url('bind/unbind')}", data.field, function (res) {
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
        function showView(data)
        {
            layer.open({
                title:'驳回备注:',
                content: data.comment
            });
        }

        function showunbinded(data)
        {
            layer.open({
                title:'解绑备注:',
                content: data.comment
            });
        }

        

        // 显示表单弹窗
        function showEditModel(M) {
            if(M.status=={$Mbind::BIND_PASS}){
                layer.msg('已审核通过', {icon: 1});
                return;
            }
            admin.open({
                type: 1,
                title: '审核',
                content: $('#modelObj').html(),
                success: function (layero, dIndex) {
                    $(layero).children('.layui-layer-content').css('overflow', 'visible');
                    var url = "{:url('bind/update')}";
                    // 回显数据
                    if (M) {
                        form.val('modelObjForm', M);
                    }

                    // 表单提交事件
                    form.on('submit(modelObjSubmit)', function (data) {
                        //data.field.roleIds = formSelects.value('roleId', 'valStr');
                        if( data.field.status!='' && data.field.status != M.status){
                            if(data.field.status=={$Mbind::BIND_REFUSE} ){
                                if(data.field.comment==''){
                                    layer.msg('请输入驳回理由', {icon: 2});
                                    return false;
                                }
                            }
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
                        }else{
                            layer.msg('审核状态未改变, 系统无需提交修改.', {icon: 2});
                        }
                            
                        return false;
                        
                    });
                }
            });
        }
        
        //显示二维码
        function showQrcode(data){
            //todo 打开显示一个图层
            layer.open({
                title: false,
                closeBtn :false,
                area: ['110px', '230px'],
                content: '<div width="132" height="132" ><img src='+data.avatar+'></div>'
            }); 
            
        }

    });
</script>

</body>
</html>