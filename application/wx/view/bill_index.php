<!DOCTYPE html>
<html>
<head>
<title>设备收入</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<link rel="stylesheet" href="/static/wx/style.css" >
<link rel="stylesheet" href="/static/wx/example.css" >
<style>
.tip_date{ display:block; width:10em;font-size:12px; }
.data{display:-webkit-box;}
.data li{min-width: 50px; }
.pandnnn{ padding: 50px 16px 0px 16px; }
</style>
</head>
<body ontouchstart >
<div class="container" id="container">

    <div class="page tabbar js_show">
        <div class="page__bd" style="height: 100%;">
            <div class="weui-tab">
                <div class="weui-tab__panel panel_bill" style="display:">

                    <div class="weui-cells__title">今日成功支付: <span class="todayCount">....</span>元</div>
                    <div class="weui-cells todaylist"></div>

                    <div class="weui-cells__title">昨日账单总计: <span class="yestodayCount">....</span>元</div>
                    <div class="weui-cells yestodaylist"></div>

                    <div class="weui-cells__title">历史账单: </div>
                    <div class="weui-cells historylist"></div>
                    <a href="javascript:;" class="weui-btn weui-btn_primary btn_history">暂无数据</a>
                    <input id="curpage" type="hidden" value="1">
                    
                </div>

                
                <div class="weui-tab__panel panel_income" style="display:none;" >
                    <div class="weui-cells__title">历史收入记录: <span class="allIncomeCount">....</span>元</div>
                    <div class="weui-cells income"></div>
                    <a href="javascript:;" class="weui-btn weui-btn_primary btn_income">暂无数据</a>
                    <input id="page_income" type="hidden" value="1">
                </div>

                <div class="weui-tab__panel" style="display:none;" >
                    
                    <div class="pandnnn" >
                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <p>今日成功支付: <span class="todayCount">....</span>元</p>
                                </div>
                            </div>
                        </div>
                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <p>昨日账单总计: <span class="yestodayCount">....</span>元</p>
                                </div>
                            </div>
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <p>昨日收入: <span class="yestodayIncomeCount">....</span>元</p>
                                </div>
                            </div>
                        </div>
                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <p>收入总计: <span class="allIncomeCount">....</span>元</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="weui-cells">
                            <a class="weui-cell weui-cell_access" href="{:url('manage/cashlist')}">
                                <div class="weui-cell__bd">
                                    <p>累计提现: <span class="allCashCount">....</span>元</p>
                                </div>
                                <div class="weui-cell__ft">
                                </div>
                            </a>                             
                        </div>

                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <p>可提现金额: <span class="Cashless">....</span>元</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="pandnnn" >
                        <a href="JavaScript:;" class="weui-btn weui-btn_primary btn_cash">提现</a>
                    </div>
                </div>


                <div class="weui-tabbar">
                    <a href="javascript:;" class="weui-tabbar__item weui-bar__item_on">
                        <span style="display: inline-block;position: relative;">
                            <img src="/static/wx/menu_home_48px.png" alt="" class="weui-tabbar__icon">
                        </span>
                        <p class="weui-tabbar__label">账单</p>
                    </a>
                    <a href="javascript:;" class="weui-tabbar__item">
                        <span style="display: inline-block;position: relative;">
                            <img src="/static/wx/menu_wallet_48px.png" alt="" class="weui-tabbar__icon">
                        </span>
                        <p class="weui-tabbar__label">收入</p>
                    </a>
                    <a href="javascript:;" class="weui-tabbar__item">
                        <img src="/static/wx/menu_bill_48px.png" alt="" class="weui-tabbar__icon">
                        <p class="weui-tabbar__label">账户</p>
                    </a>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="js_dialog" id="iosDialog2" style="opacity: 0;">
        <div class="weui-mask"></div>
        <div class="weui-dialog">
            <div class="weui-dialog__bd"> </div>
            <div class="weui-dialog__ft">
                <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">知道了</a>
            </div>
        </div>
    </div>
</div>
<input id="isloading" type="hidden" value="0">
<input id="isloading2" type="hidden" value="0">
</body>


<script type="text/javascript" src="/static/js/zepto.min.js"></script>
<!--script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script-->
<!--script type="text/javascript" src="https://res.wx.qq.com/open/libs/weuijs/1.2.1/weui.min.js"></script-->
<!--script type="text/javascript" src="https://weui.io/example.js"></script-->
<script type="text/javascript" class="tabbar js_show">
$(function(){
    
    $('.weui-tabbar__item').on('click', function () {
        $(this).addClass('weui-bar__item_on').siblings('.weui-bar__item_on').removeClass('weui-bar__item_on');
        var index = $(this).index();
        $('.weui-tab__panel').hide();
        $.each($('.weui-tab__panel'),function(k,d){
            if(k==index) $(this).show();
        })
    });


    //今日
    $.ajax({
        url:'{:url('manage/todaylist')}',
        type:'post',
        success:function(e){
            if(e.code===0)
            {
                var pay_status='<li>未知</li>';
                var pay_price = '';
                $.each(e.data,function(k,d){

                    if(1==d.status) pay_status='<li class="link">已支付</li>';
                    if(0==d.status) pay_status='<li >未支付</li>';
                    pay_price = '<li style="text-align:center;">'+d.price/100+'</li>';

                    $('.todaylist').append('<div class="weui-cell"><ul class="data"><li><p class="tip_date">'+d.createtime+'</p></li>'+pay_status+pay_price+'<li>'+d.nickname+'</li></ul></div>');
                })
            }
        }
    });

    //昨日
    $.ajax({
        url:'{:url('manage/yestodaylist')}',
        type:'post',
        success:function(e){
            if(e.code===0)
            {
                var pay_status='<li>未知状态</li>';
                var pay_price = '';
                $.each(e.data,function(k,d){

                    if(1==d.status) pay_status='<li class="link">已支付</li>';
                    if(0==d.status) pay_status='<li >未支付</li>';

                    pay_price = '<li style="text-align:center;">'+d.price/100+'</li>';

                    $('.yestodaylist').append('<div class="weui-cell"><ul class="data"><li><p class="tip_date">'+d.createtime+'</p></li>'+pay_status+pay_price+'<li>'+d.nickname+'</li></ul></div>');
                })
            }
        }
    });

    //统计数据
    $.get(
        '{:url('manage/count')}',
        function (res) {
            $(".todayCount").html(res.data.tc/100);
            $(".yestodayCount").html(res.data.yc/100);
            $(".yestodayIncomeCount").html(res.data.iyc/100);
            $(".allIncomeCount").html(res.data.iac/100);
            $(".allCashCount").html(res.data.ema/100);  //已提现金额

            //$(".less").html( (res.data.iac-res.data.ema)/100 );  //可申请提现金额
            $(".Cashless").html( (res.data.iac-res.data.ema)/100 );  //可申请提现金额
        },
        'json'
    );

    
    //流式加载历史
    $('.panel_bill').scroll(function () {
        var contentH = $(this).get(0).scrollHeight; //所要滑动的元素内容的高度，包括可见部分以及滚动条下面的不可见部分。
        var viewH = $(this).height();   //看到的这个DIV的高度，不包括可见部分也不包括滚动条下面的不可见部分
        var scrollTop = $(this).scrollTop();    //滚动条距离顶部的距离
        if(contentH-viewH-scrollTop <200){
            history();
        }
        //console.log('view'+viewH+' srolltop:'+scrollTop+' contentH:'+contentH);
        //console.log(contentH-viewH-scrollTop);
    });


    function history()
    {
        var loading = parseInt($("#isloading").val());
        if(1==loading){
            return;
        }
        $("#isloading").val(1);

        var pid = parseInt($("#curpage").val());
        if(0==pid){
            return;
        }

        $('.btn_history').addClass('weui-btn_loading').html('加载中...'); 
        $.ajax({
            url:'{:url('manage/historylist')}'+'?page='+pid,
            type:'post',
            success:function(e){
                if(e.code===0)
                {
                    var pay_status='<li>未知</li>';
                    var pay_price = '';
                    $.each(e.data.data,function(k,d){

                        if(1==d.status) pay_status='<li class="link">已支付</li>';
                        if(0==d.status) pay_status='<li >未支付</li>';

                        pay_price = '<li style="text-align:center;">'+d.price/100+'</li>';

                        $('.historylist').append('<div class="weui-cell"><ul class="data"><li><p class="tip_date">'+d.createtime+'</p></li>'+pay_status+pay_price+'<li>'+d.nickname+'</li></ul></div>');
                    })
                    $("#curpage").val(pid+1);
                    
                }

                if(e.data.size > e.data.data.length){
                    //$('.btn_history').removeClass('weui-btn_loading').html('没有了');
                    $('.btn_history').hide();
                    $("#curpage").val(0);
                }

                $("#isloading").val(0);  
            },
            error: function()
            {
                $("#isloading").val(0);  
            }
        });
    }

    history();



    //流式加载收入列表
    $('.panel_income').scroll(function () {
        var contentH = $(this).get(0).scrollHeight; //所要滑动的元素内容的高度，包括可见部分以及滚动条下面的不可见部分。
        var viewH = $(this).height();   //看到的这个DIV的高度，不包括可见部分也不包括滚动条下面的不可见部分
        var scrollTop = $(this).scrollTop();    //滚动条距离顶部的距离
        if(contentH-viewH-scrollTop <200){
            income();
        }
        //console.log('view'+viewH+' srolltop:'+scrollTop+' contentH:'+contentH);
        //console.log(contentH-viewH-scrollTop);
    });

    /**
     * 收入列表
     */
    function income()
    {
        console.log('income');
        var loading = parseInt($("#isloading2").val());
        console.log('isloading2:'+loading);
        if(1==loading){
            return;
        }
        $("#isloading2").val(1);

        var pid = parseInt($("#page_income").val());
        if(0==pid){
            return;
        }
        console.log('pid:'+pid);
        $('.btn_income').addClass('weui-btn_loading').html('加载中...'); 
        $.ajax({
            url:'{:url('manage/income')}'+'?page='+pid,
            type:'post',
            success:function(e){

                $("#isloading2").val(0); 

                if(e.code===0)
                {
                    var pay_type='<li >未知</li>';
                    var pay_price = '';
                    $.each(e.data.data,function(k,d){

                        if(1==d.type) pay_type='<li>收入</li>';
                        if(2==d.type) pay_type='<li >提成</li>';

                        pay_price = '<li style="text-align:center;">'+d.money/100+'元</li>';

                        $('.income').append('<div class="weui-cell"><p class="tip_date">'+d.time+'</p><ul class="data">'+pay_type+pay_price+'</ul></div>');
                    })
                    $("#page_income").val(pid+1);
                }

                if(e.data.size > e.data.data.length)
                {
                    //$('.btn_income').removeClass('weui-btn_loading').html('没有了');
                    $('.btn_income').hide();
                    $("#page_income").val(0);
                }

            },
            error:function(e){
                $("#isloading2").val(0); 
            }
        });
    }

    income();
    
    $('.btn_cash').on('click',function(){
        var cashed = $('.allCashCount').html();
        var income = $('.allIncomeCount').html();
        console.log('已提现:'+cashed);
        console.log('收入:'+income);
        if( parseInt(income) - parseInt(cashed) > 500 ) {
            location.href = "{:url('manage/cash')}";
        } else {
            console.log('钱不够');
            $('#iosDialog2 .weui-dialog__bd').html('不足500元不能提现');
            $('#iosDialog2').fadeIn(200);
            $('#iosDialog2 .weui-dialog__btn').on('click',function(){
                $('#iosDialog2').fadeOut(200);
            });
        }
    });

});
 
    
</script>
</html>
