{include file="public/header" /}
<style>
        .user-info-head {
            width: 110px;
            height: 110px;
            position: relative;
            display: inline-block;
        }

        .user-info-head:hover:after {
            content: '\e65d';
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            color: #eee;
            background: rgba(0, 0, 0, 0.5);
            font-family: layui-icon;
            font-size: 24px;
            font-style: normal;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            cursor: pointer;
            line-height: 110px;
            border-radius: 50%;
        }

        .user-info-head img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
        }

        .info-list-item {
            position: relative;
            padding-bottom: 8px;
        }

        .info-list-item > .layui-icon {
            position: absolute;
        }

        .info-list-item > p {
            padding-left: 30px;
        }

        .dash {
            border-bottom: 1px dashed #ccc;
            margin: 15px 0;
        }

        .layui-badge-list .layui-badge {
            margin-right: 6px;
        }

        .layui-badge-list .layui-badge {
            padding: 2px 7px;
            border: 1px solid #ccc;
            margin-bottom: 8px;
            background-color: #fafafa !important;
        }

        .bd-list-item {
            padding: 14px 0;
            border-bottom: 1px solid #e8e8e8;
            position: relative;
        }

        .bd-list-item .bd-list-item-img {
            width: 48px;
            height: 48px;
            line-height: 48px;
            margin-right: 12px;
            display: inline-block;
            vertical-align: middle;
        }

        .bd-list-item .bd-list-item-content {
            display: inline-block;
            vertical-align: middle;
        }

        .bd-list-item .bd-list-item-lable {
            margin-bottom: 4px;
            color: #333;
        }

        .bd-list-item .bd-list-item-oper {
            position: absolute;
            right: 0;
            top: 50%;
            text-decoration: none !important;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .user-info-form .layui-form-item {
            margin-bottom: 25px;
        }
    </style>
<!-- 正文开始 -->
<div class="layui-fluid">

        <div class="layui-col-sm12 layui-col-md9">
            <div class="layui-card">
                <div class="layui-card-body layui-text">

                    <div class="layui-tab layui-tab-brief" lay-filter="userInfoTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this">基本信息</li>
                            <li class="">账号绑定</li>
                            <li class="">提现账户</li>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">

                                <div class="layui-form user-info-form" style="max-width: 600px;padding-top: 25px;">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">邮箱:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="email" value="antdesign@alipay.com" class="layui-input" lay-verify="required" required="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">昵称:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="name" value="Serati Ma" class="layui-input" lay-verify="required" required="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">个人简介:</label>
                                        <div class="layui-input-block">
                                            <textarea name="desc" placeholder="个人简介" class="layui-textarea"></textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">街道地址:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="address" value="西湖区工专路 77 号" class="layui-input" lay-verify="required" required="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">联系电话:</label>
                                        <div class="layui-input-block">
                                            <div style="width: 30%;display: inline-block;margin-right: 6px;">
                                                <input type="text" name="phone1" value="0752" class="layui-input" lay-verify="required" required="">
                                            </div>
                                            <div style="width: 66%;display: inline-block;">
                                                <input type="text" name="phone2" value="268888888" class="layui-input" lay-verify="required" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" lay-filter="userInfoSubmit" lay-submit="">
                                                更新基本信息
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="layui-tab-item" style="padding: 6px 25px 30px 25px;">

                                <div class="bd-list">
                                    <div class="bd-list-item">
                                        <div class="bd-list-item-content">
                                            <div class="bd-list-item-lable">密保手机</div>
                                            <div class="bd-list-item-text">已绑定手机：138****8293</div>
                                        </div>
                                        <a class="bd-list-item-oper">修改</a>
                                    </div>
                                    <div class="bd-list-item">
                                        <div class="bd-list-item-content">
                                            <div class="bd-list-item-lable">密保邮箱</div>
                                            <div class="bd-list-item-text">已绑定邮箱：ant***sign.com</div>
                                        </div>
                                        <a class="bd-list-item-oper">修改</a>
                                    </div>
                                    <div class="bd-list-item">
                                        <div class="bd-list-item-img">
                                            <i class="layui-icon layui-icon-login-qq" style="color: #3492ED;font-size: 48px;"></i>
                                        </div>
                                        <div class="bd-list-item-content">
                                            <div class="bd-list-item-lable">绑定QQ</div>
                                            <div class="bd-list-item-text">当前未绑定QQ账号</div>
                                        </div>
                                        <a class="bd-list-item-oper">绑定</a>
                                    </div>
                                    <div class="bd-list-item">
                                        <div class="bd-list-item-img">
                                            <i class="layui-icon layui-icon-login-wechat" style="color: #4DAF29;font-size: 48px;"></i>
                                        </div>
                                        <div class="bd-list-item-content">
                                            <div class="bd-list-item-lable">绑定微信</div>
                                            <div class="bd-list-item-text">当前未绑定绑定微信账号</div>
                                        </div>
                                        <a class="bd-list-item-oper">绑定</a>
                                    </div>
                                </div>

                            </div>
                            <div class="layui-tab-item" style="padding: 6px 25px 30px 25px;">

                                <div class="bd-list">
                                    <div class="bd-list-item">
                                        <div class="bd-list-item-content">
                                            <div class="bd-list-item-lable">开户银行</div>
                                            <div class="bd-list-item-text">中国工商银行 北京市通州区北苑路支行</div>
                                        </div>
                                        <a class="bd-list-item-oper">修改</a>
                                    </div>
                                    <div class="bd-list-item">
                                        <div class="bd-list-item-content">
                                            <div class="bd-list-item-lable">银行账户</div>
                                            <div class="bd-list-item-text">6235 5265 1526 778</div>
                                        </div>

                                    </div>
                                    <div class="bd-list-item">
                                        <div class="bd-list-item-content">
                                            <div class="bd-list-item-lable">姓名</div>
                                            <div class="bd-list-item-text">邱珊奎</div>
                                        </div>
                                    </div>
                                    
                                </div>

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
    layui.use(['layer', 'form', 'element', 'util', 'admin', 'formSelects'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var util = layui.util;
        var admin = layui.admin;
        var formSelects = layui.formSelects;

        //显示用户详情
        function showUserDetail(uid){
            
        }
        
    });
</script>

</body>
</html>