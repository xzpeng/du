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
        window.location.href = "/SjtAdminSjt_News_newslist_type_<?php echo ($_GET['type']); ?>.html?title="+$("#title").val()+"&zt="+$("#zt").val();
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

function plsc(){
	
	if(!confirm("您确认要批量的删除吗？")){
		return false;
		}
	
     var idstr = "";
	 
	 $(".xzxz").each(function(index, element) {
        if($(this).attr("checked")){
			idstr = $(this).val()+","+idstr;
			}
    });
	
	idstr = idstr+"0";

	//////////////////////////////////////////////////////////
	$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_News_delnews.html',
			  data:"idstr="+idstr,
			  dataType:'text',
			  success:function(str){
				if(str == "ok"){
					alert("删除成功！");
					}else{
						alert("删除失败，请稍后再试！");
						}
				window.location = window.location;
						
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
	/////////////////////////////////////////////////////////
}

function plpb(){
	
	if(!confirm("您确认要批量的屏蔽吗？")){
		return false;
		}
	
     var idstr = "";
	 
	 $(".xzxz").each(function(index, element) {
        if($(this).attr("checked")){
			idstr = $(this).val()+","+idstr;
			}
    });
	
	idstr = idstr+"0";

	//////////////////////////////////////////////////////////
	$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_News_pingbinews.html',
			  data:"idstr="+idstr,
			  dataType:'text',
			  success:function(str){
				if(str == "ok"){
					alert("屏蔽成功！");
					}else{
						alert("失败，请稍后再试！");
						}
				window.location = window.location;
						
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
	/////////////////////////////////////////////////////////
}

function plhf(){
	
	
	if(!confirm("您确认要批量恢复正常显示吗？")){
		return false;
		}
	
     var idstr = "";
	 
	 $(".xzxz").each(function(index, element) {
        if($(this).attr("checked")){
			idstr = $(this).val()+","+idstr;
			}
    });
	
	idstr = idstr+"0";

	//////////////////////////////////////////////////////////
	$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_News_huifunews.html',
			  data:"idstr="+idstr,
			  dataType:'text',
			  success:function(str){
				if(str == "ok"){
					alert("恢复成功！");
					}else{
						alert("失败，请稍后再试！");
						}
				window.location = window.location;
						
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
	/////////////////////////////////////////////////////////
	
}

function del(id){
	
	if(!confirm("您确认要删除吗？")){
		return false;
		}
	
	//////////////////////////////////////////////////////////
	$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_News_delnews.html',
			  data:"idstr="+id,
			  dataType:'text',
			  success:function(str){
				if(str == "ok"){
					alert("删除成功！");
					}else{
						alert("删除失败，请稍后再试！");
						}
				window.location = window.location;
						
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
	/////////////////////////////////////////////////////////
	}
	
function editnews(id){
	
	window.location.href = "/SjtAdminSjt_News_editnew_type_<?php echo ($_GET['type']); ?>.html?id="+id;
	
	}	
</script>
</head>

<body>
<div class="listmenu" style="text-align:right;">
<input type="button" value="批 量 删 除" onclick="javascript:plsc()">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="批 量 屏 蔽" onclick="javascript:plpb()">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="批 量 恢 复 正 常" onclick="javascript:plhf();">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
请输入标题名称：<input type="text" name="title" id="title" size="30" value="<?php echo ($_GET['title']); ?>"> 
&nbsp;&nbsp;&nbsp;&nbsp;
请选择状态：
<select name="zt" id="zt">
    <option value="">全部</option>
    <option value="0">正常</option>
    <option value="1">屏蔽</option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="搜 索" id="SearchButton">
<script type="text/javascript">
$("#zt").val(<?php echo ($_GET['zt']); ?>);
</script>
</div>
<table cellpadding="0" cellspacing="0" border="0" id="listuser">
<tr style="background-color:#5d7b9d; color:#fff;">
<td id="xzxz" xz="0" style="cursor:pointer;">选择</td>
<td>标题</td>
<td>最后处理时间</td>
<td>状态</td>
<td>编辑</td>
<td>删除</td>

</tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
<td><input type="checkbox" name="xz" class="xzxz" value="<?php echo ($vo["id"]); ?>"></td>
<td><?php echo ($vo["title"]); ?></td>
<td><?php echo ($vo["datetime"]); ?></td>
<td>
<?php if($vo["zt"] == 0): ?>正常
<?php else: ?>
<span style="color:#F00">屏蔽</span><?php endif; ?>
</td>
<td><input type="button" value="编 辑" onclick="javascript:editnews(<?php echo ($vo["id"]); ?>)"></td>
<td><input type="button" value="删 除" onclick="javascript:del(<?php echo ($vo["id"]); ?>)"></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr>
<td colspan="20"><?php echo ($page); ?></td>
</tr>
</table>
</body>
</html>