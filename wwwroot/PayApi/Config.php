<?php
error_reporting(0); 
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set(PRC);
//平台商户ID，需要更换成自己的商户ID
//接入商户ID
$UserId='10297';
//接口密钥，需要更换成你自己的密钥，要跟后台设置的一致
//登录API平台，商户管理-->安全设置-->密钥设置，这里自己设置密钥
$SalfStr='d98c8187ca79332bc504902ca47cd10f';
//网关地址，要更新成你所在的平台网关地址
$gateWary="http://du.pengxiaozhou.com/Payapi_Index_Pay.html";
//充值结果后台通知地址
$result_url='http://du.pengxiaozhou.com/PayApi/Notify_Url.php';
//充值结果用户在网站上的转向地址
$notify_url='http://du.pengxiaozhou.com/PayApi/Result_url.php';
?>