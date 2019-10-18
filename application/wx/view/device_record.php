<!DOCTYPE html>
<html>
<head>
<title>北京创世共想科技有限公司</title>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<style>
body{ padding:0px; margin:0px; background-color: #071a5a; color:#ffffff; }
#continer{ padding:5px; margin-bottom: 60px; }

.title{margin: 15px; font-size:22px; font-weight:blod; color:#fff;  }
.title_taocan{color:#fff; }

.tc{ width:100%; height:100px; background-color:#f4f3f9;  text-align: center; display:inline-block; margin-bottom:1em; }
.tc p{ margin:0.5em;}
.devstatus{ color: #FF0; font-size:20px; text-align:center; }

#list{color:#fff; width:100%;}
#list thead{ font-size:14px; }
#list tr { height:35px;}
#list td{ min-width:20px; width:22%; margin-right:3px; }
.ta_l {text-align: left; }
.ta_c {text-align: center; }
.ta_r {text-align: right; }
.fc_7{ color:#777777; }

#page { width:100%; height:20px; margin-top:10px; line-height: 20px; text-align: center; }
#page span{ width:80; line-height: 20px;  }

#nav {width:100%; position:fixed; z-index: 10; bottom: 1px; background-color:#fff}
#nav a{ width:49%; height:40px; display:inline-block; text-align:center; line-height:40px; background-color:#fff; text-decoration:none; }
</style>
</head>
<body>
    <div id="continer" >
        <div><p class="title">量子微磁仪</p></div>
        <hr>
        <div><p class="title_taocan" >设备编码: <span id="devcode">{$dev.imei|default='5201314'}</span> </p></div>
        <div><p class="title_taocan devstatus" >........</p></div>
        <div><p class="title_taocan">记录</p></div>

        <table id="list" >
            <thead><th>订单编号</th><th>来源</th><th>时长</th><th>操作</th></thead>
            <tbody id="list""></tbody>
        </table>

        <div id="page">
            <span class="page" onclick="getlist()" >更多记录</span>
            <input id="curpage" type="hidden" value="1">
        </div>
    </div>

    <div id="nav">
        <a href="{:url('wx_dev_work','id','')}/{$dev.id}"><span>首页</span> </a>
        <a href="javascript: return false;" class="fc_7" ><span>记录</span></a>
    </div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script>
<script>
     
    
    function getlist()
    {
        var pid = parseInt($("#curpage").val());
        if(0==pid){
            return;
        }

        $.post(
            '{:url('device/list')}',
            { page:pid },
            function(res){
                if(res.code === 0){
                        
                    //Object.keys(json).length
                    if(0==res.data.data.length){
                        $(".page").html("没有更多了...");
                        $("#curpage").val(0);
                    }else{
                        //$('#list').html('');
                        $.each( res.data.data, function(key,val){
                            var html = '<tr class="ta_r" ><td class="ta_c dr_id">'+val.id + '</td>';
                            
                            if(0===val.source) {
                                html += '<td class="ta_c" >赠送</td>';
                            } else {
                                html += '<td class="ta_c" >购买</td>';
                            }
                            
                            html += '<td class="ta_c">'+val.long/60 +'分钟</td>' ;
                            
                            if(0===val.status){
                                html += '<td class="ta_c status_'+val.id+'" ><button onclick="run('+val.id+')" >启动</button></td></tr>';
                            } else {
                                //html += '<td><button disable="true" >已使用</button></td></tr>';
                                html += '<td class="ta_c fc_7">已使用</td></tr>';
                            }
                            $('#list').append(html);
                        });

                        
                        $("#curpage").val(pid+1);
                    }

                }else{
                    alert(res.msg);
                }
            }
        );
    }
    
    getlist();
    
    
    //启动设备
    function run(reid)
    {
        $.post(
            '{:url('device/run')}',
            { reid:reid, curdevid:{$dev.id} },
            function(res){
                if(res.code === 0){
                    //alert(res.msg);
                    setTimeout(dr_status,1000, 1000, reid );
                    alert(res.msg); //必须放 setTimeout后面, 这样用户反应时间就可以处理完了
                }else if(res.code===1){
                    alert(res.msg);
                } else {
                    alert('重新登录');
                    //location.href = location.href;
                }
            }
        );
    }

    //查看订单设备状态
    function dr_status(time, reid)
    {
        if(time<5000){
            time = time+1000;
            $.post(
                '{:url('device/drstatus')}',
                { reid:reid },
                function(res){
                    if(res.code === 0){
                        $('.status_'+reid).html('已使用');
                    }else if(res.code===1){
                        setTimeout(dr_status,time, time, reid );
                    }else{
                        //alert('重新登录');
                        //location.href = location.href;
                    }
                }
            );
        }
    }

    //更新设备状态
    function device_status(devid){
        if(0===devid){
            clearInterval(myVar);
        }
        $.post(
            '{:url('device/status')}',
            { devid:devid },
            function(res){
                if(res.code === 0){
                    $('.devstatus').html('设备未在线');
                }else if(res.code===1){
                    $('.devstatus').html('设备在线, 未运行');
                }else if(res.code===2){
                    $('.devstatus').html('设备运行中');
                }
            }
        );
    }

    device_status({$dev.id|default=0})
    var myVar = setInterval('device_status({$dev.id|default=0})', 15000);


</script>
</html>
