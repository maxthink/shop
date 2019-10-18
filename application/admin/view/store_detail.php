{include file="public/header" /}
<!-- 正文开始 -->
<div class="wd-bd-b wd-pd-b10" style="margin-left: 20px;margin-top:10px">
    <div>
        <button type="button" class=" layui-btn-lg layui-btn-primary layui-btn-radius" disabled="disabled" style="margin-right: 10px">店铺详情信息展示</button>
        <a href="{:url('store/index')}" style="margin-right: 10px">
            <button class="layui-btn layui-btn-radius layui-btn-normal">返回店铺列表页</button>
        </a>
    </div>
    <hr class="layui-bg-green">
    <div class="wd-pd-tb10 layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">店铺名称:</label>
            <div class="layui-input-inline" style="width:500px;">
                <input type="text" name="store_name" lay-verify="required" class="layui-input" value="{$Store['store_name']}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">负责人:</label>
            <div class="layui-input-inline" style="width:150px;">
                <input type="text" name="store_principal" lay-verify="required" class="layui-input" value="{$Store['store_principal']}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系电话:</label>
            <div class="layui-input-inline" style="width:150px;">
                <input type="text" name="store_phone" lay-verify="store_phone|required" class="layui-input" value="{$Store['store_phone']}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">所属地区:</label>
            <div class="layui-input-inline" style="width:150px;">
                <input type="text" name="store_address" lay-verify="required"class="layui-input" value="{$Store['province_name']}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">店铺地址:</label>
            <div class="layui-input-inline" style="width:500px;">
                <input type="text" name="store_address" lay-verify="required"class="layui-input" value="{$Store['store_address']}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">设备编号:</label>
            {foreach name="$Store['store_imei']" item="vo"}
            <div class="layui-input-inline" style="width:150px;">
                <input type="text" name="store_imie" lay-verify="required"class="layui-input" value="{$vo}" disabled>
            </div>
            {/foreach}
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">添加时间:</label>
            <div class="layui-input-inline" style="width:150px;">
                <input type="text" name="store_address" lay-verify="required"class="layui-input" value="{$Store['ctime']}" disabled>
            </div>
        </div>

        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>店铺图片展示</legend>
        </fieldset>

        <div class="layui-upload" style="margin-left: 30px">
            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                详情图：
                <div class="layui-upload-list" id="imgArrWrap">
                    {foreach name="images" item="vo"}
                <span style="margin-right: 15px">
                    <img src="{$vo}" height="150" width="220">
               </span>
                    {/foreach}
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