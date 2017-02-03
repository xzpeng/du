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
                  
				  if(str == "ok"){
			    
				
				$("body").append("<div style='color:#F00'>订单"+ TransID +", 通知成功！</div>");
				set_success();
				get_url_data();
				
			}else{
				if(ajax_return_num <= 5){
					$("body").append("<div>订单"+ TransID +", 通知失败 "+ ajax_return_num +" 次！</div>");
					ajax_return_num = ajax_return_num + 1;
					//alert(TransID);
					set_add_number();
					ajax_return();
				}else{
					ajax_return_num = 1;
					get_url_data();  //重新获取下一条通知数据
					
				}
			}
				  
				  
                },
		
		error:function(){
			////alert("处理失败！");
//			if(ajax_return_num < 5){
//					$("body").append("<div>订单"+ TransID +", 通知失败 "+ ajax_return_num +" 次！</div>");
//					ajax_return_num = ajax_return_num + 1;
//					set_add_number();
//					ajax_return();
//				}else{
//					ajax_return_num = 1;
//					get_url_data();  //重新获取下一条通知数据
//					
//				}
		}	
	});
}

function get_url_data(){   //获取要通知的数据
	ajax_return_num = 1;
	$.ajax({
		type:"POST",
		url:"/Index_geturldata.html",
		data:"type=1",
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
				window.setTimeout("get_url_data()",5000);
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