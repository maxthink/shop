{include file="public/header" /}

<div class="layui-card-body" style="text-align: center;">
    <h2 style="margin-top: 170px;margin-bottom: 20px;font-size: 28px;color: #91ADDC;">欢迎使用ThinkWeb管理系统 !</h2>
    <img src="/static/admin/images/welcome.png" style="max-width: 100%;">
</div>

<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>

<script>
    layui.use(['layer'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
    });
</script>
</body>

</html>