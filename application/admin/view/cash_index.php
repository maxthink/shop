{include file="public/header" /}
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    
                    <div class="layui-inline">
                        <div class="layui-input-inline mr0">
                            <input id="s_1" class="layui-input" type="text" placeholder="请输入管理员id"/>
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <select id="s_2" lay-verify="required" class="layui-select">
                            <option value="">审核状态</option>
                            <option value="{$Mcash::CASH_APPLY}" >等待客服审核</option>
                            <option value="{$Mcash::CASH_FINANCE}" >等待财务审核</option>
                            <option value="{$Mcash::CASH_PASS}" >审核通过</option>
                            <option value="{$Mcash::CASH_REFUSE}" >驳回</option>
                            <option value="{$Mcash::CASH_PAYED}" >已打款</option>
                        </select>
                    </div>
                    
                    <div class="layui-inline">
                        <button id="btnSearch" class="layui-btn icon-btn"><i class="layui-icon">&#xe615;</i>搜索</button>
                        <button id="btnExport" class="layui-btn icon-btn"><i class="layui-icon">&#xe62f;</i>导出</button>
                    </div>
                </div>
            </div>
            <table class="layui-table" id="objTable" lay-filter="objTable"></table>
        </div>
    </div>
</div>


<!-- 客服审核 表单弹窗 -->
<script type="text/html" id="modelObj">
    <form id="modelObjForm" lay-filter="modelObjForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        <div class="layui-form-item">
            <label class="layui-form-label">用户姓名:</label>
            <div class="layui-input-block">
                <input name="realname" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" readonly />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">提现金额:</label>
            <div class="layui-input-block">
                <input name="money" placeholder="" type="text" class="layui-input" maxlength="6"
                       lay-verType="tips"  readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">收款账户名:</label>
            <div class="layui-input-block">
                <input name="account_name" placeholder="" type="text" class="layui-input" maxlength="30"
                       lay-verType="tips"  readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">收款银行:</label>
            <div class="layui-input-block">
                <input name="account_bank" placeholder="" type="text" class="layui-input" maxlength="30"
                       lay-verType="tips"  readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">收款账号:</label>
            <div class="layui-input-block">
                <input name="account_number" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">纳税人税号:</label>
            <div class="layui-input-block">
                <input name="account_tax" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">审核:</label>
            <div class="layui-input-block">
                <select name="status" lay-filter="aihao">           
                    <option value="{$Mcash::CASH_FINANCE}" >通过</option>
                    <option value="{$Mcash::CASH_REFUSE}" >驳回</option>                    
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

<!-- 财务审核 表单弹窗 -->
<script type="text/html" id="financeModel">
    <form id="financeForm" lay-filter="financeForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        <div class="layui-form-item">
            <label class="layui-form-label">用户姓名:</label>
            <div class="layui-input-block">
                <input name="realname" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" readonly />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">提现金额:</label>
            <div class="layui-input-block">
                <input name="money" placeholder="" type="text" class="layui-input" maxlength="6"
                       lay-verType="tips"  readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">收款账户名:</label>
            <div class="layui-input-block">
                <input name="account_name" placeholder="" type="text" class="layui-input" maxlength="30"
                       lay-verType="tips"  readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">收款银行:</label>
            <div class="layui-input-block">
                <input name="account_bank" placeholder="" type="text" class="layui-input" maxlength="30"
                       lay-verType="tips"  readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">收款账号:</label>
            <div class="layui-input-block">
                <input name="account_number" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">纳税人税号:</label>
            <div class="layui-input-block">
                <input name="account_tax" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" readonly />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">审核:</label>
            <div class="layui-input-block">
                <select name="status" lay-filter="aihao">           
                    <option value="{$Mcash::CASH_PASS}" >通过</option>
                    <option value="{$Mcash::CASH_REFUSE}" >驳回</option>                    
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
            <button class="layui-btn" lay-filter="financeSubmit" lay-submit>保存</button>
        </div>
    </form>
</script>

<!-- 财务打款单号 表单弹窗 -->
<script type="text/html" id="payModel">
    <form id="payForm" lay-filter="payForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        
        <div class="layui-form-item">
            <label class="layui-form-label">打款单号:</label>
            <div class="layui-input-block">
                <input name="paybill" placeholder="" type="text" class="layui-input" maxlength="20"
                       lay-verType="tips" lay-verify="required" required />
            </div>
        </div>

        <div class="layui-form-item text-right">
            <button class="layui-btn layui-btn-primary" type="button" tw-event="closePageDialog">取消</button>
            <button class="layui-btn" lay-filter="paySubmit" lay-submit>保存</button>
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
            url: '{:url('cash/index')}',
            method: 'post', //如果无需自定义HTTP类型，可不加该参数
            page: {groups:15, limit:10},
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: '申请ID'},
                {field: 'realname', title: '管理员姓名'},
                {field: 'uid', title: '管理员ID'},
                { title: '申请金额',templet: function(d) {
                    return d.money / 100 ;
                }},
                { title: '服务费',templet: function(d) {
                    return d.service / 100 ;
                }},
                { title: '实发金额',templet: function(d) {
                    return d.money_true / 100 ;
                }},
                {templet:function(d){
                    return util.toDateString(d.time_apply*1000);
                }, title: '申请时间',minWidth:160},
                { templet:function(d){
                    if(d.time_verify==0){ return ''; }else{ return util.toDateString(d.time_verify*1000); }
                    }, title: '客服审核' },
                { templet:function(d){
                if(d.time_finance==0){ return ''; }else{ return util.toDateString(d.time_finance*1000); }
                }, title: '财务审核' },
                { templet:function(d){
                    if(d.time_pay==0){ return ''; }else{ return util.toDateString(d.time_pay*1000); }
                    }, title: '打款时间' },
                { templet: function (d) {
                       if(d.status=={$Mcash::CASH_APPLY} ) return '申请';
                       if(d.status=={$Mcash::CASH_FINANCE} ) return '财务审核';
                       if(d.status=={$Mcash::CASH_PASS} ) return '通过';
                       if(d.status=={$Mcash::CASH_REFUSE} ) return '驳回';
                       if(d.status=={$Mcash::CASH_PAYED} ) return '已打款';
                   }, title:'状态'},
                { templet: function(d){
                    if(d.status=={$Mcash::CASH_APPLY} ) return '<a class="layui-btn layui-btn-xs" lay-event="edit">待客服审核</a><a class="layui-btn layui-btn-xs" lay-event="show">数据核对</a>';
                    if(d.status=={$Mcash::CASH_FINANCE} ) return '<a class="layui-btn layui-btn-xs" lay-event="finance">待财务审核</a><a class="layui-btn layui-btn-xs" lay-event="show">数据核对</a>';
                    if(d.status=={$Mcash::CASH_REFUSE} ) return '<a class="layui-btn layui-btn-xs" lay-event="view">拒绝原因</a><a class="layui-btn layui-btn-xs" lay-event="show">数据核对</a>';
                    if(d.status=={$Mcash::CASH_PASS} ) return '<a class="layui-btn layui-btn-xs" lay-event="pay">等待发票</a><a class="layui-btn layui-btn-xs" lay-event="show">数据核对</a>';
                    if(d.status=={$Mcash::CASH_PAYED} ) return '<a class="layui-btn layui-btn-xs" lay-event="payinfo">已打款</a><a class="layui-btn layui-btn-xs" lay-event="show">数据核对</a>';
                },title:'操作',minWidth:160 },
                {field: 'account_name', title: '对公账户'},
                {field: 'account_bank', title: '银行'},
                {field: 'account_number', title: '账户'},
                {field: 'account_tax', title: '纳税人识别号'},
                
            ]]
        });

        
        // 搜索
        $('#btnSearch').click(function () {
            var search = new Object();
            search.s1 = $('#s_1').val();
            search.s2 = $('#s_2').val();
            search.s3 = $('#s_3').val();
            if (!search.s1 && !search.s2 && !search.s3 ) {
                layer.msg('没有搜索条件', {icon: 2});
                layer.confirm('没有搜索条件,是否执行查询', {icon: 3, title:'提示'},function(index){
                    search = new Object();
                    insTb.reload({where: search ,page: {page: 1 } });
                    layer.close(index);
                });
            }else {
                insTb.reload({where: search ,page: {page: 1 } });
            }
        });

        // 导出
        $('#btnExport').click(function () {
            var search = new Object();
            search.s1 = $('#s_1').val();
            search.s2 = $('#s_2').val();
            search.s3 = $('#s_3').val();
            
            var form = $('<form method="POST" action="{:url('cash/export')}">');
            $.each(search, function(k, v) {
                form.append($('<input type="hidden" name="' + k +'" value="' + v + '">'));
            });
            $('body').append(form);
            form.submit();
            $('body').remove(form);

        });


        

        // 工具条点击事件
        table.on('tool(objTable)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            
            if (layEvent === 'edit') {          // 客服审核
                showEditModel(data);
            }else if(layEvent === 'finance'){   //财务审核
                showfinance(data);
            }else if(layEvent === 'pay'){       //打款
                showPay(data);
            }else if(layEvent === 'view'){      //驳回, 查看驳回原因
                showView(data);
            }else if(layEvent === 'payinfo'){   //打款详情
                showpayinfo(data);
            }

        });

       
        function showView(data)
        {
            layer.open({
                title:'备注:',
                content: data.comment
            });
        }

        function showpayinfo(data)
        {
            layer.open({
                title:'打款详情:',
                content: '打款单号:'+data.paybill+" 备注:"+data.comment
            });
        }

        // 显示表单弹窗
        function showEditModel(M) {
            if( M.status=={$Mcash::CASH_PASS} ){
                layer.msg('已审核通过', {icon: 1});
                return;
            }
            var go = 0;
            $.ajax({
                url:'{:url('auth/verify')}',
                type:'post', 
                data:{authstr:'cash/update'},
                dataType:'json',
                async:false,
                success: function (res) {
                    if(res.code == 1) {
                        go=1;
                        console.log(go);
                        layer.msg('等待客服审核, 你没有客服审核权限');
                    }
                }
            });
            console.log(go);
            if(go===0){
                admin.open({
                    type: 1,
                    title: '审核',
                    content: $('#modelObj').html(),
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow', 'visible');
                        var url = "{:url('cash/update')}";
                        // 回显数据
                        if (M) {
                            M.money = M.money/100;
                            form.val('modelObjForm', M);
                        } 

                        // 表单提交事件
                        form.on('submit(modelObjSubmit)', function (data) {
                            //data.field.roleIds = formSelects.value('roleId', 'valStr');
                            if( data.field.status!='' && data.field.status != M.status){
                                if(data.field.status=={$Mcash::CASH_REFUSE} ){
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
                
        }


        // 显示 财务审核 表单弹窗
        function showfinance(M) {
            if( M.status=={$Mcash::CASH_PASS} ){
                layer.msg('已审核通过', {icon: 1});
                return;
            }

            var go=0;
            $.ajax({
                url:'{:url('auth/verify')}',
                type:'post', 
                data:{authstr:'cash/finance'},
                dataType:'json',
                async:false,
                success: function (res) {
                    if(res.code == 1) {
                        go=1;
                        console.log(go);
                        layer.msg('等待财务审核, 你没有财务审核权限');
                    }
                }
            });

            console.log(go);
            if(go===0){
                admin.open({
                    type: 1,
                    title: '财务审核',
                    content: $('#financeModel').html(),
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow', 'visible');
                        var url = "{:url('cash/finance')}";
                        // 回显数据
                        if (M) {
                            M.money = M.money/100;
                            form.val('financeForm', M);
                        }

                        // 表单提交事件
                        form.on('submit(financeSubmit)', function (data) {
                            //data.field.roleIds = formSelects.value('roleId', 'valStr');
                            if( data.field.status!='' && data.field.status != M.status){
                                if(data.field.status=={$Mcash::CASH_REFUSE} ){
                                    if(data.field.comment==''){
                                        layer.msg('请输入驳回内容', {icon: 2});
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

                
        }

        function showPay(M)
        {
            var go=0;
            $.ajax({
                url:'{:url('auth/verify')}',
                type:'post', 
                data:{authstr:'cash/pay'},
                dataType:'json',
                async:false,
                success: function (res) {
                    if(res.code == 1) {
                        go=1;
                        console.log(go);
                        layer.msg('等待财务打款, 你没有财务打款权限');
                    }
                }
            });
            console.log(go);
            
            if(go===0){
                 layer.open({
                    type: 1,
                    title:'打款',
                    content: $('#payModel').html(),
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow', 'visible');
                        var url = "{:url('cash/pay')}";
                        // 回显数据
                        if (M) {
                            M.money = M.money/100;
                            form.val('payForm', M);
                        }

                        // 表单提交事件
                        form.on('submit(paySubmit)', function (data) {
                            //data.field.roleIds = formSelects.value('roleId', 'valStr');
                            
                            if(data.field.status=={$Mcash::CASH_REFUSE} ){
                                if(data.field.comment==''){
                                    layer.msg('请输入打款单号', {icon: 2});
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
                                
                            return false;
                            
                        });
                    }
                });
             }
        }

         

    });
</script>

</body>
</html>