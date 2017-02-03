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
        window.location.href = "/SjtAdminSjt_Tikuan_tkjl_T_<?php echo ($_GET['T']); ?>.html?sq_date="+$("#sq_date").val()+"&sq_date_js="+$("#sq_date_js").val()+"&zt="+$("#zt").val()+"&pagepage="+$("#pagepage").val();
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

function daochu(){
	if(confirm("您确认要导出所选数据吗？")){
		 var listcheckbox = "";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + "," + $(this).val();
		     }
        });
	window.open("/SjtAdminSjt_Tikuan_ExportExcel.html?sq_date="+$("#sq_date").val()+"&sq_date_js="+$("#sq_date_js").val()+"&zt="+$("#zt").val()+"&T=<?php echo ($_GET['T']); ?>&wt=0&listcheckbox="+listcheckbox);
	}
	}
</script>
</head>

<body>
<div class="listmenu" style="text-align:right;">
<input type="button" value="导出数据并处理" onclick="daochu()">
申请时间(开始)：<input type="text" name="sq_date" id="sq_date" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:false})" style="width:100px;" value="<?php echo ($_GET['sq_date']); ?>">&nbsp;&nbsp;&nbsp;&nbsp;
申请时间(结束)：<input type="text" name="sq_date_js" id="sq_date_js" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:false})" style="width:100px;" value="<?php echo ($_GET['sq_date_js']); ?>">&nbsp;&nbsp;&nbsp;&nbsp;
请选择状态：
<select name="zt" id="zt">
    <option value="">全部</option>
    <option value="0">未处理</option>
    <option value="2">已打款</option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;
请选择一页显示多少条信息：
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
$("#pagepage").val(<?php echo ($_GET['pagepage']); ?>);
</script>
</div>
<table cellpadding="0" cellspacing="0" border="0" id="listuser">
<tr style="background-color:#5d7b9d; color:#fff;">
<td id="xzxz" xz="0" style="cursor:pointer;">选择</td>
<td>提款金额</td>
<td>手续费</td>
<td>到账金额</td>
<td>银行名称</td>
<td>分行名称</td>
<td>支行名称</td>
<td>银行卡号</td>
<td>开户姓名</td>
<td>申请时间</td>
<td>申请商户</td>
<td>状态</td>
</tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
<td><input type="checkbox" class="xzxz" name="xz" value="<?php echo ($vo["id"]); ?>"></td>
<td style="color:#0C0">￥<?php echo ($vo["tk_money"]); ?>&nbsp;</td>
<td style="color:#0C0">￥<?php echo ($vo["sxf_money"]); ?>&nbsp;</td>
<td style="color:#F30">￥<?php echo ($vo["money"]); ?>&nbsp;</td>
<td><?php echo ($vo["bankname"]); ?>&nbsp;</td>
<td><?php echo ($vo["fen_bankname"]); ?>&nbsp;</td>
<td><?php echo ($vo["zhi_bankname"]); ?>&nbsp;</td>
<td><?php echo ($vo["bank_number"]); ?>&nbsp;</td>
<td><?php echo ($vo["myname"]); ?>&nbsp;</td>
<td><?php echo ($vo["sq_date"]); ?>&nbsp;</td>
<td><?php echo ($vo[UserID]+10000); ?></td>
<td>
<?php if($vo["ZT"] == 0): ?><span style="color:#F00">未处理</span> 
<?php else: ?>
    <?php if($vo["ZT"] == 1): ?>正在处理中
    <?php else: ?>
        已打款<?php endif; endif; ?>
</td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr>
<td colspan="20"><?php echo ($page); ?></td>
</tr>
</table>
</body>
</html>