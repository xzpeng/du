// JavaScript Document
var ajax_return_num = 1;  //重新发送多少次
var urlname = "";     //发送的地址
var datastr = "";     //发送的数据
var TransID = "";     //订单编号
function ajax_return(){
	
	 $.ajax({
		type:'POST',
		url:"/Index_tzajax.html",
		data:"TransID="+TransID,
		timeout:2000,
		dataType:'text',
		success:function(str){
			
	    },
		complete: function (XMLHttpRequest, str) {
                    //请求完成后回调函数 (请求成功或失败时均调用)。
                    // if (textStatus != "success") {
                    //   jQuery.ligerDialog.closeWaitting();
                    // }
					if(str == "ok"){
						set_success();
						$("body").append("<div style='color:#F00'>订单"+ TransID +", 通知成功！</div>");
						get_url_data();
					}else{
						
							$("body").append("<div>订单"+ TransID +", 通知失败 "+ ajax_return_num +" 次！</div>");
							//alert(str);
							set_add_number();
						
					}
					
				 
					window.setTimeout("get_url_data()",1000);//重新获取数据
                },
		error:function(){
			////alert("处理失败！");
//			set_add_number();
//			$("body").append("<div>订单"+ TransID +", 通知失败 "+ ajax_return_num +" 次,处理错误！</div>");
//			get_url_data(); 
		}	
	});
}

function get_url_data(){   //获取要通知的数据
	
	$.ajax({
		type:"POST",
		url:"/Index_geturldata.html",
		data:"type=0",
		timeout:2000,
		dataType:"text",
		success: function(str){
			if(str != ""){
				str = str.split("^");
				urlname = str[0];
				datastr = str[1];
				TransID = str[2];
				ajax_return();
			}else{
				window.setTimeout("get_url_data()",2000);
			}
		},
		error:function(){
			//alert("处理失败！");
		}
	});
	
}


function set_add_number(){
	
	$.ajax({
		type:"POST",
		url:"/Index_addnumber.html",
		timeout:2000,
		data:"TransID="+TransID,
		dataType:"text",
		success: function(str){
			
		},
		error:function(){
			//alert("处理失败！");
		}
	});
	
}


function set_success(){
	
	$.ajax({
		type:"POST",
		url:"/Index_ordersuccess.html",
		timeout:2000,
		data:"TransID="+TransID,
		dataType:"text",
		success: function(str){
			
		},
		error:function(){
			//alert("处理失败！");
		}
	});
	
}