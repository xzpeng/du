// JavaScript Document
function keyPress() {  
    var keyCode = event.keyCode;  
    if ((keyCode >= 48 && keyCode <= 57))  
    {  
        event.returnValue = true;  
    } else {  
        event.returnValue = false;  
    }  
}  

function tjkttjl(){    //提交开通T+0

	if($("#zfmm").val() == ""){
			//alert("支付密码不能为空！");
			showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码不能为空!</span>","申请开通T+0", 500);
			$("#zfmm").focus();
			return false;
			}
		$.ajax({
		  type:'POST',
		  url:'/User_Index_yzzfmm.html',
		  data:"paypassword="+$("#zfmm").val(),
		  dataType:'text',
		  success:function(str){
				  if(str != "ok"){
					  //alert("支付密码错误！");
					  showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码错误!</span>","申请开通T+0", 500);
					  $("#zfmm").focus();
				  }else{
					////////////////////////////////////////////////////////////////////////////////////////////////////
		
					$.ajax({
						type:'POST',
						url:'/User_Index_sqtjlok.html',
						data: "paypassword="+$("#zfmm").val(),
						dataType:'text',
						success:function(str){
							switch(parseInt(str)){
								case 1:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>余额不足!</span>","申请开通T+0", 500);
								break;
								case 2:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码错误!</span>","申请开通T+0", 500);
								break;
								case 3:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>您已购买过T+0服务!</span>","申请开通T+0", 500);
								break;
								case 4:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>已成功购买T+0服务!</span>","申请开通T+0", 500);
								break;
								}
//showDialog("info", "<span style='color:#f00; font-size:20px;'>"+str+"</span>","申请开通T+0", 500);
						},
						error:function(){
							alert("处理失败！");
							}	
						});
														  
		///////////////////////////////////////////////////////////////////////////////////////////////////								
					  
					  }
			  },
		  error:function(){
			  alert("处理失败！");
			  }	
		  });	
	}
	
	
	
function tjwyfljl(){    //调整费率

	if($("#zfmm").val() == ""){
			//alert("支付密码不能为空！");
			showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码不能为空!</span>","费率调整", 500);
			$("#zfmm").focus();
			return false;
			}
		$.ajax({
		  type:'POST',
		  url:'/User_Index_yzzfmm.html',
		  data:"paypassword="+$("#zfmm").val(),
		  dataType:'text',
		  success:function(str){
				  if(str != "ok"){
					  //alert("支付密码错误！");
					  showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码错误!</span>","费率调整", 500);
					  $("#zfmm").focus();
				  }else{
					////////////////////////////////////////////////////////////////////////////////////////////////////
		            
					  $("input[name='fltype']").each(function(index, element) {
                        if($(this).attr("checked") == "checked"){
							fl = $(this).attr("fl");
							jemoney = $(this).attr("money");
						}
                    });
					
					$.ajax({
						type:'POST',
						url:'/User_Index_gmflok.html',
						data: "paypassword="+$("#zfmm").val()+"&fl="+fl+"&jemoney="+jemoney,
						dataType:'text',
						success:function(str){
							switch(parseInt(str)){
								case 1:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>余额不足!</span>","费率调整", 500);
								break;
								case 2:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码错误!</span>","费率调整", 500);
								break;
								case 3:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>您已购买过T+0服务!</span>","费率调整", 500);
								break;
								case 4:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>已成功购买T+0服务!</span>","费率调整", 500);
								break;
								}
//showDialog("info", "<span style='color:#f00; font-size:20px;'>"+str+"</span>","申请开通T+0", 500);
						},
						error:function(){
							alert("处理失败！");
							}	
						});
														  
		///////////////////////////////////////////////////////////////////////////////////////////////////								
					  
					  }
			  },
		  error:function(){
			  alert("处理失败！");
			  }	
		  });	
	}
	
	
function wtplfxjl(){    //委托批量下发

	if($("#zfmm").val() == ""){
			//alert("支付密码不能为空！");
			showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码不能为空!</span>","委托批量下发", 500);
			$("#zfmm").focus();
			return false;
			}
		$.ajax({
		  type:'POST',
		  url:'/User_Index_yzzfmm.html',
		  data:"paypassword="+$("#zfmm").val(),
		  dataType:'text',
		  success:function(str){
				  if(str != "ok"){
					  //alert("支付密码错误！");
					  showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码错误!</span>","委托批量下发", 500);
					  $("#zfmm").focus();
				  }else{
					////////////////////////////////////////////////////////////////////////////////////////////////////
		
					$.ajax({
						type:'POST',
						url:'/User_Index_wtplxfok.html',
						data: "paypassword="+$("#zfmm").val(),
						dataType:'text',
						success:function(str){
							switch(parseInt(str)){
								case 1:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>余额不足!</span>","委托批量下发", 500);
								break;
								case 2:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码错误!</span>","委托批量下发", 500);
								break;
								case 3:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>您已购买过委托批量下发服务!</span>","委托批量下发", 500);
								break;
								case 4:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>已成功购买委托批量下发服务!</span>","委托批量下发", 500);
								break;
								}
//showDialog("info", "<span style='color:#f00; font-size:20px;'>"+str+"</span>","申请开通T+0", 500);
						},
						error:function(){
							alert("处理失败！");
							}	
						});
														  
		///////////////////////////////////////////////////////////////////////////////////////////////////								
					  
					  }
			  },
		  error:function(){
			  alert("处理失败！");
			  }	
		  });	
	}
	
	
function zdjsjl(){    

	if($("#zfmm").val() == ""){
			//alert("支付密码不能为空！");
			showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码不能为空!</span>","自动结算", 500);
			$("#zfmm").focus();
			return false;
			}
		$.ajax({
		  type:'POST',
		  url:'/User_Index_yzzfmm.html',
		  data:"paypassword="+$("#zfmm").val(),
		  dataType:'text',
		  success:function(str){
				  if(str != "ok"){
					  //alert("支付密码错误！");
					  showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码错误!</span>","自动结算", 500);
					  $("#zfmm").focus();
				  }else{
					////////////////////////////////////////////////////////////////////////////////////////////////////
		
					$.ajax({
						type:'POST',
						url:'/User_Index_zdjsok.html',
						data: "paypassword="+$("#zfmm").val()+"&txmoney="+$("#txmoney").val(),
						dataType:'text',
						success:function(str){
							switch(parseInt(str)){
								case 1:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>余额不足!</span>","自动结算", 500);
								break;
								case 2:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>支付密码错误!</span>","自动结算", 500);
								break;
								case 3:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>您已购买过自动结算服务!</span>","自动结算", 500);
								break;
								case 4:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>已成功购买自动结算服务!</span>","自动结算", 500);
								break;
								}
//showDialog("info", "<span style='color:#f00; font-size:20px;'>"+str+"</span>","申请开通T+0", 500);
						},
						error:function(){
							alert("处理失败！");
							}	
						});
														  
		///////////////////////////////////////////////////////////////////////////////////////////////////								
					  
					  }
			  },
		  error:function(){
			  alert("处理失败！");
			  }	
		  });	
	}	
	
	
function zdjsxg(){
	$.ajax({
						type:'POST',
						url:'/User_Index_zdjsxgok.html',
						data: "txmoney="+$("#txmoney").val(),
						dataType:'text',
						success:function(str){
							switch(parseInt(str)){
				
								case 4:
								showDialog("info", "<span style='color:#f00; font-size:20px;'>已成功修改自动结算金额!</span>","自动结算", 500);
								break;
								}
						},
						error:function(){
							alert("处理失败！");
							}	
						});
}	