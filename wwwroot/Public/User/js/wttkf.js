$(document).ready(function(e) {
	
	 $("#banklist").floatdiv("middle");
	
   $("#menu div").addClass("menu_bg_y");
	$("#menu div:eq(3)").addClass("menu_bg");
	
	$(".moneyclass").blur(function(e) {
        
		var id = $(this).attr("moneyid");
		if($(this).val() != ""){
		
		    var srmoney = parseFloat($(this).val());
			
			var minmoney = parseFloat($("#minmoney").val());
			
			var maxmoney = parseFloat($("#maxmoney").val());
			
			var mtsxmoney = parseFloat($("#mtsxmoney").val());
			
			var mymoney = parseFloat($("#mymoney").val());
			
			var yqlmoney = parseFloat($("#yqlmoney").val());
			
			if(srmoney < minmoney){
				alert("提款金额不能小于"+minmoney+"元");
				$(this).focus();
				$(this).val("");
				return false;
			}
			
			if(srmoney > maxmoney){
				alert("提款金额不能大于"+maxmoney+"元");
				$(this).focus();
				$(this).val("");
				return false;
			}
			
			var hjmoney = 0;
			$(".moneyclass").each(function(index, element) {
                if($(this).val() != ""){
					hjmoney = hjmoney + parseFloat($(this).val());
				}
            });
			
			if(yqlmoney+hjmoney > mtsxmoney){
				alert("每天的提款总额不能超过"+mtsxmoney+"元");
				$(this).focus();
				$(this).val("");
				return false;
			}
			
			if(hjmoney > mymoney){
			
			    alert("余额不足！");
				$(this).focus();
				$(this).val("");
				return false;
			}
			
			/*if(parseInt(srmoney/100)*100 != srmoney){
			
			    alert("提款金额只能是100的整数倍！");
				$(this).focus();
				$(this).val("");
				return false;
			}*/
			
			
			var T = $("#T"+id).val();
			
			//////////////////////////////////////////////////////
			$.ajax({
					type:'POST',
					url:'/User_Index_tkjsfl.html',
					data:"tkmoney="+srmoney+"&T="+T,
					dataType:'text',
					success:function(str){
						 str = str.split("|")
					     if(str[1] != "ok"){
							// alert(str[1]);
							 }else{
					         	$("#sxf_money"+id).text(str[0]);
								$("#sj_money"+id).text(srmoney - parseFloat(str[0]));
								//////////////////////////////////////////////////////////////////////
								$.ajax({
								  type:'POST',
								  url:'/User_Index_editbank.html',
								  data:"tk_money="+srmoney+"&sxf_money="+str[0]+"&sj_money="+(srmoney - parseFloat(str[0]))+"&id="+id+"&tk_if=1&T="+T,
								  dataType:'text',
								  success:function(str){
									   if(str != "ok"){
										   alert("系统错误!addbank");
										   }
									  },
								  error:function(){
									  alert("处理失败！");
									  }	
								  });
								/////////////////////////////////////////////////////////////////////
							 }
						},
					error:function(){
						alert("处理失败！");
						}	
					});
			/////////////////////////////////////////////////////
			
		}else{
			
			$.ajax({
		  type:'POST',
		  url:'/User_Index_editbank.html',
		  data:"tk_money=0&sxf_money=0&sj_money=0&id="+$(this).attr("moneyid")+"&tk_if=0",
		  dataType:'text',
		  success:function(str){
			   if(str != "ok"){
				   alert("系统错误!addbank");
				   }else{
					    $("#sxf_money"+id).text("");
					    $("#sj_money"+id).text("");
					   }
			  },
		  error:function(){
			  alert("处理失败！");
			  }	
		  });
			
			
			}
    });
	
	
	
	$(".TT").change(function(e) {
		
			$(this).parent().children("input[type='text']").blur();
					
        
    });
	
});	
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
	
function btk(mythis){
	if(confirm("您确认此账户不参与本次申请提款吗？")){
	    
		$.ajax({
		  type:'POST',
		  url:'/User_Index_editbank.html',
		  data:"tk_money=0&sxf_money=0&sj_money=0&id="+$(mythis).attr("moneyid")+"&tk_if=0",
		  dataType:'text',
		  success:function(str){
			   if(str != "ok"){
				   alert("系统错误!addbank");
				   }else{
					   $(mythis).parent().fadeOut(1000,function(){
						  $(mythis).parent().remove();
						  });
					   }
			  },
		  error:function(){
			  alert("处理失败！");
			  }	
		  });
		
	}
}	


function sqtk(){
	
	if($("#paypassword").val() == ""){
	
	    alert("请输入支付密码！");
		$("#paypassword").focus();
		return false;
	
	}else{
		
		
		//////////////////////////////////////////////////////////////
		$.ajax({
		type:'POST',
		url:'/User_Index_yzzfmm.html',
		data:"paypassword="+$("#paypassword").val(),
		dataType:'text',
		success:function(str){
				
				if(str != "ok"){
					alert("支付密码错误！");
					$("#paypassword").focus();
				}else{
					///////////////////////////////////////////////////////////////////////////////
					if(confirm("您确认要批量申请委托提款吗？")){
						$("#banklist").show();
						$("#sqtkbutton").hide();
						
						$.ajax({
						  type:'POST',
						  url:'/User_Index_sqtkwt.html',
						  dataType:'text',
						  success:function(str){
							   if(str != "ok"){
								  // alert("系统错误!addbank");
								 // alert(str);
								  $("#banklist").html("操作失败,"+str+"<br><img src='/Public/User/images/fh.gif' onclick='fhtk();'>");
								   }else{
									   $("#banklist").html("申请提款成功！<br><img src='/Public/User/images/fh.gif' onclick='fhtk();'>");
									   }
							  },
						  error:function(){
							  alert("处理失败！");
							  }	
						  });
					}
					//////////////////////////////////////////////////////////////////////////////
				}
				
			},
		error:function(){
			alert("处理失败！");
			}	
		});	 
		/////////////////////////////////////////////////////////////
		

		}
	
	
	
}

function fhtk(){
	
	location.href = "/User_Index_wttkf.html";
	
	}