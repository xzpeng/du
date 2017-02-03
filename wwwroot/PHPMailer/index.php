<?php
require_once('phpmailer.class.php');
require_once('smtp.class.php');
$mailer = new PHPMailer(); 
$mailer->CharSet = "UTF-8";
$mailer->ContentType = 'text/html';
$mailer->IsSMTP();
//0是不输出调试信息
//2是输出详细的调试信息
$mailer->SMTPDebug  = 2;
//需要验证
$mailer->SMTPAuth = true;
$mailer->SMTPSecure = 'ssl';
$mailer->Host = 'smtp.qq.com';
$mailer->Port = '465';
$mailer->Username = '172025210@qq.com';
$mailer->Password = 'a10251219';
$mailer->SetFrom('172025210@qq.com','盛捷通');
$mailer->AddReplyTo("172025210@qq.com","盛捷通");
$mailer->AddAddress('zyzyzzy@vip.qq.com',"孙悟空");
$mailer->Subject = '盛捷通账号激活邮件';
$mailer->MsgHTML('<a href="http://www.qq.com/" target="_blank">点击</a>');
echo  $mailer->send();