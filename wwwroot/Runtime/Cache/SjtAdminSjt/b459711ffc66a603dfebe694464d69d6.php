<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="/Public/SjtAdminSjt/css/css.css" />
<script type="text/javascript" src="/Public/SjtAdminSjt/js/jquery-1.7.2.js"></script>
<script type="text/javascript">
function check(){
	return confirm("你确认要修改吗？");
}
</script>
</head>

<body>
<form name="Form1" method="post" action="/SjtAdminSjt_Tongdao_EditTongdao_apiname_<?php echo ($_GET['apiname']); ?>.html" onsubmit="return check();">
<div id="tongdao">
	<div style="font-size:25px; text-align:center; color:#00F; font-weight:bold;">
    <?php switch($_GET['apiname']): case "wxdemo": ?>微信<?php break;?>
    <?php case "alipay": ?>支付宝<?php break;?>
    <?php case "alipaywap": ?>支付宝wap<?php break;?>
    <?php case "wftwxwap": ?>微信wap<?php break;?>
    <?php case "tenpay": ?>财付通<?php break;?>
    <?php case "shengfutong": ?>盛付通<?php break;?>
    <?php case "qiling": ?>70卡<?php break;?>
    <?php case "kuaiqian": ?>快钱<?php break;?>
    <?php case "yibao": ?>易宝<?php break;?>
    <?php case "yinlian": ?>银联在线<?php break;?>
    <?php case "rongbao": ?>融宝<?php break;?>
    <?php case "huanxunips": ?>环迅IPS<?php break;?>
    <?php case "qqbao": ?>QQ钱包<?php break;?>
    <?php case "qqbaowap": ?>QQ钱包wap<?php break; endswitch;?>
    </div>
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div>商户ID：<input type="text" name="shid" id="shid" value="<?php echo ($vo["shid"]); ?>"></div>
    <div>密&nbsp;&nbsp;&nbsp;&nbsp;钥：<input type="text" name="key" id="key" value="<?php echo ($vo["key"]); ?>" style="width:310px;"></div>
    <div>账&nbsp;&nbsp;&nbsp;&nbsp;户：<input type="text" name="zhanghu" id="zhanghu" value="<?php echo ($vo["zhanghu"]); ?>"></div>
    <div>网银费率：<input type="text" name="fl" id="fl" value="<?php echo ($vo["fl"]); ?>"></div>
    <div>是否商户可选：
    <select name="xz" id="xz">
    	<option value="1">可选</option>
        <option value="0">不可选</option>
    </select>
    <script type="text/javascript">
	$("#xz").val(<?php echo ($vo["xz"]); ?>);
	</script>
    </div>
    <div>上次修改时间：<span style="font-size:25px; color:#F00; font-weight:bold;"><?php echo ($vo["edit_date"]); ?></span><input type="hidden" name="datetime" value="<?php echo ($datetime); ?>" /></div>
    <div style="text-align:center; width:600px;"><input type="submit" value="确认修改"  style="font-size:15px;">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="重 置"  style="font-size:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
</form>
</body>
</html>