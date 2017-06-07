<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<title>商户登录-<?php echo C("WEB_NAME");?></title>
<link href="static/css/bootstrap.min.css" rel="stylesheet" /> 
<link href="static/css/index.min.css" rel="stylesheet" />
<script src="/Public/js/jquery.min.js"></script>
<script type="text/javascript" src="/Public/js/floatDiv.js"></script>
<script type="text/javascript" src="/Public/js/js.js"></script>
<script src="static/js/jquery.min.js" type="text/javascript"></script>
<script src="static/js/index.min.js" type="text/javascript"></script>
<style type="text/css">
body{background:url(static/images/wallpaper7.jpg) repeat-y left top;}
.in-login{left:50%;margin-left:-160px;top:30%;}
</style>


<script src="http://libs.baidu.com/jquery/1.10.0/jquery.min.js"></script>
<script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" src="/Public/js/floatDiv.js"></script>
<script type="text/javascript" src="/Public/js/js.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
	$("#mbkcontent").floatdiv("middle");
    $(".menu_div_div:eq(2)").css({"background-image":"url(/Public/images/sbgb.jpg)"}).attr("name","a");
	$(".menu_div_div:eq(2) a").css("color","#11638b");
	
});
</script>




</head>
<body>
<header>
<div class="logo">
  <a href="http://du.pengxiaozhou.com/"><img src="static/picture/logo.png" alt="我的网站"class="img-responsive" />
  </a>
        </div>
  <div class="hotline"> </div>
  <div class="menu-icon"> <a href="tel:0817-6686753" title="点击直拨"><span class="glyphicon glyphicon-earphone"></span></a> <span class="glyphicon glyphicon-th-large"></span> </div>
</header>
    <div class="welcome"></div>
    <div class="quality1">
  <div class="box">
    <div class="caption"> <i></i><span></span> <br class="clear" />
    </div>
    <div class="items">
    <div class="in-login">  
    <form class="form-horizontal" name="Form1" method="post" action="" onsubmit="return check();">
           <input type="hidden" name="mbk" id="mbk" value="">
          <div class="in-login-con">
            <div class="zhdl">
              <strong>欢迎登陆</strong>
              <strong><p id="corp_errtips" class="error"></p></strong>
            </div>
            <div class="dlk-box">
              <span class="input-icon1"></span>
              
              <input type="text" name="UserName" id="UserName" class="dlk2 input-bg1" placeholder="请输入您注册填写的邮箱" />
            </div><br>
            <div class="dlk-box">
              <span class="input-icon2"></span>
              <input type="password" name="LoginPassWord"  id="LoginPassWord" class="dlk2 input-bg2" placeholder="请输入登陆密码" />
            </div><br>

            <div class="dlan">
              
              <button  id="corp_loginbtn" class="dlan-input" onclick="return corp_loginbtn_onclick()">登录</button>
            </div>                        
          </div>
          <input type="hidden" name="UserTpye" id="UserType" value="2" />
        </form>   
      </div>
      </div>
      </div>
      </div>



</body>
</html>