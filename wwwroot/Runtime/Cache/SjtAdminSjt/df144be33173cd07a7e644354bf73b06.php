<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="/Public/SjtAdminSjt/css/css.css" />
<script type="text/javascript" src="/Public/User/js/jquery-1.7.2.js"></script>
<script type="text/javascript">

</script>
<style type="text/css">
#listuser{
	margin-top:10px;
	}
#listuser div{
	width:30%;
	height:40px;
	line-height:40px;
	float:left;
	text-align:left;
	margin-left:3%;
	}
#listuser div input{
	border:0px;
	border-bottom:1px dashed #666;
	color:#369;
	vertical-align:middle;
	}	
</style>
</head>

<body>
<form name="Form1" method="post" action="/SjtAdminSjt_Tongdao_sjfledit.html">
<div style="width:100%; margin:0px auto; margin-top:10px; text-align:center; height:auto; font-size:20px; font-weight:bold;">上家充值手续费率<br><br>
<div style="color:#F00; font-size:12px; font-weight:normal;">注：以下输入均为小于1的数字,请在小数点前加上0,比如网银99%的价格，请输入0.99</div>
</div>
<table cellpadding="0" cellspacing="0" border="0" id="listuser">
<tr>
<td>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>">
<input type="hidden" name="apiname" value="<?php echo ($vo["jkname"]); ?>">
<div>网银：<input type="text" name="wy" value="<?php echo ($vo["wy"]); ?>"></div>
<div>天宏一卡通：<input type="text" name="thykt" value="<?php echo ($vo["thykt"]); ?>"></div>
<div>完美一卡通：<input type="text" name="wmykt" value="<?php echo ($vo["wmykt"]); ?>"></div>
<div>网易一卡通：<input type="text" name="wyykt" value="<?php echo ($vo["wyykt"]); ?>"></div>
<div>联通充值卡：<input type="text" name="ltczk" value="<?php echo ($vo["ltczk"]); ?>"></div>
<div>久游一卡通：<input type="text" name="jyykt" value="<?php echo ($vo["jyykt"]); ?>"></div>
<div>QQ币充值卡：<input type="text" name="qqczk" value="<?php echo ($vo["qqczk"]); ?>"></div>
<div>搜狐一卡通：<input type="text" name="shykt" value="<?php echo ($vo["shykt"]); ?>"></div>
<div>征途游戏卡：<input type="text" name="ztyxk" value="<?php echo ($vo["ztyxk"]); ?>"></div>
<div>骏网一卡通：<input type="text" name="jwykt" value="<?php echo ($vo["jwykt"]); ?>"></div>
<div>盛大一卡通：<input type="text" name="sdykt" value="<?php echo ($vo["sdykt"]); ?>"></div>
<div>全国神州行：<input type="text" name="qgszx" value="<?php echo ($vo["qgszx"]); ?>"></div>
<div>天下一卡通：<input type="text" name="txykt" value="<?php echo ($vo["txykt"]); ?>"></div>
<div>电信充值卡：<input type="text" name="dxczk" value="<?php echo ($vo["dxczk"]); ?>"></div>  
<div>光宇一卡通：<input type="text" name="gyykt" value="<?php echo ($vo["gyykt"]); ?>"></div>
<div>纵游一卡通：<input type="text" name="zyykt" value="<?php echo ($vo["zyykt"]); ?>"></div>
<div>移动短信：<input type="text" name="yddx" value="<?php echo ($vo["yddx"]); ?>"></div>
<div>联通短信：<input type="text" name="ltdx" value="<?php echo ($vo["ltdx"]); ?>"></div>
<div>电信短信：<input type="text" name="dxdx" value="<?php echo ($vo["dxdx"]); ?>"></div><?php endforeach; endif; else: echo "" ;endif; ?>
</td>
</tr>
</table>
<div style="width:100%; margin:0px auto; margin-top:10px; text-align:center; height:auto; font-size:20px; font-weight:bold;">
<input type="submit" value="确认修改">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" onclick="javascript:location.href='SJtAdminSjt_Tongdao_Index_apiname_<?php echo ($_GET['apiname']); ?>.html'">
</div>
</form>
</body>
</html>