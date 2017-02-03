<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="/Public/SjtAdminSjt/css/css.css" />
<script type="text/javascript" src="/Public/User/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="/Public/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#SearchButton").click(function(e) {
        window.location.href = "/SjtAdminSjt_ShangHu_zjbdjl.html?sq_date="+$("#sq_date").val()+"&sq_date_js="+$("#sq_date_js").val()+"&pagepage="+$("#pagepage").val()+"&shbh="+$("#shbh").val()+"&lx="+$("#lx").val();
    });
	
	 $("#xzxz").click(function(e) {
        if(parseInt($(this).attr("xz")) == 0){
		    $(this).attr("xz",1);
			$("#listuser input[type='checkbox']").attr("checked",true);
		}else{
			$(this).attr("xz",0);
			$("#listuser input[type='checkbox']").attr("checked",false);
		}
    });
});


</script>
<style type="text/css">
.selectclass{
	width:100%;
	height:50px;
	line-height:50px;
	text-align:center;
	text-align:left;
	font-size:15px;
	margin:0px auto;
	border-bottom:1px dashed #999999;
	}
.selectclass table{
	width:100%;
	height:50px;
	}

.selectclass table tr td{
	width:9%;
	height:50px;
	text-align:center;
	vertical-align:middle;
	font-size:12px;
	}	
</style>
</head>

<body>
  
    
<div class="listmenu" style="text-align:right;">
商户编号：<input type="text" name="shbh" id="shbh" size="5" value="<?php echo ($shbh); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
交易时间（开始）：<input type="text" name="sq_date" id="sq_date" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:false})" style="width:100px;" value="<?php echo ($_GET['sq_date']); ?>">&nbsp;&nbsp;&nbsp;&nbsp;
交易时间（结束）：<input type="text" name="sq_date_js" id="sq_date_js" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:false})" style="width:100px;" value="<?php echo ($_GET['sq_date_js']); ?>">&nbsp;&nbsp;&nbsp;&nbsp;
类型：
<select name="lx" id="lx">
<option value="">全部类型</option>
<option value="1">网银交易</option>
<option value="2">点卡交易</option>
<option value="3">平台转账</option>
<option value="4">提款</option>
<option value="5">减金</option>
<option value="6">增金</option>
<opiton value="7">分润提成</opiton>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;
每页：
<select name="pagepage" id="pagepage">
<option value="10">10条</option>
<option value="15">15条</option>
<option value="20">20条</option>
<option value="25">25条</option>
<option value="30">30条</option>
<option value="35">35条</option>
<option value="40">40条</option>
<option value="45">45条</option>
<option value="50">50条</option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="搜 索" id="SearchButton">
<script type="text/javascript">
$("#pagepage").val(<?php echo ($_GET['pagepage']); ?>);
$("#lx").val(<?php echo ($_GET['lx']); ?>);
</script>
</div>
<table cellpadding="0" cellspacing="0" border="0" id="listuser">
<tr>
<td colspan="4" style="text-align:right; font-size:20px; font-weight:bold;">合计金额：</td>
<td style="font-size:20px; color:#00F; font-weight:bold;"><?php echo ($hjje); ?></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr style="background-color:#5d7b9d; color:#fff;">
<td id="xzxz" xz="0" style="cursor:pointer;">选择</td>
<td>商户编号</td>
<td>交易时间</td>
<td>原金额</td>
<td>变动金额</td>
<td>变动后金额</td>
<td>交易类型</td>
</tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
<td><input type="checkbox" class="xzxz" name="xz" value="<?php echo ($vo["id"]); ?>"></td>

<td><?php echo ($vo["UserID"]+10000); ?></td>
<td style="font-size:18px;">
<?php echo ($vo["datetime"]); ?>
&nbsp;</td>
<td style="font-size:20px; font-weight:bold; color:#060;"><?php echo ($vo["ymoney"]); ?></td>
<td style="font-size:20px; font-weight:bold;">
<?php if($vo["money"] > 0): ?><span style="color:#0F0;">+
<?php else: ?>
<span style="color:#F00"><?php endif; ?>
<?php echo ($vo["money"]); ?>
</span>
</td>
<td style="font-size:20px; font-weight:bold; color:#66F"><?php echo ($vo["gmoney"]); ?></td>
<td>
<?php switch($vo["lx"]): case "1": ?>网银交易<?php break;?>
  <?php case "2": ?>点卡交易<?php break;?>
  <?php case "3": ?>平台转账<?php break;?>
  <?php case "4": ?>提款<?php break;?>
  <?php case "5": ?>减金<?php break;?>
  <?php case "6": ?>增金<?php break; endswitch;?>
</td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr>
<td colspan="20"><?php echo ($page); ?></td>
</tr>
</table>
</body>
</html>