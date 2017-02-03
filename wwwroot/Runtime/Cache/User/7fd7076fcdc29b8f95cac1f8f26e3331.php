<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员管理后台</title>
<link rel="stylesheet" type="text/css" href="/Public/User/css/css.css" />
<script type="text/javascript" src="/Public/User/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="/Public/User/js/js.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#menu div").addClass("menu_bg_y");
	$("#menu div:eq(1)").addClass("menu_bg");
	$("#menu_x > div > div:eq(6)").css("background-image","url(/Public/User/images/menumenu.gif)");
	$("#menu_x > div > div:eq(6) a").css("color","#F60");
	$("#menu_x_X").show();
});

function check(){
	if($("#BankName").val() == ""){
		alert("开户银行名称不能为空！");
		$("#BankName").focus();
		return false;
	}else{
		if($("#BankBranch").val() == ""){
		    alert("开户分行名称不能为空!");
			$("#BankBranch").focus();
			return false;
		}else{
			if($("#BankAccountNumber").val() == ""){
				alert("开户银行账户不能为空！");
				$("#BankAccountNumber").focus();
				return false;
			}else{
				if($("#BankCompellation").val() == ""){
					alert("开户人姓名不能为空！");
					$("#BankCompellation").focus();
					return false;
				}else{
					if(confirm("您确认要添加吗？") == true){
					    return true;
					}else{
						return false;
					}
				}
			}
		}
	}
}

function DelBank(id){
	if(confirm("您确认要删此银行信息吗？") == true){
	    location.href = "/User_Index_DelBank_id_"+id+".html?banktype=<?php echo ($banktype); ?>";
	}
}


function morenselect(){
	if($("#moren").attr("checked") == "checked"){
	    $("#moren").attr("checked",false);
	}else{
		$("#moren").attr("checked",true);
		}
}
</script>
<style type="text/css">

</style>
</head>

<body>



<form name="Form1" method="post" action="/User_Index_AddBank_banktype_<?php echo ($banktype); ?>.html" onsubmit="return check()">
<input type="hidden" name="banktype" value="<?php echo ($banktype); ?>">
<!-------------------------------------------------提款银行-------------------------------------------------------->
<div class="xgjcxx">
<div style="border:1px solid #ccc; background-image:url(/Public/User/images/menu_bg_x.jpg); width:1000px; height:40px; line-height:40px; font-size:15px; text-align:left; font-weight:bold; color:#333">
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($tkyhtypename); ?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php if($banktype == 1): ?><span style="color:#666;">【可添加委托提款银行  <span style="color:#060"><?php echo ($wtyh); ?></span> 个&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;已添加委托提款银行 <span style="color:
#F00"><?php echo ($ytjtkyh); ?></span> 个】</span><?php endif; ?>
</div>
<div style="height:10px;"></div>
<div style="width:1000px; height:auto; border:0px;">
   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="jbxx" style="width:33%; float:left; border-bottom:0px;"><a href="/User_Index_edittkyh_id_<?php echo ($vo["id"]); ?>.html?banktype=<?php echo ($_GET['banktype']); ?>" style="color:#063; text-decoration:none;"><?php echo ($vo["BankName"]); ?>(<?php echo ($vo["BankCompellation"]); ?>)</a>
   <?php if($vo["moren"] == 1): ?>(默认)<?php endif; ?>
 
    <a href="javascript:DelBank(<?php echo ($vo["id"]); ?>)"><img src="/Public/User/images/del.jpg" style="vertical-align:middle; border:0px;"></a></div><?php endforeach; endif; else: echo "" ;endif; ?>
   
</div>
<div style="clear:left;"></div><br>

<div style="width:1000px; height:auto; border:1px solid #CCC; border-bottom:0px;">

<?php if(($ytjtkyh < $wtyh) or ($banktype == 0)): ?><div class="jbxx"><input type="hidden" name="UserID" id="UserID" value="<?php echo ($_SESSION['UserID']); ?>">
    开户行名称：<input type="text" class="input_text" name="BankName" id="BankName" style="width:300px;">
    &nbsp;&nbsp;&nbsp;&nbsp;
    <?php if($banktype == 0): ?><span class="tsts"><input type="checkbox" name="moren" id="moren" value="1" style="vertical-align:middle" /> <span style="cursor:pointer;" onclick="javascript:morenselect();">默认提款账户</span></span><?php endif; ?>支付宝用户请填写"支付宝"
    </div>
    <div class="jbxx">
    开户分行名：<input type="text" class="input_text" name="BankBranch" id="BankBranch" style="width:300px;" />
    &nbsp;&nbsp;&nbsp;&nbsp;<span class="tsts"></span>支付宝用户请填写"支付宝"
    </div>
     <div class="jbxx">
      开户支行名：<input type="text" class="input_text" name="zhihang" id="zhihang" style="width:300px;">
      &nbsp;&nbsp;&nbsp;&nbsp;<span class="tsts"></span>支付宝用户请填写"支付宝"
     </div>
     <div class="jbxx">
      收款&nbsp;账号：<input type="text" class="input_text" name="BankAccountNumber" id="BankAccountNumber" style="width:300px;">
      &nbsp;&nbsp;&nbsp;&nbsp;<span class="tsts"></span>支付宝用户请填写支付宝账号
     </div>
    
    <div class="jbxx">
     开户人姓名：
     
     <?php if($banktype == 0): ?><!------------------------------------------------------------------------------------------->
    <span style="color:#000;"><?php echo ($Compellation); ?></span>
     <input type="hidden" class="input_text" name="BankCompellation" id="BankCompellation" value="<?php echo ($Compellation); ?>" style="width:300px;">
     &nbsp;&nbsp;&nbsp;&nbsp;<span class="tsts"></span>
     <!-------------------------------------------------------------------------------------------->
     <?php else: ?>
     <input type="text" class="input_text" name="BankCompellation" id="BankCompellation" style="width:300px;"><?php endif; ?>
    </div><?php endif; ?>  
    <div class="jbxx" style="text-align:center; height:50px;">
      
      <?php if($banktype == 0): ?><!---------------------------------------------------------------------------------->
       <?php if($Compellation == ''): ?>请您在 <a href="/User_Index_basic.html">基本信息</a> 里添加姓名，才能添加提款银行！<?php echo ($Compellation); ?>
       <?php else: ?>
       <input type="image" src="/Public/User/images/tj.gif" style="vertical-align:middle">
      &nbsp;&nbsp;&nbsp;&nbsp;
    <img src="/Public/User/images/chongzhi.gif" onclick="javascript:document.Form1.reset()" style="vertical-align:middle; cursor:pointer;"><?php endif; ?>
      <!----------------------------------------------------------------------------------->
      <?php else: ?>
      <!--------------------------------------------------------------------------------->
      <?php if(($ytjtkyh < $wtyh)): ?><input type="image" src="/Public/User/images/tj.gif" style="vertical-align:middle">
      &nbsp;&nbsp;&nbsp;&nbsp;
    <img src="/Public/User/images/chongzhi.gif" onclick="javascript:document.Form1.reset()" style="vertical-align:middle; cursor:pointer;"><?php endif; ?>
       <!--------------------------------------------------------------------------------><?php endif; ?>
        
    </div>
    
</div>


</div>

<!-------------------------------------------------提款银行-------------------------------------------------------->
</form>



</body>
</html>