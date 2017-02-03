$(document).ready(function(e) {
    $("#xzxz").click(function(e) {
        if(parseInt($(this).attr("xz")) == 0){
		    $(this).attr("xz",1);
			$("#listuser input[type='checkbox']").attr("checked",true);
		}else{
			$(this).attr("xz",0);
			$("#listuser input[type='checkbox']").attr("checked",false);
		}
    });
	
	
	$("#SearchButton").click(function(e) {
        var SearchContent = "";
		if($("#SearchContent").val() != ""){
			SearchContent = SearchContent+"SearchContent="+$("#SearchContent").val();
		}
		
		if($("#UserType").val() != ""){
			SearchContent = SearchContent+"&UserType="+$("#UserType").val();
		}
		
		if($("#Zt").val() != ""){
			SearchContent = SearchContent+"&Zt="+$("#Zt").val();  
		}
		
		if($("#status").val() != ""){
			SearchContent = SearchContent+"&status="+$("#status").val();
		}
		
		
		if($("#Userlx").val() != ""){
			SearchContent = SearchContent+"&Userlx="+$("#Userlx").val();
			
		}
		
		location.href = "/SjtAdminSjt_ShangHu_listuser.html?"+SearchContent;
    });
	
	$("#plsh").click(function(e) {     //批量审核
	   if(confirm("您确认要批量审核您勾选的商户吗？且只有状态为等待审核商户才能审核通过！")){
         var listcheckbox = "";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked") && $(this).attr("zt") == 1){
           
				 listcheckbox = listcheckbox + "|" + $(this).val();
		     }
        });
		
		if(listcheckbox == ""){
			alert("没有可审核的商户");
			location.href = location.href;
		}else{
			arraylist = listcheckbox.split("|");
			plsh(0,arraylist);
		}
	   }
    });
	
	$("#dongjie").click(function(e) {    //冻结账户
        if(confirm("您确认要批量冻结您勾选的商户吗？")){
         var listcheckbox = "0";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + "," + $(this).val();
		     }
        });
		
		/////////////////////////////////////////////////////////
		$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_Dongjie_status_2.html',
			  data:"UserIDList="+listcheckbox,
			  dataType:'text',
			  success:function(str){
				 if(str == "s"){
					 
					 alert("请不要非法提交！");
				 }
				 if(str == "ok"){
					 alert("已成功批量冻结所勾选的商户！");
					 location.href = location.href;
					 }
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
		////////////////////////////////////////////////////////
	   }
    });
	
	
	$("#jiedong").click(function(e) {    //解冻账户
        if(confirm("您确认要批量解冻您勾选的商户吗？")){
         var listcheckbox = "0";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + "," + $(this).val();
		     }
        });
		
		/////////////////////////////////////////////////////////
		$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_Dongjie_status_1.html',
			  data:"UserIDList="+listcheckbox,
			  dataType:'text',
			  success:function(str){
				 if(str == "s"){
					 
					 alert("请不要非法提交！");
				 }
				 if(str == "ok"){
					 alert("已成功批量解冻所勾选的商户！");
					 location.href = location.href;
					 }
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
		////////////////////////////////////////////////////////
	   }
    });
	
	
	$("#scmbkxz").click(function(e) {
		var listcheckbox = "";
        if(confirm("您确认要清除所选账户的密保卡限制吗？")){
         
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + "," + $(this).val();
		     }
        });
		
		if(listcheckbox == ""){
			alert("没有选择账户！");
			location.href = location.href;
		}else{
			////////////////////////////////////////////////////////
			listcheckbox = "0"+listcheckbox;
			$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_jiechumbk.html',
			  data:"UserIDList="+listcheckbox,
			  dataType:'text',
			  success:function(str){
				 if(str == "ok"){
					 alert("已成功批量清除所选账户密保卡限制！");
					 location.href = location.href;
					 }
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
			
			///////////////////////////////////////////////////////
		}
	   }
    });
	
	$("#shanchu").click(function(e) {
        if(confirm("您确认要批量删除您勾选的商户吗？")){
			if(confirm("如果商户删除后，所有相关的信息都会删除，请谨慎此操作")){
				if(confirm("请最后一次确认删除操作")){
         var listcheckbox = "0";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + "," + $(this).val();
		     }
        });
		
		/////////////////////////////////////////////////////////
		$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_Deletesh.html',
			  data:"UserIDList="+listcheckbox,
			  dataType:'text',
			  success:function(str){
				 if(str == "ok"){
					 alert("已成功批量删除所勾选的商户！");
					 location.href = location.href;
					 }
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
		////////////////////////////////////////////////////////
				}
			}
	   }
    });
	
	
	$("#czdlmm").click(function(e) {    //重置登录密码
        if(confirm("您确认要批量重置您勾选的商户的登录密码吗？")){
         var listcheckbox = "";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + $(this).val() + "|";
		     }
        });
		if(listcheckbox == ""){
			alert("没有可重置登录密码的商户");
			location.href = location.href;
		}else{
			arraylist = listcheckbox.split("|");
			plczdlmm(0,arraylist);
		
		}
	   }
    });
	
	
	$("#czzfmm").click(function(e) {    //重置支付密码
        if(confirm("您确认要批量重置您勾选的商户的支付密码吗？")){
         var listcheckbox = "";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + $(this).val() + "|";
		     }
        });
		if(listcheckbox == ""){
			alert("没有可重置支付密码的商户");
			location.href = location.href;
		}else{
			arraylist = listcheckbox.split("|");
			plczzfmm(0,arraylist);
		
		}
	   }
    });
	
	
	$("#kaitongT0").click(function(e) {
        if(confirm("您确认要批量给您勾选的商户开通T+0吗？")){
         var listcheckbox = "0";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + "," + $(this).val();
		     }
        });
		
		if(listcheckbox == "0"){
			alert("您没有勾选要开通T+0的商户！");
			location.href = location.href;
			}else{
		/////////////////////////////////////////////////////////
		$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_KaiTongT0.html',
			  data:"UserIDList="+listcheckbox,
			  dataType:'text',
			  success:function(str){
				 if(str == "ok"){
					 alert("已成功批量给您勾选的商户开通T+0！");
					 location.href = location.href;
					 }else{
						  alert(str);
						 }
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
		////////////////////////////////////////////////////////
			}
	   }
    });
	
	$("#kaitongT1").click(function(e) {
        if(confirm("您确认要批量给您勾选的商户关闭T+0吗？")){
         var listcheckbox = "0";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + "," + $(this).val();
		     }
        });
		
		if(listcheckbox == "0"){
			alert("您没有勾选要关闭T+0的商户！");
			location.href = location.href;
			}else{
		/////////////////////////////////////////////////////////
		$.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_KaiTongT1.html',
			  data:"UserIDList="+listcheckbox,
			  dataType:'text',
			  success:function(str){
				 if(str == "ok"){
					 alert("已成功批量给您勾选的商户关闭T+0！");
					 location.href = location.href;
					 }else{
						  alert(str);
						 }
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
		////////////////////////////////////////////////////////
			}
	   }
    });
	
	
	$("#wytd").click(function(e) {
        var listcheckbox = "";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + $(this).val() + ",";
		     }
        });
		
		listcheckbox = listcheckbox+"0";
		
		if(listcheckbox == "0"){
			alert("您没有勾选需要设置通道的商户！");
			location.href = location.href;
			}else{
	    var sheight = "350px";
        var swidth = "980px";
   
var k = window.showModalDialog("/SjtAdminSjt_ShangHu_PayBank_UserIDList_"+listcheckbox+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
			}
	    
    });
	
	$("#fstz").click(function(e) {
        var listcheckbox = "";
	     $(".xzxz").each(function(index, element) {
			 if($(this).attr("checked")){
           
				 listcheckbox = listcheckbox + $(this).val() + ",";
		     }
        });
		
		listcheckbox = listcheckbox+"0";
		
		if(listcheckbox == "0"){
			alert("您没有勾选要批量发送通知的商户！");
			location.href = location.href;
			}else{
	    var sheight = "550px";
        var swidth = "980px";
   
var k = window.showModalDialog("/SjtAdminSjt_ShangHu_Fstz_UserIDList_"+listcheckbox+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
			}
	    
    });
	
});


function plczzfmm(listid,arraylist){
	if(listid < arraylist.length){
		
		 $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_PayEmail.html',
			  data:"UserID="+arraylist[listid],
			  dataType:'text',
			  success:function(str){
				     listid = listid + 1;
				     plczzfmm(listid,arraylist);
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
	}else{
		alert("已成功批量重置您勾选的商户的支付密码为123456！");
	    location.href = location.href;
	}
}


function plczdlmm(listid,arraylist){
	if(listid < arraylist.length){
		
		 $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_DengluEmail.html',
			  data:"UserID="+arraylist[listid],
			  dataType:'text',
			  success:function(str){
				     listid = listid + 1;
				     plczdlmm(listid,arraylist);
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
	}else{
		alert("已成功批量重置您勾选的商户的登录密码为123456！");
	    location.href = location.href;
	}
}

function plsh(listid,arraylist){
	//////////////////////////////////////////////
	
	if(listid < arraylist.length){
		
		 $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_shjkshtg.html',
			  data:"UserID="+arraylist[listid],
			  dataType:'text',
			  success:function(str){
				     listid = listid + 1;
				     plsh(listid,arraylist);
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });
	}else{
		alert("批量审核成功！");
		location.href = location.href;
	}
		/////////////////////////////////////////////
}

function dakai(id){
	 var sheight = "350px";
    var swidth = "500px";
   
var k = window.showModalDialog("/SjtAdminSjt_ShangHu_ShowShenhe_UserID_"+id+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
	    
		
	}
	

function editusername(id){
	 var sheight = "550px";
    var swidth = "700px";
   
var k = window.showModalDialog("/SjtAdminSjt_ShangHu_ShowEdit_UserID_"+id+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
}	


function sxf(id){
	 var sheight = "430px";
    var swidth = "900px";
   
var k = window.showModalDialog("/SjtAdminSjt_ShangHu_Sxf_UserID_"+id+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
	}	
function sxfs(id){
	 var sheight = "430px";
    var swidth = "900px";
   
var k = window.showModalDialog("/SjtAdminSjt_ShangHu_Sxfs_UserID_"+id+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
	}	
	
function xgje(id){
	
	 var sheight = "350px";
    var swidth = "500px";
   
var k = window.showModalDialog("/SjtAdminSjt_ShangHu_xgje_UserID_"+id+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
}	

function tksz(id){
	
	
	 var sheight = "350px";
    var swidth = "800px";
   
var k = window.showModalDialog("/SjtAdminSjt_ShangHu_tksz_UserID_"+id+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
	
}


function tkyh(id){
	
	
	 var sheight = "350px";
    var swidth = "500px";
   
var k = window.showModalDialog("/SjtAdminSjt_ShangHu_tkyh_UserID_"+id+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
	
}


function ddsz(id){
	
	 var sheight = "420px";
    var swidth = "500px";
   
var k = window.showModalDialog("/SjtAdminSjt_ShangHu_Diaodan_UserID_"+id+".html?aaa="+ Math.random(),window,'dialogWidth:'+swidth+'px;dialogHeight:'+sheight+'px;edge:raised;resizable:no;scroll:no;status:no;center:yes;help:no;minimize:no;maximize:no;fullscreen:no;');
    
	location.href = location.href;
}