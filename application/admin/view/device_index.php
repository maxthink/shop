{include file="public/header" /}
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    
                    <div class="layui-inline">
                        <div class="layui-input-inline mr0">
                            <input id="s_imei" class="layui-input" type="text" placeholder="请输入设备imei号"/>
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <select id="s_type" lay-verify="required" class="layui-select">
                            <option value="">全部设备类型</option>
                            <?php foreach($devtype as $type): ?>
                            <option value="<?php echo $type['id']; ?>" ><?php echo $type['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="layui-inline">
                        <button id="btnSearch" class="layui-btn icon-btn"><i class="layui-icon">&#xe615;</i>搜索</button>
                        <button id="btnAdd" class="layui-btn icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>
                    </div>
                </div>
            </div>

            <table class="layui-table" id="objTable" lay-filter="objTable"></table>
        </div>
    </div>
</div>

<!-- 表格操作列 -->
<script type="text/html" id="tableBar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">套餐</a>
    
</script>

<!-- 表格操作列 -->
<script type="text/html" id="passcode">
    <a class="layui-btn layui-btn-xs" lay-event="viewpass">授权码</a>
</script>

<!-- 表格操作列 -->
<script type="text/html" id="workBar">
    <a class="layui-btn layui-btn-xs" lay-event="work_start">启动</a>
    <a class="layui-btn layui-btn-xs" lay-event="work_stop">停止</a>
</script>
<!-- 表格操作列 二维码  -->
<script type="text/html" id="qrcodeBar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="qrcode">二维码</a>
</script>
<!-- 表格状态列 -->
<script type="text/html" id="tableState">
    <input type="checkbox" lay-filter="ckState" value="{{d.id}}" lay-skin="switch"
           lay-text="正常|锁定" {{d.lock==0 ?'checked':''}}/>
</script>
<!-- 表格状态列 -->

<!-- 表单弹窗 -->
<script type="text/html" id="modelObj">
    <form id="modelObjForm" lay-filter="modelObjForm" class="layui-form model-form">
        <input name="id" type="hidden"/>
        
        <div class="layui-form-item">
            <label class="layui-form-label">设备类型:</label>
            <div class="layui-input-block">
                <select name="type" xm-select="type" lay-verify="required" >
                    <option value="" >请选择设备类型</option>
                    <?php foreach($devtype as $type): ?>
                    <option value="<?php echo $type['id']; ?>" ><?php echo $type['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">imei号:</label>
            <div class="layui-input-block">
                <input name="imei" placeholder="" type="number" class="layui-input" maxlength="20" lay-verType="tips" lay-verify="required" readonly />
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">套餐:</label>
            <div class="layui-input-block">
                <select name="setmealId" xm-select="setmealId" lay-verType="tips" lay-verify="required">
                    <?php foreach( $objarr as $obj ): ?>
                        <option value="<?php echo $obj['id']?>"><?php echo $obj['name']?></option>
                    <?php endforeach; ?>
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
<script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script>
<script type="text/javascript" src="/static/common/js/jquery.qrcode.js?v=311"></script> 
<script>

    var devtype = [<?php  $_devtype = [];
    foreach($devtype as $type){
        $_devtype[]= '{id:'.$type['id'].',name:"'.$type['name'].'"}';
    }
    echo implode(',', $_devtype);
    ?>];
            
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
            url: "{:url('device/index')}",
            method: 'post', //如果无需自定义HTTP类型，可不加该参数
            page: {groups:15, limit:20},
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: '编号', width:60 },
                {field: 'type', title: '设备类型',templet:function(d){
                        for(var t in devtype){
                            if(d.type==devtype[t].id) return devtype[t].name;
                        }
                        return '未知设备';
                },width:100},
                {field: 'imei', title: 'imei编码',minWidth:150 },
                { title:'授权码',templet:'#passcode',width:80},

                {title:'远程操作',templet:'#workBar',width:120},
                {templet: '#tableState', title: '状态', width:100},
                {align: 'center', toolbar: '#tableBar', title: '套餐', width: 80},
                { templet: '#qrcodeBar', title: '二维码', width: 80},
                {field: 'online', title: '在线'},
                
                {field: 'realname', title: '管理员姓名'},
                { templet: function (d) {
                       if(d.lasttime<100){
                           return '';
                       } else {
                           return util.toDateString(d.lasttime*1000);
                       }
                   }, title:'最后通信时间',minWidth:180 },
                { templet: function (d) {
                    if(d.createtime<100){
                        return '';
                    } else {
                        return util.toDateString(d.createtime*1000);
                    }
                }, title: '创建时间', minWidth:180 }
                
            ]]
        });

        // 添加
        $('#btnAdd').click(function () {
            showEditModel();
        });

        // 搜索
        $('#btnSearch').click(function () {
            var search = new Object();
            search.imei = $('#s_imei').val();
            search.type = $('#s_type').val();
            if (!search.imei && !search.type) {
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

        // 工具条点击事件
        table.on('tool(objTable)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            if (layEvent === 'edit') { // 修改
                showEditModel(data);
            } else if (layEvent === 'del') { // 删除
                doDel(data.id, data.nickName);
            } else if (layEvent === 'reset') { // 重置密码
                resetPsw(data.id, data.username);
            } else if (layEvent === 'qrcode' ) {    //显示二维码
                showQrcode(data.id);
            } else if (layEvent === 'work_start' ){
                showWork(data.id);
            } else if (layEvent === 'work_stop' ){
                stopWork(data.id);
            } else if (layEvent === 'viewpass' ){
                showpass(data);
            }

        });

        // 显示表单弹窗
        function showEditModel(M) {
            admin.open({
                type: 1,
                title: (M ? '修改' : '添加') + '设备',
                content: $('#modelObj').html(),
                success: function (layero, dIndex) {
                    $(layero).children('.layui-layer-content').css('overflow', 'visible');
                    var url = M ? "{:url('device/update')}" : "{:url('device/add')}";
                    // 回显数据
                    var roleIds = new Array();
                    if (M) {
                        //$('input[name="username"]').attr('readonly', 'readonly');
                        //todo 组装套餐数据
                        var roles = M.setmealId.split(',');
                        console.log( typeof(roles) );
                        for (var i = 0; i < roles.length; i++) {
                            roleIds.push(roles[i]);
                        }
                        
                        form.val('modelObjForm', M);
                    } else {
                        form.render('radio');
                    }
                    formSelects.render('setmealId', {init: roleIds});   //重新加载套餐数据

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
        
        //显示二维码
        function showQrcode(devId){
            //todo 打开显示一个图层
            layer.open({
                title: false,
                closeBtn :false,
                area: ['340px', '400px'],
                content: '<div width="300" height="300" id="devqrcode" ></div>'
            }); 
            //todo 定义路径
            var url = "{:url('wx_dev_work','id','')}/"+devId;
            //todo 显示二维码
            $('#devqrcode').qrcode({
                render: 'image',    // render method: 'canvas', 'image' or 'div'
                ecLevel: 'Q',       // error correction level: 'L', 'M', 'Q' or 'H'
                size: 300,          // size in pixel
                fill: '#000',       // code color or image element
                background: '#fff', // background color or image element, null for transparent background
                text: url,          // content
                radius: 0,          // corner radius relative to module width: 0.0 .. 0.5
                quiet: 1,           // quiet zone in modules
                mode: 2,            // modes  0: normal  1: label strip  2: label box  3: image strip  4: image box
                mSize: 0.1,
                mPosX: 0.5,
                mPosY: 0.5,
                label: '创世共想',   // 显示文字
                fontname: '微软雅黑',   // 文字 字体
                fontcolor: '#08c',  //文字颜色
                image: '/logo.png'  //二维码图片
            });
        }

        function showpass(data)
        {
            layer.open({
                title: false,
                closeBtn :false,
                area: ['300px', '120px'],
                content: '<p style="width:100%;text-align:center;font-size:20px;">授权码: '+data.bindpassword.toLowerCase()+'</p>'
            }); 
        }

        // 修改用户锁定状态
        form.on('switch(ckState)', function (obj) {
            layer.load(2);
            
            $.post("{:url('device/lock')}", {
                Id: obj.elem.value,
                state: obj.elem.checked ? 0 : 1
            }, function (res) {
                layer.closeAll('loading');
                if ( res.code == 1 ) {
                    layer.msg(res.msg, {icon: 2});
                } else if( res.code == 0 ) {
                    layer.msg(res.msg, {icon: 1});
                    $(obj.elem).prop('checked', obj.elem.checked);
                    form.render('checkbox');
                }else if(res.code == 2) {
                    top.location.href = location.href;
                }
            }, 'json');
        });
        
        //启动设备
        function showWork(devid){
            layer.open({
                title: "远程操作", 
                content: '<input type="number" id="workLong" placeholder="请输入 1-90 分钟之内时间" lay-verify="number" class="layui-input"/>', 
                yes: function( index,layero ){
                    var iii = layer.load(1, { shade: [0.1,'#fff'] });
                    var long = $('#workLong').val();
                    if(parseInt(long)<=0 || parseInt(long)>90){
                        layer.alert("设备可运行时间在 1 到 90 分钟 之间! ");
                        layer.close(iii);
                        return;
                    } 
                    $.post( "{:url('device/work')}",
                        {id:devid,type:'start',long:long},
                        function(res){
                            if(res.code===0){
                                layer.close(iii);
                                layer.msg(res.msg);
                            }else if( res.code==1){

                            }
                        },'json');
                }
            });
        }
        
        //停止设备
        function stopWork(devid)
        {
            var iii = layer.load(1, { shade: [0.1,'#fff'] });

            $.post( "{:url('device/work')}",
                {id:devid,type:'stop'},
                function(res){
                    layer.close(iii);
                    if(res.code===0){
                        layer.msg(res.msg);
                    }else if( res.code==1){
                        
                    }
                },'json');
        }

         

    });
</script>

</body>
</html>