<!DOCTYPE html>
<html>
<head>
<title>提现</title>
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
.weui-input{width:90%;border-left:none;border-top:none;border-right:none; }
.weui-label{width:70px;}
.weui-toast{top:230px; min-height:2em;}
.weui-cells__title{margin-top:5px;}
.weui-cell{font-size:14px;}
.weui-half-screen-dialog{border-radius:0;}
.weui-half-screen-dialog__hd{height:auto;line-height:50px;font-size:16px;}
#cash_account_from .weui-cell{padding:8px 16px;font-size:14px;}
.edit-btn{position: absolute;top:0;right:0;padding:13px;z-index: 10}
</style>
</head>
<body ontouchstart >
<div class="container" id="container">

    <div class="page js_show">
        <div class="page__bd" style="height: 100%;">
            <div class="weui-tab">
                
                <div class="weui-tab__panel" >
                    
                    <div class="pandnnn account" >
                        <!-- <div class="weui-cells__title">收款账户:</div> -->
                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__bd show_account">
                                    对公账户:北京**** 有限公司
                                    <br>对公账号: 493948493223344322
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="weui-panel weui-panel_access">
                        <div class="weui-panel__bd">
                            <li class="weui-media-box weui-media-box_appmsg" style="padding-bottom:10px;">
                                <div class="weui-media-box__bd">
                                    <h4 class="weui-media-box__title">体现金额</h4>
                                        <div class="weui-cell__bd weui-media-box__desc" style="padding-top:15px;font-size:18px;color:#333;">
                                            <span style="color:#ccc;">￥</span>
                                            <input type="number" name="cash_money" class="weui-input cash_money" type="text" placeholder="500起,每次至少加100">
                                        </div>
                                        <!-- <div class="cash_tip" style="color:#ff3300;font-size:14px;padding-top:6px;"></div> -->
                                </div>
                            </li>
                        </div>
                        <div class="weui-panel__ft">
                            <p class="weui-cell cash_tip" style="font-size:16px;color:#ff3300;padding:10px 16px;"></p>    
                        </div>

                    </div>
                    <div class="weui-cells__title">( 500元起提,每次至少加100, 提现服务费2000元以下按2%收取, 2000元(含)以上按1%收取 )</div>
                    <div class="pandnnn" >
                        <div class="weui-cells weui-cells_checkbox">
                            <label class="weui-cell weui-check__label" for="s11">
                                <div class="weui-cell__hd">
                                    <input type="checkbox" class="weui-check cash_check" name="cash_check" id="s11" >
                                    <i class="weui-icon-checked"></i>
                                </div>
                                <div class="weui-cell__bd tip_check">
                                    <p>我方确认以北京创世共想基因科技有限公司审核确认的金额为准</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="pandnnn" style="padding:10px 15px;">
                        <a href="JavaScript:;" class="weui-btn weui-btn_primary weui-btn_block btn_cash" style="background-color:#519DDA;border-radius:100px;">提现</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="page msg_success js_show" style="display:none;" >
        <div class="weui-msg">
            <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg" style="color:#519DDA;"></i></div>
            <div class="weui-msg__text-area">
                <h2 class="weui-msg__title">操作成功</h2>
                <p class="weui-msg__desc">提现申请已提交成功,请等待审核.</p>
            </div>
            <div class="weui-msg__opr-area">
                <p class="weui-btn-area" style="padding:0 5%;">
                    <a href="javascript:history.back();" class="weui-btn weui-btn_primary weui-btn_block btn_cash" style="background-color:#519DDA;border-radius:100px;">返回首页</a>
                </p>
            </div>
            <div class="weui-msg__tips-area">
              <p class="weui-msg__tips"></p>
            </div>
            <div class="weui-msg__extra-area">
                <div class="weui-footer">
                    <p class="weui-footer__links">
                        <a href="javascript:void(0);" class="weui-footer__link">北京创世共想基因科技有限公司</a>
                    </p>
                    <p class="weui-footer__text">Copyright © 2008-2019 </p>
                </div>
            </div>
        </div>
    </div>
    <div class="page msg_warn js_show" style="display:none;" >
        <div class="weui-msg">
            <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg"></i></div>
            <div class="weui-msg__text-area">
                <h2 class="weui-msg__title">操作失败</h2>
                <p class="weui-msg__desc">提现申请提交失败... </a></p>
            </div>
            <div class="weui-msg__tips-area">
              <p class="weui-msg__tips">  </p>
            </div>
            <div class="weui-msg__opr-area">
                <p class="weui-btn-area">
                    <a href="javascript:history.back();" class="weui-btn weui-btn_default">返回首页</a>
                </p>
            </div>
            <div class="weui-msg__extra-area">
                <div class="weui-footer">
                    <p class="weui-footer__links">
                        <a href="javascript:void(0);" class="weui-footer__link">北京创世共想基因科技有限公司</a>
                    </p>
                    <p class="weui-footer__text">Copyright © 2008-2019 </p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="js_dialog" id="iosDialog3" style="opacity: 0;">
        <div class="weui-mask"></div>
        <div class="weui-half-screen-dialog" style="padding:0 0 4%">
            <div class="weui-half-screen-dialog__hd" style="padding:0 4%;background-color:#f5f5f5;">
                <div class="weui-half-screen-dialog__hd__main">
                    <strong class="weui-half-screen-dialog__title" style="text-align:center;">选择到账对公账户</strong>
                </div>
            </div>
            <div class="weui-half-screen-dialog__bd">
                <div class="weui-cells list_account_ssss">账户列表处</div>
            </div>
        </div>
    </div>
    <div class="js_dialog" id="Dialog_newAcount" style="opacity: 0;">
        <div class="weui-mask"></div>
        <div class="weui-half-screen-dialog" style="padding:0 0 4%">
            <div class="weui-half-screen-dialog__hd">
              <div class="weui-half-screen-dialog__hd__main" style="padding:0 4%;background-color:#f5f5f5;">
                <p class="weui-half-screen-dialog__title" style="text-align:center;">对公账户</p>
              </div>
            </div>
            <div class="weui-half-screen-dialog__bd">
                <div class="weui-cells weui-cells_form">
                    <form id="cash_account_from">
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">账户名:</label></div>
                        <div class="weui-cell__bd">
                            <input name="name" class="weui-input account_input_name" type="text"  maxlength="30"  placeholder="对公账户户名">
                        </div>
                    </div>
                    <div class="weui-cell" style="border-bottom:5px solid #f5f5f5;">
                        <div class="weui-cell__hd"><label class="weui-label">开户行:</label></div>
                        <div class="weui-cell__bd">
                            <input name="bank" class="weui-input account_input_bank" type="text" maxlength="30"  placeholder="对公账户开户银行全称">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">账户号:</label></div>
                        <div class="weui-cell__bd">
                            <input name="account" class="weui-input account_input_account" type="number" maxlength="20" placeholder="银行账号">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">纳税号:</label></div>
                        <div class="weui-cell__bd">
                            <input name="tax" class="weui-input account_input_tax" type="text" maxlength="20" placeholder="纳税人识别号">
                        </div>
                    </div>
                    <input name="id" class="weui-input account_id" type="hidden">
                    </form>                         
                </div>
            </div>
            <div class="weui-half-screen-dialog__ft" style="padding:15px;">
                <a href="javascript:;" class="weui-btn weui-btn_default btn_account_cancel">取消</a>
                <a href="javascript:;" class="weui-btn weui-btn_primary btn_account_ok">确认</a>
            </div>
        </div>
    </div>
    <div class="js_dialog" id="iosDialog2" style="opacity: 0;">
        <div class="weui-mask"></div>
        <div class="weui-dialog">
            <div class="weui-dialog__bd">弹窗内容，告知当前状态、信息和解决方法</div>
            <div class="weui-dialog__ft">
                <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">知道了</a>
            </div>
        </div>
    </div>

    <div id="toast" style="opacity: 0; display: none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <!--i class="weui-icon-success-no-circle weui-icon_toast"></i-->
            <p class="weui-toast__content">已完成</p>
        </div>
    </div>

</div>

<input id="less" type="hidden" value="0">
<input id="account" type="hidden" value="0">
</body>


<script type="text/javascript" src="/static/js/zepto.min.js"></script>
<!--script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script-->
<!--script type="text/javascript" src="https://res.wx.qq.com/open/libs/weuijs/1.2.1/weui.min.js"></script-->
<!--script type="text/javascript" src="https://weui.io/example.js"></script-->
<script type="text/javascript" class="tabbar js_show">
$(function(){
    var less = 0;
    //页面初始显示数据
    $.get(
        '{:url('manage/cashinit')}',
        function (e) {
            if(e.code===0){
                $('#less').val(e.data.less/100);
                if( e.data.less <= 0 )
                {
                    $('.btn_cash').addClass('weui-btn_disabled');
                }else{
                    //var less = parseInt(e.data.less/100);
                    //less =  less - less%100;
                    less = e.data.less/100;
                    $('.cash_tip').html('账户剩余金额: '+less+'元');
                    $('#less').val(less);
                }

                if( e.data.account != null)
                {
                    $('#account').val(e.data.account.id);
                    $('.show_account').html( e.data.account.name+"<br/>账户: "+e.data.account.account  );
                } else {
                    $('.show_account').html(  '没有账户, 请点击此处添加账户' );
                }
            }
        },'json'
    );

    //mask层点击, 父层js_dialog消失
    $('.weui-mask').on('click', function(){
        console.log('mask click ');
        $(this).parents('.js_dialog').fadeOut(200);
    });
    
    //页面上的账户点击, 显示账户列表
    $('.account').on('click', function(){
        $('#iosDialog3').fadeIn(200);
        $.get("{:url('manage/account_list')}",function(e){
            if(0===e.code){
                $('.list_account_ssss').html('');
                $.each(e.data, function(k,d){
                    $('.list_account_ssss').append("<div class='weui-cell' style='padding:8px 15% 8px 4%;border-bottom:5px solid #f5f5f5;'>"
                        +"<div class='weui-cell__bd list_account' data-aid="+d.id+" >"
                        +"<p>"+d.name+"</p>"
                        +"<p>账户: "+d.account+"</p>"
                        +"</div>"
                        +"<span class='edit-btn' data-id="+d.id+" data-name="+d.name+" data-account="+d.account+" data-bank="+d.bank+" data-tax="+d.tax+"><img src='/wxs/img/edit.png'/></span>"
                        +"</div>"
                        );
                })
                console.log(e.data.length);
                if( e.data.length < 5){
                    $('.list_account_ssss').append("<p class='weui-half-screen-dialog__tips' style='padding:4%;color:#F58A8A'>请核对好收款账户</p><div class='weui-cell' style=''><a href='javascript:;' class='weui-btn weui-btn_block weui-btn_default btn_addacount'>添加新对公账户</a></div>")
                }else{
                    $('.list_account_ssss').append("<div class='weui-cell'><div class='weui-cell__bd'><p>最多添加五个对公账户</p></div></div>")
                }
            }
        });
    });

    //账户列表里选择账户
    $(document).on('click','.list_account',function(e){
        $('.show_account').html( $(this).children('p').html() );
        $aid = $(this).attr('data-aid');
        console.log($aid);
        $('#account').val($aid);
        $(this).parents('.js_dialog').fadeOut(200);
    });

    //账户添加/修改 输入监听(不知为何, 不管用)
    $('.account_input_account').on('onchange oninput',function(){
        
        console.log( $(this).val().toString().length );
        if( $(this).val().toString().length >20)
        {
            $(this).val($(this).val().toString().slice(0,20 ));
        }
    });

    //新账户添加按钮点击事件
    $(document).on('click','.btn_addacount', function(e){
        console.log('add acount');
        $(this).parents('.js_dialog').fadeOut(200);
        $('#Dialog_newAcount').fadeIn(200);

    });

    $('.btn_account_cancel').on('click',function(){
        $(this).parents('.js_dialog').fadeOut(200);
        $('#iosDialog3').fadeIn(200);
    });
    $('.btn_account_ok').on('click',function(){
        console.log('add new account ');
        pullAccount();
    });
    //提交账户
    function pullAccount(){
        if($('.account_input_name').val()==''){
            Toast('请输入对公账户名称');
            return false;
        }
        if($('.account_input_bank').val()==''){
            Toast('请输入开户银行全称');
            return false;
        }
        if($('.account_input_account').val()==''){
            Toast('请输入开户银行账户');
            return false;
        }
        if($('.account_input_tax').val()==''){
            Toast('请输入纳税人识别号');
            return false;
        }
        let formData = $('#cash_account_from').serialize();
        //提交新账户信息
        $.ajax({
            url:"{:url('manage/account_update')}",
            type:"post",
            data:formData,
            success: function(e){
                if(e.code===0){
                    $('.show_account').html(e.data.name+'<br>账户:'+e.data.account ); //填充显示账户信息
                    $('#account').val(e.data.id);   //设置收款账户id
                    //document.getElementById("cash_account_from").reset();   //重置账户添加信息
                    $('.account_input_account').val('');
                    $('.account_input_name').val('');
                    $('.account_input_bank').val('');
                    $('.account_input_tax').val('');
                    //alert('提交一次');
                }else if(e.code===1){
                    alert(e.msg);
                }
                $('.js_dialog').fadeOut(200);   //隐藏
            },
            error:function(){
                $('.js_dialog').fadeOut(200);
            }
        });
    }
    $(document).on('click','.edit-btn', function(e){
        let _this = $(this);
        $('#Dialog_newAcount').fadeIn(200);
        $('#iosDialog3').fadeOut(200);
        $('.account_input_name').val(_this.data('name'))
        $('.account_input_bank').val(_this.data('bank'))
        $('.account_input_account').val(_this.data('account'))
        $('.account_input_tax').val(_this.data('tax'))
        $('.account_id').val(_this.data('id'))
    })
$('.btn_cash').addClass('weui-btn_disabled');
     //监听输入金额
    $(".cash_money").bind("input propertychange change",function(event){
        check();
    });
    
    function check()
    {
        var price = $('.cash_money').val()
        if(price.length==''){
            $('.cash_tip').html('账户剩余金额: '+less+'元');
            return;
        }
        // var less = parseInt( $('#less').val() );
        console.log( 'price: '+price );
        if( less < 500 ){
            console.log( 'price: '+price );
            $(".cash").val('');
            $('.cash_tip').html('账户可提取金额不足500元, 不能提现');
            return false;
        }

        let calcless = less-less%100;

        if(price>=500){
            console.log('price:'+price);
            var _price=price;
            if( _price > calcless ){
                 _price = calcless;
                $(".cash_money").val(calcless);
            }
            // if(price<500) {
            //     $('.btn_cash').addClass('weui-btn_disabled');
            //     return false;
            // }

            if(_price%100 !== 0)
            {
                // $('.btn_cash').addClass('weui-btn_disabled');
                return false;
            }
        
            $('.btn_cash').removeClass('weui-btn_disabled');
            
            var rate = 0;
            var ratemoney =0;
            if(_price <2000 ){
                rate = '2%';
                ratemoney = _price*0.02;
            }else{
                rate = '1%';
                ratemoney = _price*0.01;
            }
            overmoney = _price-ratemoney;
            console.log('rate:'+rate);
            console.log('rate.toFixed:'+ratemoney.toFixed(1));
            $('.cash_tip').html('服务费'+ratemoney.toFixed(1)+'元(费率'+rate+'),实际到账'+overmoney);
        }else{
            $('.btn_cash').addClass('weui-btn_disabled');
            $('.cash_tip').html('可提取金额: '+less+'元');
        }

        
    }

    //提现
    $('.btn_cash').click(function(){

        if( $('.btn_cash').hasClass('weui-btn_disabled') ){
            return false;
        }

        console.log('btn_cash click');
        var price = $('.cash_money').val()
        var less = parseInt( $('#less').val() );
        less = less-less%100;
        var aid = $('#account').val();
        console.log(aid);

        if(0==aid){            
            
            $('.show_account').animate({color:'#FF0000',left:'10px'}, 200,'ease',function(){
                $(this).animate({color:'#000000',left:'-10px'}, 200,'ease',function(){
                    $(this).animate({color:'#FF0000',left:'10px'}, 200,'ease',function(){
                        $(this).animate({color:'#000000',left:'-10px'}, 200)
                    })
                })
            });
            return false;
        }

        if(price < 500 ){
            return false;
        }else if(price > less){
            $(".cash_money").val(less);
            return false;
        }else{
            
            //检查是否选择确认按钮
            var ischeck = document.getElementById('s11').checked;
            if(!ischeck){
                $(".tip_check").animate({color:'#FF0000'}, 200,'ease',function(){
                    $(this).animate({color:'#000000'}, 200,'ease',function(){
                        $(this).animate({color:'#FF0000'}, 200,'ease',function(){
                            $(this).animate({color:'#000000'}, 200)
                        })
                    })
                })
                return false;
            }

            $.ajax({
                url:"{:url('manage/cash')}",
                type:'post',
                data:{price:price,aid:aid},
                success:function(e){
                    if( e.code===0 )
                    {
                        $('.msg_success').show();
                    }else if( e.code===1 ){
                        $('.msg_warn .weui-msg__desc').html('提现错误: '+e.msg);
                        $('.msg_warn').show();
                    }
                },
                error:function(e){

                }
            });
            
        }
    });

    //显示
    function Toast(content, long=2000){
        if ($('#toast').css('display') != 'none') return;
        $('#toast .weui-toast__content').html(content);
        $('#toast').fadeIn(100);
        setTimeout(function () {
            $('#toast').fadeOut(100);
        }, long);

    }

});
 
 
</script>
</html>
