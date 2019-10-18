{include file="public/header" /}
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header">标准折线图</div>
        <div class="layui-card-body">

        <div class="layui-carousel layadmin-carousel layadmin-dataview" data-anim="fade" lay-filter="LAY-index-normline">
            <div carousel-item id="LAY-index-normline">
                <div><i class="layui-icon layui-icon-loading1 layadmin-loading"></i></div>
            </div>
        </div>

      </div>
    </div>

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
                    </div>
                </div>
            </div>

            <table class="layui-table" id="userTable" lay-filter="userTable"></table>
        </div>
    </div>
</div>

<!-- 表格操作列 -->
<script type="text/html" id="tableBar">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="show">用户详情</a>
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="bill">消费记录</a>
</script>
<!-- 表格状态列 -->
<script type="text/html" id="tableState">
    <input type="checkbox" lay-filter="ckState" value="{{d.id}}" lay-skin="switch"
           lay-text="正常|锁定" {{d.islock==1?'checked':''}}/>
</script>

<!-- 表单弹窗 -->
<script type="text/html" id="modelObj">
    <form id="modelObjForm" lay-filter="modelObjForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        
        <div class="layui-form-item">
            <label class="layui-form-label">用户详情:</label>
            <div class="layui-input-block">
                <input name="nickname"  type="text" class="layui-input" maxlength="20"
                       lay-verType="tips"  disabled  />

            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">openid:</label>
            <div class="layui-input-block">
                <input name="thr_id" placeholder="" type="text" class="layui-input" maxlength="32"
                       lay-verType="tips" lay-verify="required" required/>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">类型:</label>
            <div class="layui-input-block">
                <select name="level" xm-select="level" lay-verType="tips" class="layui-select" lay-verify="required">
                    <option value="">用户级别</option>
                        <option value="0">普通用户</option>
                        <option value="1">店铺管理员</option>
                        <option value="2">代理</option>

                </select>
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
            url: '{:url('user/index')}',
            method: 'post', //如果无需自定义HTTP类型，可不加该参数
            page: {groups:15, limit:20},
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: '编号'},
                {field: 'nickname', title: '昵称'},
                {field: 'usertype', title: '用户类型',templet:function(d){
                        switch(d.usertype){
                            case 0: return '一般用户';
                            case 1: return '微信';
                            case 2: return '支付宝';
                            default : return '未知';
                        }
                }},
                {field: 'thr_id', title: '第三方id'},
                {field:'createtime', title: '创建时间', minWidth:180,
                    templet: function(d){
                      return layui.util.toDateString(d.createtime*1000);
                    }
                },
                {templet: '#tableState', title: '状态'},
                {field:'level', title: '用户类型', minWidth:100,
                    templet: function(d){
                       if(0===d.level){
                        return '普通用户';
                       }
                       if(1===d.level){
                        return '店铺管理员';
                       }
                       if(2===d.level){
                        return '代理';
                       }
                       if(3===d.level){
                        return '合伙人';
                       }

                    }
                },
                {align: 'center', toolbar: '#tableBar', title: '操作', minWidth: 200}
            ]]
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
            if (layEvent === 'bill') { // 查看用户消费记录
                showBillModel(data);
            } else if (layEvent==='show'){  //查看用户详情
                showUserDetail(data);
            }
        });


        // 个人显示账单
        function showBillModel(data) {
            top.layer.confirm('确定要删除“' + nickName + '”吗？', {
                skin: 'layui-layer-admin'
            }, function (i) {
                top.layer.close(i);
                layer.load(2);
                $.post('{:url('user/bill')}', {
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
        
        //显示用户详情
        function showUserDetail(M){
            admin.open({
                type: 1,
                title: '用户详情',
                content: $('#modelObj').html(),
                success: function (layero, dIndex) {
                    $(layero).children('.layui-layer-content').css('overflow', 'visible');
                    var url = '{:url('user/update')}' ;
                    // 回显数据
                    var roleIds = new Array();
                    if (M) {
                        //$('input[name="username"]').attr('readonly', 'readonly');
                        //todo 组装套餐数据
                        form.val('modelObjForm', M);
                        var roles = M.setmealId.split(',');
                        console.log( typeof(roles) );
                        for (var i = 0; i < roles.length; i++) {
                            roleIds.push(roles[i]);
                        }
                    } else {
                        form.render('radio');
                    }

                    rate.render({
                      elem: '#test1'
                      ,setText: function(value){
                        var arrs = {
                          '0': '普通用户'
                          ,'1': '店铺管理员'
                          ,'2': '代理商'
                        };
                        this.span.text(arrs[value] || ( value + "星"));
                      }
                    });

                    formSelects.render('level', {init: roleIds});   //重新加载用户类别/等级

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

        // 修改用户锁定状态
        form.on('switch(ckState)', function (obj) {
            layer.load(2);
            
            $.post('{:url('user/lock')}', {
                userId: obj.elem.value,
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