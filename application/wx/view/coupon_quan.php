a<!DOCTYPE html>
<html>
<head>
<title>北京创世共想科技有限公司</title>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<style>
body{ background-color: #071a5a; color:#ffffff; }
a:active a:before a:after{color:#ffffff; }

.title{margin: 15px; font-size:22px; font-weight:blod; color:#fff;  }
.title_taocan{color:#fff; }

.tc{ width:100%; height:80px; background-color:#f4f3f9; color:#000; text-align: center; display:inline-block; margin-bottom:1em; }
.tc p{ margin:0.5em;}
.devstatus{ color: #FF0; font-size:20px; text-align:center; }


</style>
</head>
<body>
    <div><p class="title">量子微磁仪</p></div>
    <hr>

    <div><p class="title_taocan">账单记录</p></div>
    
    <div id='list'></div>


</body>
<script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js"></script>
<script>
    
    
    //获取优惠券列表
    $.post(
        '{:url('coupon/list')}',
        
        function(res){

            if( res.code == 0){
                    
                $.each( res.data, function(key,val){
                    var html = '<div class="tc" data-origal="'+val.id+'" >'
                                +'<p>'+val.name+'</p>'
                                +'<p>'+val.sum+'次' 
                                + val.long/60+'分钟';

                    if(val.price===0){
                        html += '免费体验';
                    }else{
                        html += val.price/100 + '元';
                    }

                    html == '</p></div>';              
                           
                    $('#list').append(html);
                });



            }else{
                alert(res.msg+"ddd");
            }
        },"json" );
    

    // $("body").on("click",".tc", function(){
    //     var qid = $(this).attr('data-origal');
    //     coupon( qid );
    // });

    $(".tc").on("click", function(){
        var qid = $(this).attr('data-origal');
        coupon( qid );
    });


    //点击领取优惠券
    function coupon( qid )
    {
        $.post(
            '{:url('coupon/free')}',
            { id:qid },
            function(res){
                if( res.code == 0){
                    //todo 领券成功
                    alert(res.msg);
                }else if(res.code == 1){
                    alert(res.msg);
                }else{
                    alert(res.msg);
                }
            },
        'json');
    }

    
</script>
</html>
