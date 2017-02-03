<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="/Public/SjtAdminSjt/css/css.css" />
<script type="text/javascript" src="/Public/User/js/jquery-1.7.2.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#shtg").click(function(e) {
        if(confirm("您确认要批准此商户通过审核吗？") == true){
			 $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_shjkshtg.html',
			  data:"UserID="+$("#UserID").val(),
			  dataType:'text',
			  success:function(str){
				 if(str == "ok"){
					 alert("已经审核通过！");
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
	
	$("#btgdh").click(function(e) {
        if(confirm("您确认要打回此商户通过审核吗？") == true){
			 $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_dhsh.html',
			  data:"UserID="+$("#UserID").val(),
			  dataType:'text',
			  success:function(str){
				 if(str == "ok"){
					 alert("已修改成功！");
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
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><table cellpadding="0" cellspacing="0" border="0" id="showshenhe">
<input type="hidden" value="<?php echo ($vo["UserID"]); ?>" id="UserID">
<tr>
<td>企业名称</td>
<td class="showshenheclass"><?php echo ($vo["CompanyName"]); ?>&nbsp;</td>
</tr>
<tr>
<td>网站名称</td>
<td class="showshenheclass"><?php echo ($vo["WebsiteName"]); ?>&nbsp;</td>
</tr>
<tr>
<td>网站域名</td>
<td class="showshenheclass"><?php echo ($vo["WebsiteUrl"]); ?>&nbsp;</td>
</tr>
<tr>
<td>商户ID</td>
<td class="showshenheclass"><?php echo ($vo['UserID']+10000); ?></td>
</tr>
<tr>
<td>密钥</td>
<td class="showshenheclass"><?php echo ($vo["Key"]); ?>&nbsp;</td>
</tr>
<tr>
<td>身份证（正面）</td>
<td class="showshenheclass"><a href="/Public/Uploads/<?php echo ($vo["IdentificationFront"]); ?>">查看</a></td>
</tr>
<tr>
<td>身份证（背面）</td>
<td class="showshenheclass"><a href="/Public/Uploads/<?php echo ($vo["IdentificationReverse"]); ?>">查看</a></td>
</tr>
<tr>
<td>手持身份证半身照片</td>
<td class="showshenheclass"><a href="/Public/Uploads/<?php echo ($vo["scsfzbsz"]); ?>">查看</a></td>
</tr>
<tr>
<td>营业执照</td>
<td class="showshenheclass"><a href="/Public/Uploads/<?php echo ($vo["BusinessLicense"]); ?>">查看</a></td>
</tr>
<?php if($vo["Zt"] == 1): ?><tr><td colspan="2"><input type="button" value="审核通过" id="shtg">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="不通过打回" id="btgdh"></td></tr><?php endif; ?>
</table><?php endforeach; endif; else: echo "" ;endif; ?>
</body>
</html>