<?php /*a:2:{s:55:"/data/www/csgx2/application/admin/view/store_detail.php";i:1563864619;s:56:"/data/www/csgx2/application/admin/view/public_header.php";i:1564456502;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>管理中心</title>
    <link rel="stylesheet" href="/static/admin/libs/layui/css/layui.css"/>
    <link rel="stylesheet" href="/static/admin/module/admin/admin.css?v=311"/>
    <link rel="stylesheet" href="/static/admin/module/formSelects/formSelects-v4.css"/>
</head>
<body>

<!-- 页面加载loading -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>

<!-- 正文开始 -->
<div class="wd-bd-b wd-pd-b10" style="margin-left: 20px;margin-top:10px">
    <div>
        <button type="button" class=" layui-btn-lg layui-btn-primary layui-btn-radius" disabled="disabled" style="margin-right: 10px">店铺详情信息展示</button>
        <a href="<?php echo url('store/index'); ?>" style="margin-right: 10px">
            <button class="layui-btn layui-btn-radius layui-btn-normal">返回店铺列表页</button>
        </a>
    </div>
    <hr class="layui-bg-green">
    <div class="wd-pd-tb10 layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">店铺名称:</label>
            <div class="layui-input-inline" style="width:500px;">
                <input type="text" name="store_name" lay-verify="required" class="layui-input" value="<?php echo htmlentities($Store['store_name']); ?>" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">负责人:</label>
            <div class="layui-input-inline" style="width:150px;">
                <input type="text" name="store_principal" lay-verify="required" class="layui-input" value="<?php echo htmlentities($Store['store_principal']); ?>" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系电话:</label>
            <div class="layui-input-inline" style="width:150px;">
                <input type="text" name="store_phone" lay-verify="store_phone|required" class="layui-input" value="<?php echo htmlentities($Store['store_phone']); ?>" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">所属地区:</label>
            <div class="layui-input-inline" style="width:150px;">
                <input type="text" name="store_address" lay-verify="required"class="layui-input" value="<?php echo htmlentities($Store['province_name']); ?>" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">店铺地址:</label>
            <div class="layui-input-inline" style="width:500px;">
                <input type="text" name="store_address" lay-verify="required"class="layui-input" value="<?php echo htmlentities($Store['store_address']); ?>" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">设备编号:</label>
            <?php if(is_array($Store['store_imei']) || $Store['store_imei'] instanceof \think\Collection || $Store['store_imei'] instanceof \think\Paginator): if( count($Store['store_imei'])==0 ) : echo "" ;else: foreach($Store['store_imei'] as $key=>$vo): ?>
            <div class="layui-input-inline" style="width:150px;">
                <input type="text" name="store_imie" lay-verify="required"class="layui-input" value="<?php echo htmlentities($vo); ?>" disabled>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">添加时间:</label>
            <div class="layui-input-inline" style="width:150px;">
                <input type="text" name="store_address" lay-verify="required"class="layui-input" value="<?php echo htmlentities($Store['ctime']); ?>" disabled>
            </div>
        </div>

        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>店铺图片展示</legend>
        </fieldset>

        <div class="layui-upload" style="margin-left: 30px">
            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                详情图：
                <div class="layui-upload-list" id="imgArrWrap">
                    <?php if(is_array($images) || $images instanceof \think\Collection || $images instanceof \think\Paginator): if( count($images)==0 ) : echo "" ;else: foreach($images as $key=>$vo): ?>
                <span style="margin-right: 15px">
                    <img src="<?php echo htmlentities($vo); ?>" height="150" width="220">
               </span>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </blockquote>
        </div>
    </div>
</div>
<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
</body>
</html>