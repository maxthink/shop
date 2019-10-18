{include file="public/header" /}
<!-- 正文开始 -->
<div class="wd-bd-b wd-pd-b10" style="margin-left: 20px;margin-top:10px">
    <div>
        <button type="button" class=" layui-btn-lg layui-btn-primary layui-btn-radius" disabled="disabled" style="margin-right: 10px">店铺信息修改</button>
        <a href="{:url('store/index')}" style="margin-right: 10px">
            <button class="layui-btn layui-btn-radius layui-btn-normal">返回店铺列表页</button>
        </a>
    </div>
    <hr class="layui-bg-green">
    <div class="wd-pd-tb10 layui-form" id="parent">
        <div class="layui-form-item">
            <label class="layui-form-label">店铺名称:</label>
            <div class="layui-input-inline" style="width:500px;">
                <input type="hidden" name="store_id" value="{$Store['id']}">
                <input type="text" name="store_name" lay-verify="required|font_len" class="layui-input" value="{$Store['store_name']}">
            </div>
            <span class="wd-lh-36" style="color: red;font-size: 30px">*</span>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">负责人:</label>
            <div class="layui-input-inline" style="width:200px;">
                <input type="text" name="store_principal" lay-verify="required" class="layui-input" value="{$Store['store_principal']}">
            </div>
            <span class="wd-lh-36" style="color: red;font-size: 30px">*</span>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系电话:</label>
            <div class="layui-input-inline" style="width:200px;">
                <input type="text" name="store_phone"  lay-verify="store_phone|required" class="layui-input" value="{$Store['store_phone']}">
            </div>
            <span class="wd-lh-36" style="color: red">*请填写11位联系电话，座机请添加区号</span>
        </div>
        {foreach name="imeis" item="vo" key="k"}
        {if condition="$k eq 0"}
        <div class="layui-form-item">
            <label class="layui-form-label">设备编号:</label>
            <div class="layui-input-inline" style="width:200px;">
                <input type="text" name="imei[]" lay-verify="imei_check|required" class="layui-input" value="{$vo}">
            </div>
            [<a href="javascript:void(0);" class="add">+</a>]
            <span class="wd-lh-36" style="color: red;font-size: 15px">* 请输入15位设备编号</span>
        </div>
        {else/}
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-inline" style="width:200px;">
                <input type="text" name="imei[]" lay-verify="imei_check|required" class="layui-input" value="{$vo}">
            </div>
            [<a href="javascript:void(0);" class="rmv">-</a>]
                <span class="wd-lh-36" style="color: red;font-size: 15px">* 请输入15位设备编号</span>
        </div>
        {/if}
        {/foreach}

        <div class="layui-form-item">
            <label class="layui-form-label">店铺地址:</label>
            <div class="layui-input-inline" style="width:500px;">
                <input type="text" name="store_address" lay-verify="required|ft_len" class="layui-input" value="{$Store['store_address']}">
            </div>
            <span class="wd-lh-36" style="color: red;font-size: 30px">*</span>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>上传店铺图片</legend>
        </fieldset>

        <div class="layui-upload" style="margin-left: 30px">
            <button type="button" class="layui-btn" id="imgs">图片上传</button>
            <span class="wd-lh-36" style="color: red;margin-left: 20px">*支持JPG、JPEG、PNG图片格式，最多支持上传4张</span>
            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                预览图：
                <div class="layui-upload-list" id="imgArrWrap">
                    {volist name="images" id="vo" key="k"}
                        <li style="display:inline-block;position:relative;margin-right:5px;">
                            <img src="{$vo.img_url}" height="150" width="220">
                            <input type="hidden" name="p_img{$k}" value="{$vo.img_url}"/>
                            <span class="delBtn" style="position:absolute;right:0;bottom:0;background-color:#ff6600;color:#fff;padding:2px 6px;cursor:pointer;" data-src="{$vo.img_url}" data-index="{$k}">X</span>
                        </li>
                    {/volist}
                </div>
            </blockquote>
        </div>
        <?php $Store ?>
        <div class="layui-form-item" style="margin-top: 30px">
            <label class="layui-form-label">省份选择</label>
            <div class="layui-input-block" style="width:200px;">
                <select name="province_id">
                    <option value="北京市" <?php if($Store['province_name']=="北京市") echo "selected"; ?>>北京市</option>
                    <option value="浙江省" <?php if($Store['province_name']=="浙江省") echo "selected"; ?>>浙江省</option>
                    <option value="天津市" <?php if($Store['province_name']=="天津市") echo "selected"; ?>>天津市</option>
                    <option value="安徽省" <?php if($Store['province_name']=="安徽省") echo "selected"; ?>>安徽省</option>
                    <option value="上海市" <?php if($Store['province_name']=="上海市") echo "selected"; ?>>上海市</option>
                    <option value="福建省" <?php if($Store['province_name']=="福建省") echo "selected"; ?>>福建省</option>
                    <option value="重庆市" <?php if($Store['province_name']=="重庆市") echo "selected"; ?>>重庆市</option>
                    <option value="江西省" <?php if($Store['province_name']=="江西省") echo "selected"; ?>>江西省</option>
                    <option value="山东省" <?php if($Store['province_name']=="山东省") echo "selected"; ?>>山东省</option>
                    <option value="河南省" <?php if($Store['province_name']=="河南省") echo "selected"; ?>>河南省</option>
                    <option value="湖北省"<?php if($Store['province_name']=="湖北省") echo "selected"; ?>>湖北省</option>
                    <option value="湖南省" <?php if($Store['province_name']=="湖南省") echo "selected"; ?>>湖南省</option>
                    <option value="广东省" <?php if($Store['province_name']=="广东省") echo "selected"; ?>>广东省</option>
                    <option value="海南省" <?php if($Store['province_name']=="海南省") echo "selected"; ?>>海南省</option>
                    <option value="山西省" <?php if($Store['province_name']=="山西省") echo "selected"; ?>>山西省</option>
                    <option value="青海省" <?php if($Store['province_name']=="青海省") echo "selected"; ?>>青海省</option>
                    <option value="江苏省" <?php if($Store['province_name']=="江苏省") echo "selected"; ?>>江苏省</option>
                    <option value="辽宁省" <?php if($Store['province_name']=="辽宁省") echo "selected"; ?>>辽宁省</option>
                    <option value="吉林省" <?php if($Store['province_name']=="吉林省") echo "selected"; ?>>吉林省</option>
                    <option value="台湾省" <?php if($Store['province_name']=="台湾省") echo "selected"; ?>>台湾省</option>
                    <option value="河北省" <?php if($Store['province_name']=="河北省") echo "selected"; ?>>河北省</option>
                    <option value="贵州省" <?php if($Store['province_name']=="贵州省") echo "selected"; ?>>贵州省</option>
                    <option value="四川省" <?php if($Store['province_name']=="四川省") echo "selected"; ?>>四川省</option>
                    <option value="云南省" <?php if($Store['province_name']=="云南省") echo "selected"; ?>>云南省</option>
                    <option value="陕西省" <?php if($Store['province_name']=="陕西省") echo "selected"; ?>>陕西省</option>
                    <option value="甘肃省" <?php if($Store['province_name']=="甘肃省") echo "selected"; ?>>甘肃省</option>
                    <option value="黑龙江省" <?php if($Store['province_name']=="黑龙江省") echo "selected"; ?>>黑龙江省</option>
                    <option value="香港特别行政区" <?php if($Store['province_name']=="香港特别行政区") echo "selected"; ?>>香港特别行政区</option>
                    <option value="澳门特别行政区" <?php if($Store['province_name']=="澳门特别行政区") echo "selected"; ?>>澳门特别行政区</option>
                    <option value="广西壮族自治区" <?php if($Store['province_name']=="广西壮族自治区") echo "selected"; ?>>广西壮族自治区</option>
                    <option value="宁夏回族自治区" <?php if($Store['province_name']=="宁夏回族自治区") echo "selected"; ?>>宁夏回族自治区</option>
                    <option value="新疆维吾尔自治区" <?php if($Store['province_name']=="新疆维吾尔自治区") echo "selected"; ?>>新疆维吾尔自治区</option>
                    <option value="内蒙古自治区" <?php if($Store['province_name']=="内蒙古自治区") echo "selected"; ?>>内蒙古自治区</option>
                    <option value="西藏自治区" <?php if($Store['province_name']=="西藏自治区") echo "selected"; ?>>西藏自治区</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-form-inline">
                <button class="layui-btn" lay-submit lay-filter="save">确认添加</button>
                <a href="{:url('store/index')}"><button class="layui-btn layui-btn-primary">返回</button></a>
            </div>
        </div>
    </div>
</div>
<!-- js部分 -->
<script type="text/javascript" src="/static/admin/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js?v=311"></script>
<script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script>
<script>
    layui.use(['form','layer','upload'], function() {
        var $ = layui.jquery,
            form = layui.form,
            upload = layui.upload;

        $('.add').click(function(){
            var add_div ='<div class="layui-form-item"><label class="layui-form-label"></label><div class="layui-input-inline" style="width:200px;"><input type="text" name="imei[]" lay-verify="imei_check|required" class="layui-input"></div>[<a href="javascript:;" class="rmv">-</a>]<span class="wd-lh-36" style="color: red;font-size: 30px">*</span></div>';
            $(this).parent().after(add_div);
        });
        $('#parent').on("click",".rmv",function(){
            $(this).parent().remove();
        });
        //规则验证
        form.verify({
            store_phone:[/^\d{11}$/,'请输入正确格式'],
            imei_check:[/^\d{15}$/,'请输入正确的设备编号'],
            font_len:function(value){
                if(value.length >25){
                    return '输入过长';
                }
            },
            ft_len:function(value){
                if(value.length >50){
                    return '输入过长';
                }
            }
        })

        //上传图片
        var imgArrWrap = $('#imgArrWrap'),i=4,imgArr = [];
        $('#imgs').hide();
        upload.render({
            elem: '#imgs',
            url: "{:url('store/upload')}",
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
            if(i<4){
                $('#imgs').show();
            }
        }
        // 删除上传的图片
        imgArrWrap.on('click', '.delBtn', function () {
            var _this = $(this),imgurl = $(this).data('src'),newImg = $(this).data('new');
            if(newImg){
                $.post("{:url('store/DelImg')}", {src: imgurl}, function (res) {
                    if (res == 0) {
                        layer.msg("删除失败");
                    } else {
                        removeImg(_this)
                    }
                });
            }else{
                removeImg(_this)
            }
        });
        //提交
        var isSub = true;
        form.on('submit(save)',function(data){
            if(isSub){
                isSub = false;
                if(i>=4){
                    $.post("{:url('store/edit')}",data.field,function(res){
                        if(res.status == 1){
                            layer.msg(res.msg);
                            setTimeout(function(){
                                window.location.href="{:url('store/index')}";
                            },1000)
                        }else if(res.status == 2){
                            isSub = true;
                            layer.msg(res.msg);
                            location.reload();
                        }else if(res.status == 3){
                            isSub = true;
                            layer.msg(res.msg);
                        }else {
                            isSub = true;
                            layer.msg(res.msg);
                            location.reload();
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