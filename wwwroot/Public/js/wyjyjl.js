function xgtz(TransID){
	if(confirm("您确认要手动补发通知吗？") == true){
			  $.ajax({
			  type:"POST",
			  url:"/User_Index_xgtz.html",
			  data:"TransID="+TransID,
			  timeout:2000,
			  dataType:"text",
			  success: function(str){
				 if(str == "ok"){
				 	alert("补发通知成功！");
				 }else{
				    alert("补发通知失败！");
				 }
			  },
			  error:function(){
				  //alert("处理失败！");
			  }
		  });
	}
}