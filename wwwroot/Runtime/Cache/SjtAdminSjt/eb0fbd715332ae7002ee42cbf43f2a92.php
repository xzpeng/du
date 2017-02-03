<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($UserIDList); ?></title>
<link rel="stylesheet" type="text/css" href="/Public/SjtAdminSjt/css/css.css" />
<script type="text/javascript" src="/Public/User/js/jquery-1.7.2.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
     $("#plszwy").click(function(e) {
        if(confirm("您确认要批量设置以上商户的网银通道吗？")){
		      $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_Plbank.html',
			  data:"PayBank="+ $("#PayBank").val()+"&UserIDList=<?php echo ($UserIDList); ?>",
			  dataType:'text',
			  success:function(str){
				    if(str == "ok"){
					    alert("批量设置成功！");
						window.close();	
					}else{
						alert(str);
						}
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
		}
    });
});
</script>
</head>

<body>

<table cellpadding="0" cellspacing="0" border="0" id="showshenhe">
<tr>
<td style="font-weight:bold">您选择的商户</td
></tr>
<tr>
<td>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div>
<?php echo ($vo["UserName"]); ?>【<span style="color:#F00">
<?php if($vo["PayBank"] == 0): ?>默认网银
<?php else: ?>
<span id="wywy<?php echo ($vo["Shh"]); ?>" style="font-weight:bold; color:#000"></span>
<script type="text/javascript">
$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_Sjapi.html',
			  data:"id=<?php echo ($vo["PayBank"]); ?>",
			  dataType:'text',
			  success:function(str){
				    $("#wywy<?php echo ($vo["Shh"]); ?>").text(str);
				     //document.write(str);
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
</script><?php endif; ?>
</span>
】
</div><?php endforeach; endif; else: echo "" ;endif; ?>
</td>
</tr> 
<tr>
<td style="font-weight:bold">请选择网银接口</td>
</tr>
<tr>
<td style="height:40px;">
<select name="PayBank" id="PayBank" style="font-size:20px;">
<option value="0">默认网银通道</option>
<?php if(is_array($banklist)): $i = 0; $__LIST__ = $banklist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bank): $mod = ($i % 2 );++$i;?><option value="<?php echo ($bank["id"]); ?>"><?php echo ($bank["myname"]); ?>(网银)</option><?php endforeach; endif; else: echo "" ;endif; ?>
<option value="10001">银联无卡支付</option>
</select>
</td>
</tr>
<tr>
<td style="height:40px;">
<input type="button" value="批量设置网银" id="plszwy">
</td>
</tr>
</table>

</body>
</html>