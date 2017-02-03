var obj = {
	1 : "5,10,15,30,50,100",   //天宏一卡通
	2 : "15,30,50,100",     //完美一卡通
	3 : "10,15,20,30,50",    //网易一卡通
	4 : "20,30,50,100,300",    //联通充值卡
	5 : "5,10,15,20,25,30,50,100",   //久游一卡通
	6 : "5,10,15,30,60,100,200",    //QQ币充值卡
	7 : "5,15,30,40,100",      //搜狐一卡通
	8 : "10,20,30,50,60,100,300",    //征途游戏卡
	9 : "5,6,10,15,30,50,100",    //骏网一卡通
	10 : "5,10,30,35,45,50,100,350",     //盛大一卡通
	11 : "10,20,30,50,100,300",    //全国神州行
	12 : "10,20,30,40,50,60,70,80,90,100",   //天下一卡通
	13 : "50,100",   //电信充值
	15 : "10,15,30,50,100"  //纵游一卡通
	
	}

$(document).ready(function(e) {
	
		$("#gx").click(function(e) {
		if($(this).attr("checked") == "checked"){
		    $("#mypayimgtj").removeClass("mypaytj");
			$("#mypayimgtj").click(function(e) {
                mypaytj();
            });
		}else{
			$("#mypayimgtj").addClass("mypaytj");
			$("#mypayimgtj").unbind("click");
			}
			
	});
	
	///////////////////////////////////////////////////////////
	  $("#mypayimgtj").removeClass("mypaytj");
			$("#mypayimgtj").click(function(e) {
                mypaytj();
            });
	//////////////////////////////////////////////////////////
	$("#pd_FrpId_b").change(function(e) {
        var  id = $(this).val();
		text = $(this).find("option:selected").text();
		$("#Sjt_ProudctID").text("");
		var arrayval = obj[id].split(",");
		$("#Sjt_ProudctID").append("<option value=''>请选择</option>");
		for(var i = 0; i < arrayval.length; i++){
			$("#Sjt_ProudctID").append("<option value='"+ arrayval[i] +"'>"+ text +"("+ arrayval[i] +"元)</option>");
		}
		//alert(arrayval[0]);
    });
	
	$("#Sjt_ProudctID").change(function(e) {
        var money = $(this).val();
		$("#fkmoney").val(money);
    });
	
	
	$("input[name='Sjt_Paytype']").click(function(e) {
        if($(this).attr("checked") == "checked"){
		
		     $(".b").hide();
			 $(".g").hide();
			 $("."+ $(this).attr("zy")).show();
		}
    });
	
	$("input[name='Sjt_Paytype']").next("span").click(function(e) {
        $(this).prev("input").attr("checked","checked").click();
    });
	
	$("input[name='Sjt_Paytype']:eq(0)").click();

});

function mypaytj(){
	
	Sjt_Paytype = $("#g").attr("checked")=="checked"?"g":"b";
	
	//alert(Sjt_Paytype);
	
	if(Sjt_Paytype == "g"){
		
		pd_FrpId = $("#pd_FrpId_b").val();
		if(pd_FrpId == ""){
			alert("请选择点卡！");
		    $("#pd_FrpId_b").focus();
		    return false;
		}
		
		Sjt_ProudctID = $("#Sjt_ProudctID").val();
		if(Sjt_ProudctID == ""){
			alert("请选择点卡充值面额！");
		    $("#Sjt_ProudctID").focus();
		    return false;
		}
		
		Sjt_CardNumber =  $("#Sjt_CardNumber").val();
		if(Sjt_CardNumber == ""){
			alert("点卡卡号不能为空！");
		    $("#Sjt_CardNumber").focus();
		    return false;
		}
		
		Sjt_CardPassword = $("#Sjt_CardPassword").val();
		if(Sjt_CardPassword == ""){
			alert("点卡密码不能为空！");
		    $("#Sjt_CardPassword").focus();
		    return false;
		}
		
	}else{
		pd_FrpId = $("#pd_FrpId").val();
	}
	
	money = $("#fkmoney").val();
	if(money == ""){
		alert("付款金额不能为空！");
		$("#fkmoney").focus();
		return false;
	}
	
	fksm = $("#fksm").val();
	

	

	
	$("#mypayimgtj").hide();
	$("#zzclz").show();
	
	
	
	$.ajax({
			type:'POST',
			url:"/Index_mypayyzm.html",
			data:"&xym="+xym,
			dataType:'text',
			success:function(str){
				///////////////////////////////////
				if(str == "ok"){
					window.location.href = "/Index_mypay.html?ActionName="+$("#ActionName").val()+"&money="+money+"&fksm="+fksm+"&xym="+xym+"&pd_FrpId="+pd_FrpId+"&Sjt_Paytype="+Sjt_Paytype+"&Sjt_ProudctID="+Sjt_ProudctID+"&Sjt_CardNumber="+Sjt_CardNumber+"&Sjt_CardPassword="+Sjt_CardPassword;
					}else{
						alert("校验码输入错误!");
						$("#mypayimgtj").show();
	                    $("#zzclz").hide();
						}
				///////////////////////////////////
				},
			error:function(str){
				//////////////////////////
				}	
			});
	return false;		
}

function keyPress() {  
    var keyCode = event.keyCode;  
    if ((keyCode >= 48 && keyCode <= 57))  
    {  
        event.returnValue = true;  
    } else {  
        event.returnValue = false;  
    }  
} 
