<?php /*a:1:{s:51:"/data/www/csgx2/application/wx/view/bill2_index.php";i:1563175478;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<title>订单列表</title>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<link rel="stylesheet" href="/static/layui/css/layui.css" >
<link rel="stylesheet" href="/static/global.css" >
<script src="/static/layui/layui.js"></script>
<style>
body{ padding:0px; margin:0px; background-color: #071a5a; color:#ffffff; font-size:14px; }
</style>
</head>
<body class="layui-box" >

    <div id="continer" class="layui-tab " >
        <ul class="layui-tab-title" style="z-index:9999; background-color:#071a5a ">
            <li class="layui-this" >今日账单</li>
            <li>昨日账单</li>
            <li>收入</li>
            <li>统计</li>
            <li>提现记录</li>
        </ul>
        <div class="layui-tab-content" >
            <div class="layui-tab-item layui-show">
                <p class="">今日成功支付: <span class="todayCount">....</span>元</p>
                <table id="todaylist" class="layui-table" ></table>
                <p class="">&nbsp; </p>
            </div>
            <div class="layui-tab-item" >
                <p class="">昨日账单总计: <span class="yestodayCount">....</span>元</p>
                <table id="yestodaylist" class="layui-table" > </table>
                <p class="">&nbsp; </p>
            </div>
            <div class="layui-tab-item" >
                
                <div class="layui-row grid-demo  layui-bg-black">今日账单总计<span class="todayCount" ></span></div>

                <div class="layui-row">
                    <div class="layui-col-xs6 layui-col-sm6 grid-demo layui-bg-red" >昨日账单总计<span class="todayCount" ></span></div>
                    <div class="layui-col-xs6 layui-col-sm6 grid-demo layui-bg-red">昨日收入<span class="yestodayCount" ></span></div>
                </div>
 
                <div class="layui-row grid-demo  layui-bg-black">总计收入: <span class="allCount" ></span></div>

                <div class="layui-row">
                    <div class="layui-col-xs6 layui-col-sm6 grid-demo" > 已提现: <span class="exchange" ></span> </div>
                    <div class="layui-col-xs6 layui-col-sm6 grid-demo"> 可提现金额: <span class="less" ></span> </div>
                </div>
                 
                <div class="layui-row grid-demo  layui-bg-yellow"> <input name="money" class="less" /> </div>
            </div>
            <div class="layui-tab-item" >
                <table id="caselist" class="layui-table" ></table>

                
            </div>
        </div>

         

    </div>
    
</body>
<!--script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script-->
<script>
     
    layui.use(['table','element','jquery'], function(){
        //var table = layui.table;  
        var element = layui.element;
        var $ = layui.$ //重点处
        

        layui.table.render({
            elem: '#todaylist'
            ,url: '<?php echo url('bill1/todaylist'); ?>'
            ,limit:15
            ,cols: [[ //表头
                    { field: 'code', title: '订单编码' , minWidth:180 },
                    { field: 'status', title: '订单状态', templet:function(d){
                        if(1==d.status) return '支付完成';
                        if(0==d.status) return '未支付';
                        } ,minWidth:100
                    },
                    { field: 'price', title: '价格', templet:function(d){
                        return d.price/100;
                    }  }
                    ]]
            
        });

        layui.table.render({
            elem: '#yestodaylist'
            ,url: '<?php echo url('bill1/yestodaylist'); ?>'
            ,limit:15
            ,cols: [[ //表头
                    { field: 'code', title: '订单编码' , minWidth:180 },
                    { field: 'status', title: '订单状态', templet:function(d){
                        if(1==d.status) return '支付完成';
                        if(0==d.status) return '未支付';
                        } ,minWidth:100
                    },
                    { field: 'price', title: '价格', templet:function(d){
                        return d.price/100;
                    }  }
                    ]]
            
        });

        function showCount()
        {
            $.get(
                '<?php echo url('bill1/count'); ?>',
                function (res) {
                    $(".todayCount").html(res.data.tc/100);
                    $(".yestodayCount").html(res.data.yc/100);
                    $(".allCount").html(res.data.ac/100);
                },
                'json'
            );
        }

        showCount();
    
    });   
    
</script>
</html>
