<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>邮件服务</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="stylesheet" href="/static/admin/libs/layui/css/layui.css"/>
<link rel="stylesheet" href="/static/admin/module/admin/admin.css?v=311"/>
</head>
<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">后台设置</div>
          <div class="layui-card-body">
            
            <div class="layui-form" wid100 lay-filter="">
              <div class="layui-form-item">
                <label class="layui-form-label">标题</label>
                <div class="layui-input-inline">
                  <input type="text" name="smtp_server" value="创世共想" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">例: 百度,阿里云</div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">版权信息</label>
                <div class="layui-input-inline" style="width: 380px;">
                  <input type="text" name="cache" lay-verify="" value="© 2019 thinkweb.com MIT license" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">例: copy right baidu 2019</div>
              </div>
             
              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="set_system_email">确认保存</button>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="/static/admin/libs/layui/layui.js"></script>  
  <script>
  layui.config({
    base: '../../../layuiadmin/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use(['index', 'set']);
  </script>
</body>
</html>