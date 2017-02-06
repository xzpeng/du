<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo C("WEB_NAME");?>会员管理后台</title>
<link rel="stylesheet" type="text/css" href="/Public/User/css/css.css" />
<script type="text/javascript" src="/Public/User/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="/Public/User/js/js.js"></script>
<script type="text/javascript" src="/Public/User/js/pcasunzip.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#menu div").addClass("menu_bg_y");
	$("#menu div:eq(1)").addClass("menu_bg");
	
	$("#menu_x > div > div:eq(0)").css("background-image","url(/Public/User/images/menumenu.gif)");
	$("#menu_x > div > div:eq(0) a").css("color","#F60");
});
</script>
</head>

<body>
﻿<div id="top_logo">
   <div id="logo"><img src="/Public/images/logo.png"></div>
   <div id="login_reg">
      <div><?php echo ($_SESSION['UserName']); ?>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<a href="/User_Index_ExitLogin.html">退出登录</a>&nbsp;&nbsp;&nbsp;&nbsp;|</div>
      <div style="width:667px; margin-top:0px; height:20px; text-align:center;">
    <!--  <img src="/Public/User/images/new.gif" height="15" width="35" onclick="javascript: showDialog('info', '测试信息<br>sdgbsgsdag','标题', 700);">&nbsp;&nbsp;&nbsp;&nbsp;-->
      
         
      </div>
   </div>
</div>

<div style="width:100%; height:38px; margin:0px auto; background-image:url(/Public/User/images/menu_menu_x.jpg);">

<div id="menu">
<div onclick="javascript:location.href='/User'">首 页</div>
<div onclick="javascript:location.href='/User_Index_basic.html'">账户管理</div>
<div onclick="javascript:location.href='/User_Index_wyjyjl.html'" style="margin-left:45px;">交易管理</div>
<div onclick="javascript:location.href='/User_Index_npdy.html'" style="margin-left:45px;">付款</div>
<div onclick="javascript:location.href='/User_Index_skym.html'" >我的个性主页</div>



<?php switch($_SESSION['UserType']): case "5": ?><div onclick="javascript:location.href='/User_Index_tjyg.html'">
    分润管理
    </div><?php break;?>
    <?php default: endswitch;?>



</div>

</div>
﻿<div id="menu_x">
   <div>
<div><a href="/User_Index_basic.html">基本信息</a></div>
<div style="width:20px;">|</div>
<div><a href="/User_Index_aqxx.html">安全信息</a></div>
<div style="width:20px;">|</div>
<div><a href="/User_Index_mbk.html">密保卡</a></div>
<div style="width:20px;">|</div>
<div><a href="/User_Index_tkyh_banktype_0.html">提现账号设置</a></div>
<div style="width:20px;">|</div>
<div><a href="/User_Index_tksxf.html">手续费</a></div>
<div style="width:20px;">|</div>
<div><a href="/User_Index_dllist.html">登录记录</a></div>
   </div>
 </div>  
 <div id="menu_x_X" style="display:none">
 <div style="text-align:left;">
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <span style="font-weight:bold; color:#F60;">提现账号设置：</span>
 <span><a href="/User_Index_tkyh_banktype_0.html">提款银行</a></span>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<span><a href="/User_Index_tkyh_banktype_1.html">委托提款银行</a></span>
 </div>
 </div>
<form name="Form1" action="/User_Index_basicsave.html" method="post">

<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!-------------------------------------------------基本信息-------------------------------------------------------->
<div class="xgjcxx">
<div style="border:1px solid #ccc; background-image:url(/Public/User/images/menu_bg_x.jpg); width:1000px; height:40px; line-height:40px; font-size:15px; text-align:left; font-weight:bold; color:#333">
&nbsp;&nbsp;&nbsp;&nbsp;基本信息
</div>

<div style="width:1000px; height:auto; border:1px solid #CCC; border-top:0px">
    <div class="jbxx">
    <input type="hidden" name="id" id="id" value="<?php echo ($vo["id"]); ?>" />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;姓名：
    <?php if($vo["Compellation"] == ''): ?><input type="text" class="input_text form-control" name="Compellation" id="Compellation" value="<?php echo ($vo["Compellation"]); ?>">
    &nbsp;&nbsp;&nbsp;&nbsp;<span class="tsts">请输入身份证上的姓名，添加后不能修改。</span>
    <?php else: ?>
    <span style="color:#000; font-weight:bold;"><?php echo htmlspecialchars($vo["Compellation"]);?></span><?php endif; ?>
    
    </div>
     <div class="jbxx">
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QQ号：<input type="text" class="input_text" name="qq" id="qq" style="width:100px;" value="<?php echo ($vo["qq"]); ?>" />
    &nbsp;&nbsp;&nbsp;&nbsp;
    </div>
    
    <div class="jbxx">
    身份证号：<input type="text" class="input_text" name="IdentificationCard" id="IdentificationCard" style="width:300px;" value="<?php echo ($vo["IdentificationCard"]); ?>" />
    &nbsp;&nbsp;&nbsp;&nbsp;<span class="tsts">请输入正确的身份证号</span>
    </div>
     <div class="jbxx">
      手机号码：<input type="text" class="input_text" name="MobilePhone" id="MobilePhone" style="width:200px;" value="<?php echo ($vo["MobilePhone"]); ?>">
      &nbsp;&nbsp;&nbsp;&nbsp;<span class="tsts">请输入常用的手机号码。</span>
     </div>
    <div class="jbxx">
     联系电话：<input type="text" class="input_text" name="Tel" id="Tel" style="width:200px;" value="<?php echo ($vo["Tel"]); ?>">
     &nbsp;&nbsp;&nbsp;&nbsp;<span class="tsts">请输入您公司的座机号码，如 010-123123。</span>
    </div>
    <div class="jbxx" style="display:none;">
     所在省市：
     <select name="Province" id="province"></select>&nbsp;&nbsp;
     <select name="City" id="city"></select>
   <script type="text/javascript">
	 new PCAS("province","city","area");
	 $(document).ready(function(e) {
        $("#province").val('<?php echo ($vo["Province"]); ?>').change();;
		$("#city").val('<?php echo ($vo["City"]); ?>');
    });
   </script>	
   &nbsp;&nbsp;&nbsp;&nbsp;<span class="tsts">请选择您现在所在的省市。</span>	
    </div>
    <div class="jbxx">
    联系地址：<input type="text" class="input_text" name="Address" id="Address" style="width:500px;" value="<?php echo ($vo["Address"]); ?>">
    &nbsp;&nbsp;&nbsp;&nbsp;<span class="tsts">请输入您现在所在省市的详细联系地址。</span>
    </div>
 

   
    <div class="jbxx" style="text-align:center; height:50px;">
    <input type="image" src="/Public/User/images/qrxg.gif" style="vertical-align:middle">
      &nbsp;&nbsp;&nbsp;&nbsp;
    <img src="/Public/User/images/chongzhi.gif" onclick="javascript:document.Form1.reset()" style="vertical-align:middle; cursor:pointer;">  
    </div>
    
</div>
</div>
<!-------------------------------------------------基本信息--------------------------------------------------------><?php endforeach; endif; else: echo "" ;endif; ?>  

 </form>
﻿<div style="clear:left"></div>

<div style="width:100%; height:120px; background-color:#dbe0e3;">
<!---------------------------------------------------------------------------------------------->
<div id="foot">
   <div class="dt">
     <a href="/Index_company.html">关于我们</a> | <a href="Index_sjtcjwt.html">帮助中心</a> | <a href="/Index_fwdt.html">联系我们</a>  | <a href="/Index_ysxy.html">服务协议</a>
   </div>
   <div class="dt">信付云 版权所有2012-2017 　<a href="http://www.miitbeian.gov.cn/state/outPortal/loginPortal.action">蜀ICP备16033332号</a></div>
   <div class="dt"text-align:center">

    <a href="http://www.pbc.gov.cn/" target="_blank"><img src="/Public/images/a1.gif" style=" border:0px"></a>
    <a href="https://sealinfo.verisign.com/splash?form_file=fdf/splash.fdf&dn=du.pengxiaozhou.com&lang=zh_cn" target="_blank"> <img src="/Public/images/a2.gif" style=" border:0px"></a>
    <a href="http://www.12377.cn/" target="_blank"><img src="/Public/images/a3.gif" border:0px></a>
    <a href="http://huhehaote.cyberpolice.cn/" target="_blank"><img src="/Public/images/a4.gif" style=" border:0px"></a>
    <a href="http://www.365anfang.com/" target="_blank"><img src="/Public/images/a5.gif" style=" border:0px;"></a>
    <a href="https://ss.knet.cn/verifyseal.dll?sn=e16112451000003333225255381&comefrom=trust&trustKey=dn&trustValue=du.pengxiaozhou.com" target="_blank"><img src="/Public/images/a6.gif" style=" border:0px"></a>

   </div> 



   </div> 


   </div><br>


</body>
</html>