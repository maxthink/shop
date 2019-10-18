{include file="public/header" /}
<style>
    /* 这段样式只是用于演示 */
    .demo-list .layui-card{height: 117px; padding:20px 0; -webkit-box-shadow: 1px 2px 1px 0 rgba(0,0,0,0.3); -moz-box-shadow: 1px 2px 1px 0 rgba(0,0,0,0.3); box-shadow: 1px 2px 1px 0 rgba(0,0,0,0.3); }
    .layui-card .title{ margin-top: 30px; color: #333; font-size: 22px; text-align: -webkit-center; text-align: center; }
    .layui-card .num{  color: #eb6528; font-size: 22px; text-align: -webkit-center; text-align: center; }
</style>

<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space20 demo-list">

        <div class="layui-col-sm4 layui-col-md3 layui-col-lg2">
            <div class="layui-card">
                <p class="title">账户余额</p>
                <p class="num">239.2元</p>
            </div>
        </div>
        <div class="layui-col-sm4 layui-col-md3 layui-col-lg2">
            <div class="layui-card">
                <p class="title">今日收入</p>
                <p class="num">239.2元</p>
            </div>
        </div>
        <div class="layui-col-sm4 layui-col-md3 layui-col-lg2">
            <div class="layui-card">
                <p class="title">昨日收入</p>
                <p class="num">239.2元</p>
            </div>
        </div>
        <div class="layui-col-sm4 layui-col-md3 layui-col-lg2">
            <div class="layui-card">
                <p class="title">总收入</p>
                <p class="num">239.2元</p>
            </div>
        </div>

    </div>

    <div class="layui-row layui-col-space20">
        <div class="layui-card">
            <!--div class="layui-card-header">简洁风格</div-->
            <div class="layui-card-body">
                <div class="layui-tab layui-tab-brief" lay-filter="component-tabs-brief">
                    <ul class="layui-tab-title">
                        <li class="layui-this">提现</li>
                        <li class="">提现记录</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div class="layui-card">
                                <div class="layui-card-body">
                                    <form class="layui-form" action="" lay-filter="component-form-element">
                                        <fieldset class="layui-elem-field">
                                            <legend>提现账户信息</legend>
                                            <div class="layui-field-box">
                                                <p>账户类型: 支付宝</p>
                                                <p>账户号: 2452kdo@keo.com.cn</p>
                                            </div>
                                        </fieldset>
                                        <div class="layui-col-lg6">
                                            <label class="layui-form-label">提现金额：</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="fullname" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label"> </label>
                                            <div class="layui-input-block">
                                                <input type="checkbox" name="agreement" title="我方确认：结算金额以创世共想审核确认的金额为准。" lay-skin="primary" checked="" ><div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin="primary"><span>我方确认：结算金额以创世共想审核确认的金额为准。</span><i class="layui-icon layui-icon-ok"></i></div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <div class="layui-input-block">
                                                <button class="layui-btn" lay-submit="" lay-filter="component-form-element">立即提交</button>
                                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="layui-tab-item">
                            <table class="layui-table" id="objTable" lay-filter="objTable"></table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
<script>
layui.use(['layer', 'form', 'table', 'util', 'admin', 'formSelects', 'element'], function () {
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
            url: '{:url('exchange/index')}',
            method: 'post', //如果无需自定义HTTP类型，可不加该参数
            page: true,
            cellMinWidth: 100,
            cols: [[
            {field: 'id', title: 'ID'},
            {field: 'dev_id', title: '设备ID'},
            {field: 'code', title: '订单编号'},
            {field: 'user_id', title: '用户id'},
            {field: 'price', title: '金额', templet: function(d) {
            return d.price / 100;
            }},
            {templet: '#tableBar', title: '操作'},
            ]]
    });
    

});
</script>

</body>
</html>