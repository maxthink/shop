<?php /*a:1:{s:52:"/data/www/csgx2/application/wx/view/manage_index.php";i:1567070399;}*/ ?>
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
.weui-cell{font-size:14px;}
.weui-cell .nickname{overflow: hidden;text-overflow:ellipsis;white-space: nowrap;padding-right:6px;width:60px;display:inline-block;}
.tip_date{position: absolute;right:10px;color:#bababa;}
.data{display:-webkit-box;font-size:14px;}
.data li{min-width: 50px;}
/*.data li:nth-child(2){font-size:16px;}*/
.pandnnn{ padding: 0; }
.weui-tab__panel.panel_bill{padding:4% 2%;overflow:hidden;height:100%;}
/*.weui-cell_access{padding:40px 16px;}*/
.order-detail{position:fixed;top:45px;left:0;width:100%;height:calc(100% - 43px);transition:transform .2s ease-out;transform:translateX(100%);margin-top:0;z-index:10;overflow:hidden;overflow-y: scroll;}
.order-detail.active{transform:translateX(0);}
.order-header{position: fixed;top:0;left:0;width:100%;background-color:#0093D3;color:#fff;line-height:45px;display:none;z-index:11;height:45px;}
.order-header .back-btn{padding:0 14px;font-size:18px;}
.order-header .header-title{position: absolute;top:0;left:40px;right:40px;font-size:16px;font-weight:normal;text-align:center;}
.text-name{width:120px;display:inline-block;font-size:15px;}
.weui-cell .data span,.weui-cells.income span{color:#bababa;}
.dev-header{background-color:#0093D3;color:#fff;padding:12px 15px;font-size:16px;}
</style>
</head>
<body ontouchstart >
<div class="container" id="container">

    <div class="page tabbar js_show">
        <div class="page__bd" style="height: 100%;">
            <div class="weui-tab">
                    <div class="order-header" id="order-header">
                        <span class="back-btn"><</span>
                        <h1 class="header-title">账单列表</h1>
                    </div>
                <div class="weui-tab__panel panel_bill" id="orderItemLists">
                    <div class="weui-cells">
                        <a class="weui-cell weui-cell_access" href="javascript:;" data-name="todaylist" data-title="今日支付">
                            <div class="weui-cell__bd">
                                <p><span class="text-name">今日成功支付:</span>￥<span class="todayCount">....</span>元</p>
                            </div>
                            <div class="weui-cell__ft"></div>
                        </a>
                    </div>
                    <div class="weui-cells" style="margin-top:18px;">
                        <a class="weui-cell weui-cell_access" href="javascript:;" data-name="yestodaylist" data-title="昨日账单">
                            <div class="weui-cell__bd">
                                <p><span class="text-name">昨日账单总计:</span>￥<span class="yestodayCount">....</span>元</p>
                            </div>
                            <div class="weui-cell__ft"></div>
                        </a>
                    </div>
                    <div class="weui-cells" style="margin-top:18px;">
                        <a class="weui-cell weui-cell_access" href="javascript:;" data-name="historylist" data-title="历史账单">
                            <div class="weui-cell__bd">
                                <p><span class="text-name">历史账单:</span></p>
                            </div>
                            <div class="weui-cell__ft"></div>
                        </a>
                    </div>

                    <div class="weui-cells order-detail" id="todaylist"></div>
                    <div class="weui-cells order-detail" id="yestodaylist"></div>
                    <div class="weui-cells order-detail" id="historylist"></div>
                    <!-- <a href="javascript:;" class="weui-btn weui-btn_primary btn_history">暂无数据</a> -->
                    <input id="curpage" type="hidden" value="0">
                    
                </div>

                
                <div class="weui-tab__panel panel_income" style="display:none;" >
                    <div class="order-header" style="display:block;">
                        <!-- <span class="back-btn"><</span> -->
                        <h1 class="header-title">历史收入记录: <span class="allIncomeCount">....</span>元</h1>
                    </div>
                    <div class="weui-cells__title"></div>
                    <div class="weui-cells income" style="width:100%;position:relative;top:29px;bottom:60px;"></div>
                    <a href="javascript:;" class="weui-btn weui-btn_primary btn_income">暂无数据</a>
                    <input id="page_income" type="hidden" value="1">
                </div>

                <div class="weui-tab__panel" style="display:none;" >
             
                    <div class="pandnnn" >
                        <div class="dev-header">我的推荐ID: <?php echo htmlentities($userid); ?></div>
                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <p><span class="text-name">今日成功支付:</span>￥<span class="todayCount">....</span>元</p>
                                </div>
                            </div>
                        </div>
                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <p><span class="text-name">昨日账单总计:</span>￥<span class="yestodayCount">....</span>元</p>
                                </div>
                            </div>
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <p><span class="text-name">昨日收入:</span>￥<span class="yestodayIncomeCount">....</span>元</p>
                                </div>
                            </div>
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <p><span class="text-name">收入总计:</span>￥<span class="allIncomeCount">....</span>元</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="weui-cells">
                            <a class="weui-cell weui-cell_access" href="<?php echo url('manage/cashlist'); ?>">
                                <div class="weui-cell__bd">
                                    <p><span class="text-name">累计提现:</span>￥<span class="allCashCount">....</span>元</p>
                                </div>
                                <div class="weui-cell__ft">
                                </div>
                            </a>                             
                        </div>

                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <p><span class="text-name">可提现金额:</span>￥<span class="Cashless">....</span>元</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pandnnn" style="padding:2% 4%;" >
                        <a href="JavaScript:;" class="weui-btn weui-btn_primary weui-btn_block btn_cash" style="background-color:#519DDA;border-radius:100px;" >提现</a>
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
            $('#order-header').hide();
            $('.order-detail').removeClass('active')
            if(k==index) $(this).show();
        })
    });

    $('#orderItemLists').on('click', 'a',function(){
        let _this=$(this),item = $(this).data('name'),
            itemDetail = '';
            $('#'+item).addClass('active').html('');
            $('#order-header').show().find('h1').text(_this.data('title'));
            if(item !=='historylist'){
            //今日//昨日
                $.ajax({
                    url:"<?php echo url('manage/"+item+"'); ?>",
                    type:'post',
                    success:function(e){
                        if(e.code===0){
                            
                            var pay_status='<li>未知状态</li>';
                            var pay_price = '';
                            $.each(e.data,function(k,d){
                                if(1==d.status) pay_status='<li class="link">已支付</li>';
                                if(0==d.status) pay_status='<li>未支付</li>';
                                pay_price = '<li style="text-align:center;">￥'+d.price/100+'</li>';

                                $('#'+item).append('<div class="weui-cell"><ul class="data"><span class="nickname">'+d.nickname+'</span>'+pay_status+pay_price+'</ul><span class="tip_date">'+d.createtime+'</span></div>');
                            })
                        }
                    }
                });
            }else{
                $("#curpage").val(1);
                history();
            }
    })
    let backBtn = document.querySelector('.back-btn');
        backBtn.addEventListener('click',function(){
        $('.order-detail').removeClass('active');
        $('#order-header').hide()
    })
    //统计数据
    $.get(
        '<?php echo url('manage/count'); ?>',
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
    $('#historylist').scroll(function () {
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
        // if(0==pid){
        //     return;
        // }
        $('.btn_history').addClass('weui-btn_loading').html('加载中...'); 
        $.ajax({
            url:'<?php echo url('manage/historylist'); ?>'+'?page='+pid,
            type:'post',
            success:function(e){
                if(e.code===0)
                {
                    document.querySelector('#order-header').style.display='block';
                    var pay_status='<li>未知</li>';
                    var pay_price = '';
                    $.each(e.data.data,function(k,d){
                        if(1==d.status) pay_status='<li class="link">已支付</li>';
                        if(0==d.status) pay_status='<li >未支付</li>';

                        pay_price = '<li style="text-align:center;">￥'+d.price/100+'</li>';

                        $('#historylist').append('<div class="weui-cell"><ul class="data"><span class="nickname">'+d.nickname+'</span>'+pay_status+pay_price+'</ul><span class="tip_date">'+d.createtime+'</span></div>');
                    })
                    $("#curpage").val(pid+1);
                }

                if(e.data.size > e.data.data.length){
                    //$('.btn_history').removeClass('weui-btn_loading').html('没有了');
                    $('.btn_history').hide();
                    // $("#curpage").val(0);
                }

                $("#isloading").val(0);  
            },
            error: function()
            {
                $("#isloading").val(0);  
            }
        });
    }




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
            url:'<?php echo url('manage/income'); ?>'+'?page='+pid,
            type:'post',
            success:function(e){

                $("#isloading2").val(0); 

                if(e.code===0)
                {
                    var pay_type='<li >未知</li>';
                    var pay_price = '';
                    $.each(e.data.data,function(k,d){

                        if(1==d.type) pay_type='<span>收入</span>';
                        if(2==d.type) pay_type='<li >提成</li>';
                        if(4==d.type) pay_type='<li >补贴</li>';

                        pay_price = '<li style="text-align:center;">￥'+d.money/100+'元</li>';
                        k++
                        $('.income').append('<div class="weui-cell"><span style="font-size:12px;padding-right:8px;">'+k+'</span><ul class="data">'+pay_type+pay_price+'</ul><span class="tip_date">'+d.time+'</span></div>');
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
            location.href = "<?php echo url('manage/cash'); ?>";
            return;
        if( parseInt(income) - parseInt(cashed) > 500 ) {
            location.href = "<?php echo url('manage/cash'); ?>";
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
