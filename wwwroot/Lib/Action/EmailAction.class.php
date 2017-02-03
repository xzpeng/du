<?php

class EmailAction extends Action{
	protected $Email_Host = "smtp.qq.com";
   // protected $Email_Host = "smtp.exmail.qq.com";
	protected $Email_Port = "465";
	//protected $Email_UserName = "admin@88china.com";
	//protected $Email_PassWord = "88china";
    protected $Email_UserName = "450816947@qq.com";
    protected $Email_PassWord = "zhouheiya1";
	
	public function zylwj(){
		return "123123123";
	}
	
	public function SendEmail($SendAddress, $Subject, $MsgHTML){
		//import("@.Action.phpmailer");
		//import("@.Action.smtp");
		////////////////////////////////////////////
		require_once('phpmailer.class.php');
		require_once('smtp.class.php');
		$mailer = new PHPMailer(); 
		$mailer->CharSet = "UTF-8";
		$mailer->ContentType = 'text/html';
		$mailer->IsSMTP();
		//0是不输出调试信
		//2是输出详细的调试信息
		$mailer->SMTPDebug  = 0;
		//需要验证
		$mailer->SMTPAuth = true;
		$mailer->SMTPSecure = 'ssl';
		$mailer->Host = $this->Email_Host;
		$mailer->Port = $this->Email_Port;
		$mailer->Username = $this->Email_UserName;
		$mailer->Password = $this->Email_PassWord;
		$mailer->SetFrom($this->Email_UserName,C("WEB_NAME"));
		$mailer->AddReplyTo($this->Email_UserName,C("WEB_NAME"));
		$mailer->AddAddress($SendAddress,$SendAddress);
		$mailer->Subject =  $Subject;
		$mailer->MsgHTML($MsgHTML);
		return $mailer->send();
		///////////////////////////////////////////
		}
	
}

?>