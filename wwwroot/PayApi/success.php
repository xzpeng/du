<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>支付成功</title>
<css href='/Public/User/css/css.css' />
<css href='/Public/css/defaultcss.css' />
<css href='/Public/css/css.css' />
<css href='/Public/css/slider.css' />
<css href='/Public/css/reg.css' />

<script type="text/javascript">
$(document).ready(function(e) {
    $("#menu div").addClass("menu_bg_y");
	$("#menu div:eq(0)").addClass("menu_bg");
	
	$("#menu_x > div > div:eq(1)").css("background-image","url(/Public/User/images/menumenu.gif)");
	$("#menu_x > div > div:eq(1) a").css("color","#F60");
});
</script>
<style type="text/css">
#successtab{
	width:700px;
	height:auto;
	border:1px solid #063; 
	}
#successtab tr td{
	width:700px;
	height:30px;
	text-align:center;
	vertical-align:middle;
	}	
</style>
</head>

<body>


<br>
<div class="cz_div" style="height:auto;">
<div style="width:700px; margin:0px auto; margin-top:50px; height:auto;">
<table border="0" id="successtab" cellpadding="0" cellspacing="0">
<tr>
<td style="color:#F60; font-size:50px;">
<if condition="$sk == 'ok'">
支付成功！
<else />
</if>
</td>
</tr>
<tr>
<td style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;交易金额：<span style="color:#060; font-size:25px;"><?php echo $_GET['total_fee'];?> 元</span></td>
</tr>
<tr>
<td style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;订单号：<span style="color:#39F"><?php echo $_GET['out_trade_no'];?></span></td>
</tr>
<tr>
<td style="text-align:left;color:#FF0000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本页面5秒后将自动回到商家网站</td>
</tr>
<tr>
<td style="text-align:left;">&nbsp;</td>
</tr>
</table>
</div>
<br>
</div>


<include file="Index:foot" />
</body>
</html>
