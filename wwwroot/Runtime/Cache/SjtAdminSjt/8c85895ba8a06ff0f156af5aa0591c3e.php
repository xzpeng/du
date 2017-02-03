<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($UserIDList); ?></title>
<link rel="stylesheet" type="text/css" href="/Public/SjtAdminSjt/css/css.css" />
<script type="text/javascript" src="/Public/User/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="/Public/User/js/pcasunzip.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#xxxx").click(function(e) {
        if(confirm("您确认要修改吗？")){
			 $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_Userbasicinformationedit.html',
			  data:"Compellation="+ $("#Compellation").val()+"&MobilePhone="+ $("#MobilePhone").val()+ "&Tel="+ $("#Tel").val()+ "&IdentificationCard="+ $("#IdentificationCard").val()+ "&Address="+ $("#Address").val()+"&province="+ $("#Province").val()+ "&city="+ $("#City").val()+"&UserID="+ $("#UserID").val()+"&qq="+$("#qq").val(),
			  dataType:'text',
			  success:function(str){
				    if(str == "ok"){
					    alert("修改成功！");
					}
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
		}
    });
	
	
	
	$("#yyyy").click(function(e) {
        if(confirm("您确认要修改吗？")){
			 $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_Userapiinformationedit.html',
			  data:"CompanyName="+ $("#CompanyName").val()+"&WebsiteName="+ $("#WebsiteName").val()+ "&WebsiteUrl="+ $("#WebsiteUrl").val()+"&UserID="+ $("#UserID").val(),
			  dataType:'text',
			  success:function(str){
				    if(str == "ok"){
					    alert("修改成功！");
					}
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
		}
    });
	
	
	$("#zzzz").click(function(e) {
		//alert($("#Userlx").val());
        if(confirm("您确认要修改吗？")){
			 $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_Userlx.html',
			  data:"Userlx="+$("#Userlx").val()+"&UserID="+$("#UserID").val(),
			  dataType:'text',
			  success:function(str){
				    if(str == "ok"){
					    alert("修改成功！");
					}
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
		}
    });
	
	$("#xgxg").click(function(e) {
        $("#sjtxt").hide();
		$("#sjinput").show();
		$("#ssss").show();
		$(this).hide();
    });
	
	
	$("#ssss").click(function(e) {
		//alert($("#sjusername").val());
        if(confirm("您确认要修改上家代理吗？")){
			 $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_EditSjdl.html',
			  data:"sjusername="+$("#sjusername").val()+"&UserID="+$("#UserID").val(),
			  dataType:'text',
			  success:function(str){
				    if(str == "ok"){
					    alert("修改成功！");
						
					}else{
						alert("修改失败，请确认上家代理的用户名是否输入错误！");
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
<style type="text/css">
#showshenhe tr td{
	height:30px;
	vertical-align:middle;
	}
</style>
</head>

<body>

<table cellpadding="0" cellspacing="0" border="0" id="showshenhe">
<?php if(is_array($basiclist)): $i = 0; $__LIST__ = $basiclist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><input type="hidden" id="UserID" name="UserID" value="<?php echo ($vo["UserID"]); ?>">
<tr>
<td style="width:20%;">姓名：</td>
<td style="text-align:left; padding-left:10px;"><input type="text" name="Compellation" id="Compellation" size="50" value="<?php echo ($vo["Compellation"]); ?>"></td>
</tr>
<tr>
<td style="width:20%;">QQ号：</td>
<td style="text-align:left; padding-left:10px;"><input type="text" name="qq" id="qq" size="50" value="<?php echo ($vo["qq"]); ?>"></td>
</tr>

<tr>
<td style="width:20%;">手机号：</td>
<td style="text-align:left; padding-left:10px;"><input type="text" name="MobilePhone" id="MobilePhone" size="50" value="<?php echo ($vo["MobilePhone"]); ?>"></td>
</tr>
<tr>
<td style="width:20%;">座机电话：</td>
<td style="text-align:left; padding-left:10px;"><input type="text" name="Tel" id="Tel" size="50" value="<?php echo ($vo["Tel"]); ?>"></td>
</tr>
<tr>
<td style="width:20%;">身份证号：</td>
<td style="text-align:left; padding-left:10px;"><input type="text" name="IdentificationCard" id="IdentificationCard" size="50" value="<?php echo ($vo["IdentificationCard"]); ?>"></td>
</tr>
<tr>
<td style="width:20%;">联系地址：</td>
<td style="text-align:left; padding-left:10px;"><input type="text" name="Address" id="Address" size="50" value="<?php echo ($vo["Address"]); ?>"></td>
</tr>
<tr style="display:none;">
<td style="width:20%;">所在省市：</td>
<td style="text-align:left; padding-left:10px;">
 <select name="Province" id="Province"></select>&nbsp;&nbsp;
     <select name="City" id="City"></select>
   <script type="text/javascript">
	 new PCAS("province","city","area");
	 $(document).ready(function(e) {
        $("#Province").val('<?php echo ($vo["Province"]); ?>').change();;
		$("#City").val('<?php echo ($vo["City"]); ?>');
    });
   </script>	
</td>
</tr>
<tr>
<td colspan="2"><input type="button" value="确认修改" id="xxxx"></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr style="display:none;"><td colspan="2" style="background-color:#ccc">&nbsp;</td></tr>
<?php if(is_array($apilist)): $i = 0; $__LIST__ = $apilist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr style="display:none;">
<td style="width:20%;">公司名称：</td>
<td style="text-align:left; padding-left:10px;"><input type="text" name="CompanyName" id="CompanyName" size="50" value="<?php echo ($vo["CompanyName"]); ?>"></td>
</tr>
<tr style="display:none;">
<td style="width:20%;">网站名称：</td>
<td style="text-align:left; padding-left:10px;"><input type="text" name="WebsiteName" id="WebsiteName" size="50" value="<?php echo ($vo["WebsiteName"]); ?>"></td>
</tr style="display:none;">
<tr style="display:none;">
<td style="width:20%;">网站域名：</td>
<td style="text-align:left; padding-left:10px;"><input type="text" name="WebsiteUrl" id="WebsiteUrl" size="40" value="<?php echo ($vo["WebsiteUrl"]); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#F00">与商户接口关联，请谨慎修改！</span></td>
</tr>
<tr style="display:none;">
<td colspan="2"><input type="button" value="确认修改" id="yyyy"></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr><td colspan="2" style="background-color:#ccc">&nbsp;</td></tr>
<tr>
<td style="width:20%;">用户类型：</td>
<td style="text-align:left; padding-left:10px;">
<select name="Userlx" id="Userlx">
<option value="1">普通商户</option>
<option value="5">代理商</option>
</select>
<script type="text/javascript">
$("#Userlx").val(<?php echo ($Userlx); ?>);
</script>
</td>
</tr>
<tr>
<td colspan="2"><input type="button" value="确认修改" id="zzzz"></td>
</tr>
<tr><td colspan="2" style="background-color:#ccc">&nbsp;</td></tr>
<tr>
<td style="width:20%;">上级代理：</td>
<td style="text-align:left; padding-left:10px;">
<span id="sjtxt"><?php echo ($sjusername); ?> <b><?php echo ($sjname); ?></b></span><span id="sjinput" style="display:none">请输入上家代理的用户名：<input type="text" id="sjusername" value="<?php echo ($sjusername); ?>" size="40"></span>&nbsp;&nbsp;<input type="button" value="修改" id="xgxg">
</td>
</tr>
<tr>
<td colspan="2"><input type="button" value="确认修改" id="ssss" style="display:none"></td>
</tr>
</table>

</body>
</html>