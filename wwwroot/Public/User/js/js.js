// JavaScript Document
$(document).ready(function(e) {
    $("input[name='pd_FrpId']").next().click(function(e) {
		$("input[name='pd_FrpId']").next().css("border","0px");
        $(this).prev().attr("checked",true);
		$(this).css("border","2px solid #f00");
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

function ygdel(id){
	     if(confirm("您确认要删此员工吗？") == true){
			 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			 $.ajax({
			type:'POST',
			url:"/User_Index_ygdel.html",
			data:"id="+id,
			dataType:'text',
			success:function(str){
				if(str != "ok"){
					alert(str)
				}else{
					alert("删除成功！");
					window.location.href = window.location.href;
					}
				///////////////////////////////////
				},
			error:function(str){
				//////////////////////////
				}	
			});
	
			 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			 }
	}