<?php

class UserModel extends Model{
	
	protected $_validate = array(
		array("UserName","require","帐户不能为空！_0",0,"regex",1),
	//	array("UserName","email","账户必须为Email地址!_0",0,"regex",1),
		array("UserName","","账户已存在！_0",0,"unique",1),
		//array("UserName","50","账户长度不能超过50个字符！",0,"length",1),
		array("LoginPassWord","require","登录密码不能为空！_1",0,"regex",3),
		array("LoginPassWord","OkPassWord","两次登录密码输入不一致！_2",0,"confirm",3),
		array("UserType",array(0,1,3),"会员类型错误，请不要非法提交！",0,"in",1),
		array("verify","require","验证码不能为空！_3",0,"regex",3),
		//array("ajaxkey","ajaxkey","ok",0,"equal",1)
	);
	
	
	protected $_auto = array(
	    array("status",0),
		array("LoginPassWord","md5",1,"function"),
		//array("RegDate","date",1,"function"),
	);
	
}

?>