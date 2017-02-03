<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="/Public/SjtAdminSjt/css/css.css" />
<script type="text/javascript" src="/Public/User/js/jquery-1.7.2.js"></script>
<script type="text/javascript">
function clearNoNum(obj)
	{
		//先把非数字的都替换掉，除了数字和.
		obj.value = obj.value.replace(/[^\d.]/g,"");
		//必须保证第一个为数字而不是.
		obj.value = obj.value.replace(/^\./g,"");
		//保证只有出现一个.而没有多个.
		obj.value = obj.value.replace(/\.{2,}/g,".");
		//保证.只出现一次，而不能出现两次以上
		obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
}

function deldel(id,t){
	
	if(confirm("您确认要删除吗？")){
		
		window.location.href = "/SjtAdminSjt_System_txfldel_id_"+id+".html?t="+t;
		
	}
	
}


function bjbj(id){
	
	 var sheight = "220px";
    var swidth = "500px";
   
var k = window.showModalDialog("/SjtAdminSjt_System_txflbj_id_"+id+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
}
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

<div style="width:100%; margin:0px auto; margin-top:10px; text-align:center; height:auto; font-size:20px; font-weight:bold;">系统统一设置提现手续费( T + <?php echo ($_GET['t']); ?> )
</div>
<table cellpadding="0" cellspacing="0" border="0" id="listuser">
<tr>
<td colspan="7">
<form name="Form1" method="post" action="/SjtAdminSjt_System_txfladd.html">
<input type="hidden" name="T" value="<?php echo ($_GET['t']); ?>">
开始金额：<input type="text" name="k_money" size="20" onkeyup="clearNoNum(this)">&nbsp;&nbsp;&nbsp;&nbsp;
结束金额：<input type="text" name="s_money" size="20" onkeyup="clearNoNum(this)">&nbsp;&nbsp;&nbsp;&nbsp;
手续费：<input type="text" name="fl_money" size="20" onkeyup="clearNoNum(this)">&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="添 加">
</form>
</td>
</tr>
<tr style="background-color:#5d7b9d; color:#fff;">
<td>起始金额</td>
<td>结束金额</td>
<td>手续费</td>
<td>删除</td>
<td>编辑</td>
</tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
<td style="font-size:20px; font-weight:bold; color:#00F"><?php echo ($vo["k_money"]); ?> 元</td>
<td style="font-size:20px; font-weight:bold; color:#00F"><?php echo ($vo["s_money"]); ?> 元</td>
<td style="font-size:20px; font-weight:bold; color:#F00"><?php echo ($vo["fl_money"]); ?> 元</td>
<td><input type="button" value="删 除" onclick="javascript:deldel(<?php echo ($vo["id"]); ?>,<?php echo ($_GET['t']); ?>)"></td>
<td><input type="button" value="编 辑" onclick="javascript:bjbj(<?php echo ($vo["id"]); ?>)"></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr style="font-size:14px;"><td colspan="15"><?php echo ($page); ?>&nbsp;</td></tr>
</table>

</body>
</html>