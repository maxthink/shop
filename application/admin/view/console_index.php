{include file="public/header" /}
<div id="dcWrap">
    <!--包含公共模版-->
    {include file="public/lefter" /}
    <div id="dcMain">
        <!-- 当前位置 -->
        <div id="urHere"> 管理中心<b>></b><strong>系统设置</strong></div>
        <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
            <h3>系统设置</h3>
            <!--{ js href="__PUBLIC__/js/jquery.tab.js" /}-->

            <div class="idTabs">
                <ul class="tab">
                    <li><a id="site" class="selected">常规设置</a></li>
                    <li><a id="display">显示设置</a></li>
                    <!-- <li><a id="defined">自定义</a></li> -->
                    <li><a id="email">邮件服务器</a></li>
                </ul>
                <div class="items">
                    <form>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    {include file="public/footer" /}
    <div class="clear"></div>
</div>
<script type="text/javascript">
    

</script>
</body>
</html>