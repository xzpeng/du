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
        window.location.href = "/SjtAdminSjt_Jilu_wyjl.html?sq_date="+$("#sq_date").val()+"&sq_date_js="+$("#sq_date_js").val()+"&zt="+$("#zt").val()+"&pagepage="+$("#pagepage").val()+"&typepay="+$("#typepay").val()+"&shbh="+$("#shbh").val()+"&TransID="+$("#TransID").val();
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

function xgtz(TransID){
	if(confirm("您确认要重新发送后台通知吗？") == true){
			  $.ajax({
			  type:"POST",
			  url:"/SjtAdminSjt_Jilu_xgtz.html",
			  data:"TransID="+TransID,
			  timeout:2000,
			  dataType:"text",
			  success: function(str){
				 if(str == "ok"){
				 	alert("已成功提交重新发送后台通知请求！");
				 }else{
				    alert("提交重新发送后台通知失败！");
				 }
			  },
			  error:function(){
				  //alert("处理失败！");
			  }
		  });
	}
}
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
  
      <div class="selectclass" style="height:auto;">
      <table border="0" cellpadding="0" cellspacing="0">
      <tr>
      <td>当日总额：<span style="font-size:20px; color:#F00; font-weight:bold;"><?php echo ($daymoney); ?></span> 元</td>
      <td>当日实收金额：<span style="font-size:20px; color:#F00; font-weight:bold;"><?php echo ($daysjmoney); ?></span> 元</td>
      <td>当日成功订单：<span style="font-size:20px; color:#F00; font-weight:bold;"><?php echo ($daynum); ?></span> 笔</td>
      <td>当日利润：<span style="font-size:20px; color:#F00; font-weight:bold;"><?php echo ($daylrmoney); ?></span> 元</td>
      </tr>
      <tr>
      <td>昨日总额：<span style="font-size:20px; color:#F00; font-weight:bold;"><?php echo ($zmoney); ?></span> 元</td>
      <td>昨日实收金额：<span style="font-size:20px; color:#F00; font-weight:bold;"><?php echo ($zsjmoney); ?></span> 元</td>
      <td>昨日成功订单：<span style="font-size:20px; color:#F00; font-weight:bold;"><?php echo ($znum); ?></span> 笔</td>
      <td>昨日利润：<span style="font-size:20px; color:#F00; font-weight:bold;"><?php echo ($zlrmoney); ?></span> 元</td>
      </tr>
      </table>
      </div>
<div class="listmenu" style="text-align:right;">
订单号：<input type="text" name="TransID" id="TransID" size="15" value="<?php echo ($TransID); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
商户编号：<input type="text" name="shbh" id="shbh" size="5" value="<?php echo ($shbh); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
交易时间（开始）：<input type="text" name="sq_date" id="sq_date" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:false})" style="width:100px;" value="<?php echo ($_GET['sq_date']); ?>">&nbsp;&nbsp;&nbsp;&nbsp;
交易时间（结束）：<input type="text" name="sq_date_js" id="sq_date_js" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:false})" style="width:100px;" value="<?php echo ($_GET['sq_date_js']); ?>">&nbsp;&nbsp;&nbsp;&nbsp;
状态：
<select name="zt" id="zt">
    <option value="">全部</option>
    <option value="0">未处理</option>
    <option value="1">成功</option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;
交易类型：
<select name="typepay" id="typepay">
    <option value="">全部</option>
    <option value="0">订单</option>
    <option value="1">充值</option>
    <option value="3">网银收款</option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;
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
$("#zt").val(<?php echo ($_GET['zt']); ?>);
$("#typepay").val(<?php echo ($_GET['typepay']); ?>);
$("#pagepage").val(<?php echo ($_GET['pagepage']); ?>);
</script>
</div>
<table cellpadding="0" cellspacing="0" border="0" id="listuser">
<tr style="background-color:#5d7b9d; color:#fff;">
<td id="xzxz" xz="0" style="cursor:pointer;">选择</td>
<td>交易类型</td>
<td>商户编号</td>
<td>订单号</td>
<td>交易时间</td>
<td>交易金额</td>
<td>手续费</td>
<td>实际金额</td>
<td>状态</td>
<td>通道</td>
<td>银行</td>
<td>通知</td>
<td>来源网址</td>
</tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
<td><input type="checkbox" class="xzxz" name="xz" value="<?php echo ($vo["id"]); ?>"></td>
<td>
 <?php if($vo["typepay"] == 0): ?>订单
   <?php elseif($vo["typepay"] == 1): ?>
   充值
   <?php else: ?>
   网银收款<?php endif; ?>
&nbsp;</td>
<td><?php echo ($vo["UserID"]+10000); ?></td>
<td style="color:#0C0">
<?php echo ($vo["TransID"]); ?>
&nbsp;</td>
<td style="color:#F30"><?php echo ($vo["TradeDate"]); ?>&nbsp;</td>
<td><?php echo ($vo["trademoney"]); ?>&nbsp;</td>
<td><?php echo ($vo["sxfmoney"]); ?>&nbsp;</td>
<td><?php echo ($vo["OrderMoney"]); ?>&nbsp;</td>
<td>
<?php if($vo["Zt"] == 0): ?><span style="color:#F00">未处理</span>
<?php else: ?>
成功<?php endif; ?>
&nbsp;</td>
<td>
<?php echo ($vo["tongdao"]); ?>&nbsp;
</td>
<td>
<?php echo ($vo["bankname"]); ?>&nbsp;
</td>
<td>
   
    <?php if($vo["budan"] == ''): ?>NULL
    <?php else: ?>
    <a href="<?php echo ($vo["budan"]); ?>" target="_blank">补单</a><?php endif; ?>
</td>
<td><a href="<?php echo ($vo["tjurl"]); ?>" target="_blank">来源网址</a></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr>
<td colspan="20"><?php echo ($page); ?></td>
</tr>
</table>
</body>
</html>