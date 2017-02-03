$(document).ready(function(e) {
    $(".zc img").click(function(e) {
        var reg = $(this).attr("reg");
		var UserType = $(this).attr("UserType");
		$(".zc").fadeOut(200,function(){
			$("#regdiv input[type='image']").attr("src",reg);
			$("#UserType").val(UserType);
			$("#regdiv").fadeIn(200);
			})
    });
	
	$(".inputtext").focus(function(e) {
		$(".inputtext").removeClass("input_text");
        $(this).addClass("input_text");
    });
	
	
});

function fanhui(){
	$("#regdiv").fadeOut(200,function(){
		$("#regdiv input").val("");
		$(".zc").fadeIn(200);
		});
	}


function check(){
	$(".errordiv").hide().text("");
	$.ajax({
			type:'POST',
			url:"/Index_reg.html",
			data:"UserName="+ $("#UserName").val() + "&LoginPassWord=" + $("#LoginPassWord").val() + "&OkPassWord=" + $("#OkPassWord").val() + "&verify=" + $("#verify").val() + "&UserType=" + $("#UserType").val() + "&ajaxkey=ok&SjUserID="+ $("#SjUserID").val(),
			dataType:'text',
			success:function(str){
				//alert(str);
				//if(str != "ok"){
				//	str = str.split("_");
					//$(".errordiv").hide().text("");
				//	$(".inputtext:eq("+ str[1] +")").focus();
					//$(".errordiv:eq("+ str[1] +")").text(str[0]).show();
				//}else{
					//location.href = "/Index_SucceedReg.html";
					window.location.href = $.trim(str);
					//}
				///////////////////////////////////
				},
			error:function(str){
				//////////////////////////
				}	
			});
			
		return false;	
	
	}
	
function plfsjh(arraylist){
	
		
		 $.ajax({
			  type:'POST',
			  url:'/SjtAdminSjt_ShangHu_JiHuoEmail.html',
			  data:"UserID="+arraylist,
			  dataType:'text',
			  success:function(str){
				    alert("已成功重新发送激活邮件，请意查收！");
				  },
			  error:function(){
				  alert("处理失败！");
				  }	
			  });

	
}