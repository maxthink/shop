<?php /*a:1:{s:49:"/data/www/csgx2/application/wx/view/bill_test.php";i:1564476731;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<title>订单列表</title>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<style>
body{ padding:0px; margin:0px; background-color: #071a5a; color:#ffffff; font-size:14px; }
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
        <div><p class="title">账单记录</p></div>
        <hr>
        <table id="list" >
            <thead><th>支付人</th><th>支付状态</th><th>价格</th><th>下单时间</th></thead>
            <tbody id="list""></tbody>
        </table>

        <div id="page">
            <span class="page" onclick="getlist()" >更多记录</span>
            <input id="curpage" type="hidden" value="1">
        </div>
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
            '<?php echo url('bill2/list'); ?>',
            { page:pid },
            function(res){
                if(res.code === 0){
                        
                    //Object.keys(json).length
                    if( 0 < res.data.data.length){
                        
                        //$('#list').html('');
                        $.each( res.data.data, function(key,val){
                            var html = '<tr class="ta_r" ><td class="ta_c dr_id">'+val.nickname + '</td>';
                            
                            if(1===val.status) {
                                html += '<td class="ta_c" >支付成功</td>';
                            } else {
                                html += '<td class="ta_c" >下单未支付</td>';
                            }
                            
                            html += '<td class="ta_c">'+val.price/100 +'</td>' ;
                            
                            html += '<td class="ta_c">'+val.createtime +'</td>' ;
 
                            html += '</tr>';
                            $('#list').append(html);
                        });

                        
                        $("#curpage").val(pid+1);
                    }

                    if(res.data.size > res.data.data.length){
                        $(".page").html("没有更多了...");
                        $("#curpage").val(0);
                    }

                }else{
                    alert(res.msg);
                }
            }
        );
    }
    
    getlist();
    
    
    
</script>
</html>
