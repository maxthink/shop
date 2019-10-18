<?php /*a:1:{s:55:"/data/www/csgx2/application/wx/view/manage_cashlist.php";i:1567067584;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<title>提现记录</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<link rel="stylesheet" href="/static/wx/style.css" >
<link rel="stylesheet" href="/static/wx/example.css" >
<style>
.tip_date{ display:block; width:140px;font-size:12px; }
.data{display:-webkit-box;}
.data li{min-width: 60px; }
.pandnnn{ padding: 50px 16px; }
</style>
</head>
<body ontouchstart >
<div class="container" id="container">

    <div class="page tabbar js_show">
        <!--div class="page__hd">
            <h1 class="page__title">提现记录</h1>
            <p class="page__desc">总计: <span class="allIncomeCount">....</span>元</p>
        </div-->
        <div class="page__bd " style="height: 100%;">
            
            <div class="cashlist" ></div>
            <a href="javascript:;" class="btn_load" style="background-color:#0093D3;font-weight:normal;width:100%;padding:8px 0;text-align:center;display:block;color:#fff;">暂无数据</a>
            <input id="page_cashlist" type="hidden" value="1">
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
</body>

<script type="text/javascript" src="/static/js/zepto.min.js"></script>
<!--script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script-->
<!--script type="text/javascript" src="https://res.wx.qq.com/open/libs/weuijs/1.2.1/weui.min.js"></script-->
<!--script type="text/javascript" src="https://weui.io/example.js"></script-->
<script type="text/javascript" class="tabbar js_show">
$(function(){
    
   
    //流式加载收入列表
    $('.cashlist').scroll(function () {
        var contentH = $(this).get(0).scrollHeight; //所要滑动的元素内容的高度，包括可见部分以及滚动条下面的不可见部分。
        var viewH = $(this).height();   //看到的这个DIV的高度，不包括可见部分也不包括滚动条下面的不可见部分
        var scrollTop = $(this).scrollTop();    //滚动条距离顶部的距离
        if(contentH-viewH-scrollTop <200){
            cashlist();
        }
    });

    /**
     * 收入列表
     */
    function cashlist()
    {
        console.log('cashlist');
        var loading = parseInt($("#isloading").val());
        console.log('isloading:'+loading);
        if(1==loading){
            return;
        }
        $("#isloading").val(1);

        var pid = parseInt($("#page_cashlist").val());
        if(0==pid){
            return;
        }
        console.log('pid:'+pid);
        //$('.btn_load').addClass('weui-btn_loading').html('加载中...'); 
        $.ajax({
            url:'<?php echo url('manage/cashlist'); ?>'+'?page='+pid,
            type:'post',
            success:function(e){

                $("#isloading").val(0); 

                if(e.code===0)
                {
                    var _html='';
                    var comment='无';
                    $.each(e.data.data,function(k,d){

                        _html='<div class="weui-form-preview">'
                        +'<div class="weui-form-preview__hd"><div class="weui-form-preview__item"><label class="weui-form-preview__label">提现金额</label><em class="weui-form-preview__value">¥'+d.money/100+'</em></div></div>'
                        +'<div class="weui-form-preview__bd"><div class="weui-form-preview__item"><label class="weui-form-preview__label">手续费</label><span class="weui-form-preview__value">¥'+d.service/100+'</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">实际到账</label><span class="weui-form-preview__value">¥'+d.money_true/100+'</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">提现状态:</label><span class="weui-form-preview__value">';

                        if(<?php echo htmlentities($Mcash::CASH_APPLY); ?>==d.status){
                            _html+='申请已提交,等待审核';
                            if(d.comment!=null) comment=d.comment;
                        }
                        if(<?php echo htmlentities($Mcash::CASH_FINANCE); ?>==d.status){
                            _html+='申请已提交,等待审核';
                            if(d.comment!=null) comment=d.comment;
                        }
                        if(<?php echo htmlentities($Mcash::CASH_PASS); ?>==d.status){
                            _html+='审核通过,等待发票';
                            if(d.comment!=null) comment=d.comment;
                        } 
                        if(<?php echo htmlentities($Mcash::CASH_REFUSE); ?>==d.status){
                            _html+='驳回';
                            if(d.comment!=null) comment=d.comment;
                        } 
                        if(<?php echo htmlentities($Mcash::CASH_PAYED); ?>==d.status){
                            _html+='已打款, 请查收';
                            if(d.comment!=null) comment=d.comment;
                        } 

                        _html+= '</span></div><div class="weui-form-preview__item"><label class="weui-form-preview__label">操作时间:</label><span class="weui-form-preview__value">'+d.time+'</span></div><div class="weui-form-preview__item" style="padding-top:10px;font-size:16px;margin-top:5px;border-top:1px solid #f3f3f3;"><label class="weui-form-preview__label">备注:</label><span class="weui-form-preview__value">'+comment+'</span></div></div></div><br/>';

                        $('.cashlist').append(_html);
                        comment='无';
                    })
                    $("#page_cashlist").val(pid+1);
                    
                }

                if(e.data.size > e.data.data.length){
                    
                    if(pid==1){
                        $('.btn_load').show();
                    }else{                        
                        $('.btn_load').removeClass('weui-btn_loading').html('没有更多了');
                    }
                    
                    $("#page_cashlist").val(0);
                }

            },
            error:function(e){
                $("#isloading").val(0); 
            }
        });
    }

    cashlist();
     
    

});
 
function formatDate(unix) {
    var tt = new Date(unix); 

    //return tt.toLocaleString();
    
    return tt.getFullYear()+'-'+tt.getMonth()+'-'+tt.getDate()+' '+tt.getHours()+':'+tt.getMinutes() ;

}
    
</script>
</html>
