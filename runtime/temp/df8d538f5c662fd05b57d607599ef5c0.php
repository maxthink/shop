<?php /*a:2:{s:52:"/data/www/csgx2/application/admin/view/store_add.php";i:1564394054;s:56:"/data/www/csgx2/application/admin/view/public_header.php";i:1564456502;}*/ ?>
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
    <div class="postionNav wd-bd-b wd-mg-b15" style="font-weight:bold">添加线下店铺信息</div>
    <hr class="layui-bg-green">
    <div class="wd-pd-tb10 layui-form" id="parent">
        <div class="layui-form-item">
            <label class="layui-form-label">店铺名称:</label>
            <div class="layui-input-inline" style="width:500px;">
                <input type="text" name="store_name" lay-verify="required|font_len" class="layui-input">
            </div>
            <span class="wd-lh-36" style="color: red;font-size: 30px">*</span>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">负责人:</label>
            <div class="layui-input-inline" style="width:200px;">
                <input type="text" name="store_principal" lay-verify="required" class="layui-input">
            </div>
            <span class="wd-lh-36" style="color: red;font-size: 30px">*</span>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系电话:</label>
            <div class="layui-input-inline" style="width:200px;">
                <input type="text" name="store_phone"  value=""  lay-verify="store_phone|required" class="layui-input">
            </div>
            <span class="wd-lh-36" style="color: red">*请填写11位联系电话，座机请添加区号</span>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">设备编号:</label>
            <div class="layui-input-inline" style="width:200px;">
                <input type="text" name="imei[]" lay-verify="imei_check|required" class="layui-input">
            </div>
            [<a href="javascript:void(0);" class="add">+</a>]
            <span class="wd-lh-36" style="color: red;font-size: 15px">* 请输入15位设备编号</span>
        </div>
        <div class="layui-form-item" style="margin-top: 30px">
            <label class="layui-form-label">省份选择</label>
            <div class="layui-input-block" style="width:200px;">
                <select name="province_id">
                    <option value="北京市">北京市</option>
                    <option value="浙江省">浙江省</option>
                    <option value="天津市">天津市</option>
                    <option value="安徽省">安徽省</option>
                    <option value="上海市">上海市</option>
                    <option value="福建省">福建省</option>
                    <option value="重庆市">重庆市</option>
                    <option value="江西省">江西省</option>
                    <option value="山东省">山东省</option>
                    <option value="河南省">河南省</option>
                    <option value="湖北省">湖北省</option>
                    <option value="湖南省">湖南省</option>
                    <option value="广东省">广东省</option>
                    <option value="海南省">海南省</option>
                    <option value="山西省">山西省</option>
                    <option value="青海省">青海省</option>
                    <option value="江苏省">江苏省</option>
                    <option value="辽宁省">辽宁省</option>
                    <option value="吉林省">吉林省</option>
                    <option value="台湾省">台湾省</option>
                    <option value="河北省">河北省</option>
                    <option value="贵州省">贵州省</option>
                    <option value="四川省">四川省</option>
                    <option value="云南省">云南省</option>
                    <option value="陕西省">陕西省</option>
                    <option value="甘肃省">甘肃省</option>
                    <option value="黑龙江省">黑龙江省</option>
                    <option value="香港特别行政区">香港特别行政区</option>
                    <option value="澳门特别行政区">澳门特别行政区</option>
                    <option value="广西壮族自治区">广西壮族自治区</option>
                    <option value="宁夏回族自治区">宁夏回族自治区</option>
                    <option value="新疆维吾尔自治区">新疆维吾尔自治区</option>
                    <option value="内蒙古自治区">内蒙古自治区</option>
                    <option value="西藏自治区">西藏自治区</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">店铺地址:</label>
            <div class="layui-input-inline" style="width:500px;">
                <input type="text" name="store_address" lay-verify="required|ft_len"  value="" class="layui-input">
            </div>
            <span class="wd-lh-36" style="color: red">*请填写完整的详细地址 保证数据准确性</span>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>上传店铺图片</legend>
        </fieldset>

        <div class="layui-upload" style="margin-left: 30px">
            <button type="button" class="layui-btn" id="imgs">图片上传</button>
            <span class="wd-lh-36" style="color: red;margin-left: 20px">*支持JPG、JPEG、PNG图片格式，最多支持上传4张</span>
            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                预览图：
                <div class="layui-upload-list" id="imgArrWrap"></div>
            </blockquote>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-form-inline">
                <button class="layui-btn" lay-submit lay-filter="save">确认添加</button>
                <a href="<?php echo url('store/index'); ?>"><button class="layui-btn layui-btn-primary">返回</button></a>
            </div>
        </div>
    </div>
</div>
<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
<script>
    layui.use(['form','layer','upload'], function() {
         var $ = layui.jquery,
             form = layui.form,
             upload = layui.upload;
        $('.add').click(function(){
            var add_div ='<div class="layui-form-item"><label class="layui-form-label"></label><div class="layui-input-inline" style="width:200px;"><input type="text" name="imei[]" lay-verify="imei_check|required" class="layui-input"></div>[<a href="javascript:void(0);" class="rmv">-</a>]<span class="wd-lh-36" style="color: red;font-size: 15px">* 请输入15位设备编号</span></div>';
            $(this).parent().after(add_div);
        });
        $('#parent').on("click",".rmv",function(){
            $(this).parent().remove();
        });
        //规则验证
        form.verify({
            store_phone:[/^\d{11}$/,'请输入正确的联系方式'],
            imei_check:[/^\d{15}$/,'请输入正确的设备编号'],
            font_len:function(value){
                if(value.length >20){
                    return '输入过长';
                }
            },
            ft_len:function(value){
                if(value.length >50){
                    return '输入过长';
                }
            }
        });

        //上传图片
        var imgArrWrap = $('#imgArrWrap'),i=1,imgArr = [];
        upload.render({
            elem: '#imgs',
            url: "<?php echo url('store/upload'); ?>",
            done: function(res){
                if(res.status==1){
                    if(i >= 4 ){
                        $('#imgs').hide();
                    }
                    imgArrWrap.append('<li style="display:inline-block;position:relative;margin-right:15px;"><img src="' + res.data + '" height="150" width="220"><input type="hidden" name="p_img'+(imgArr[0] || i)+'" value="'+res.data+'"/><span class="delBtn" style="position:absolute;right:0;bottom:0;background-color:#ff6600;color:#fff;padding:2px 6px;cursor:pointer;" data-src="' + res.data + '" data-index="'+(imgArr[0] || i)+'" >X</span></li>');
                    if(imgArr.lenght!=''){
                        imgArr.shift();
                    }
                    i++;
                }else{
                    layer.msg(res.msg);
                }
            }
        });

        var removeImg = function(obj){
            obj.parent().remove();
            let newIndex = obj.data('index');
            imgArr.push(newIndex)
            i--;
            if(i<5){
                $('#imgs').show();
            }
        };
        // 删除上传的图片
        imgArrWrap.on('click', '.delBtn', function () {
            var _this = $(this),imgurl = $(this).data('src');
            $.post("<?php echo url('store/DelImg'); ?>", {src: imgurl}, function (res) {
                if (res == 0) {
                    layer.msg("删除失败");
                } else {
                    removeImg(_this)
                }
            });
        });

        //提交
        var isSub = true;
        form.on('submit(save)',function(data){
            if(isSub){
                if(i>=4){
                    isSub = false;
                    $.post("<?php echo url('store/add'); ?>", data.field,function(res){
                        if(res.status == 1){
                            layer.msg(res.msg);
                            setTimeout(function(){
                                window.location.href="<?php echo url('store/index'); ?>";
                            },1000)
                        }else if(res.status == 3){
                            isSub = true;
                            layer.msg(res.msg);
                        }else{
                            isSub = true;
                            layer.msg(res.msg);
                        }
                    },'json');
                }else{
                    isSub = true;
                    layer.msg('请上传4张店铺图片');
                }
            }

        })
    })
</script>
</body>
</html>