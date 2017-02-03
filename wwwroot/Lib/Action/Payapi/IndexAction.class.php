<?php
class IndexAction extends Action{
    public function index(){
        
        $this->display();
    }
   
    public function Pay(){
       
	   
	   
	      
       header("Content-Type:text/html; charset=utf-8");
        
       $Sjt_Paytype = $this->_request("Sjt_Paytype");
       
       
       if($Sjt_Paytype == NULL || $Sjt_Paytype == ""){

  
           $this->CompatibleApi();
       }else{
           
           if($Sjt_Paytype == "b"){
               
               $UserID = intval($this->_request("p1_MerId")) - 10000;
               
               $Usersafetyinformation = M("Usersafetyinformation");
               
               $PayBank=intval($this->_request("PayBank"));
               if(!$PayBank || $PayBank==''){
                  $PayBank = $Usersafetyinformation->where("UserID = ".$UserID)->getField("PayBank");
               }
               if($PayBank == 0 || $PayBank == NULL){
                   
                   $System = M("System");
               
                   $DefaultBank = $System->where("UserID=0")->getField("DefaultBank"); 
               }else{
                   
                   $DefaultBank = $PayBank;
               }
               
               if(intval($PayBank) == 10001){
                   $payname = "Yinlian";
               }else{
                   $Sjapi = M("Sjapi");
                   
                   $payname = $Sjapi->where("id=".$DefaultBank)->getField("payname");
               }
               
               //echo $payname."<br>";
               if($payname == "Qiling"){
                   $payname = "QilingBank";
               }
               
               if( $_POST['PayBank']==21){

                 	$payname  ='QQbao'; //qq钱包
					
				
                 	R("Payapi/".$payname."/Post",$this->_request("typego"));//pc 或者mobile
                 	
               }
			   
			    if( $_POST['PayBank']==22){

                 	 $payname  ='Wxdemo'; //wxdemo
                 	 R("Payapi/".$payname."/Post",$this->_request("typego"));// pc
					
               }
               
               
               
               
               R("Payapi/".$payname."/Post");//Alipay或WftWx
             // exit("Payapi/".$payname."/Post"); 
               
           }else{
               
               if($Sjt_Paytype == "g"){
                   $gameid = $this->_request("pd_FrpId");
                   $Gamepay = M("Gamepay");
                   $payname = $Gamepay->where("sjt='".$gameid."'")->getField("default");
                  // exit("--".$gameid."--");
                   switch($payname){
                       case "baofu":
                       R("Payapi/BaoFu/Post");
                       break;
                       case "qiling":
                       R("Payapi/Qiling/Post");
                       break;
                       case "yibao":
                       R("Payapi/YibaoGame/Post");
                       break;
                       default:
                       echo $payname;
                   }
                   
               }else{
                    exit("<script language='javascript'>alert('请不要非法提交！[".$Sjt_Paytype."]'); location.href='http://".C("WEB_URL")."';</script>");
                    
               }
           }
       }
    }
    
    
    public function success(){
        
        $this->assign("msgTitle","");
        $this->assign("message","充值成功！");
        $this->assign("waitSecond",3);
        $this->assign("jumpUrl","User_Index.html");
        $this->display();
    }
    
    private function CompatibleApi(){
           $ArrayQiLing = array("P_UserId","P_OrderId","P_FaceValue");
           if($this->gjzpd($ArrayQiLing)){
               
               $p0_Cmd = "Buy";
               
              $p1_MerId = $this->_request("P_UserId");   
              $p2_Order = $this->_request("P_OrderId");  //商户编号
              
              $p3_Amt = $this->_request("P_FaceValue");  //交易金额
              
              $p4_Cur = "CNY";
              
              $p5_Pid = "10086";
              
              $p6_Pcat = "10086";
              
              $p7_Pdesc = "NULL";
              
              $p8_Url = urldecode($this->_request("P_Result_url"));
              
              $p9_SAF = "0";
              
              $pa_MP = "0";
              
              $pd_FrpId = "zsyh";
             
              $pr_NeedResponse = urldecode($this->_request("P_Notify_url"));
              
              $Sjt_Paytype = "b";
              
              $Sjt_UserName = urldecode($this->_request("P_Notic"))."~|".urldecode($this->_request("P_CardId"))."~|".urldecode($this->_request("P_Description"))."~|".urldecode($this->_request("P_Subject"));
              
              $Sjt_CardNumber = "";
              
              $Sjt_CardPassword = "";
              
              $Sjt_ProudctID = "";
                 
              
              $Userapiinformation = M("Userapiinformation");
              
              $key = $Userapiinformation->where("UserID=".(intval($p1_MerId)-10000))->getField("key");
              
              $User = M("User");
              
              
            //  $Sjt_UserName = $Username;
             $Sjt_UserName = urlencode($Sjt_UserName);
             //echo $Sjt_UserName;
              
              $tjurl = "http://".C("WEB_URL")."/Payapi_Index_Pay.html";
              
                   
                  $hmacstr = $p0_Cmd.$p1_MerId.$p2_Order.$p3_Amt.$p4_Cur.$p5_Pid.$p6_Pcat.$p7_Pdesc.$p8_Url.$p9_SAF.$pa_MP.$pd_FrpId.$pr_NeedResponse.$key;
                  $hmac = md5($hmacstr);
            $tjurla = $_SERVER["HTTP_REFERER"];

            
            
            $this->assign('tjurl',$tjurl);
            $this->assign('p1_MerId',$p1_MerId);
            $this->assign('p2_Order',$p2_Order);
            $this->assign('p3_Amt',$p3_Amt);
            $this->assign('p5_Pid',$p5_Pid);
            $this->assign('p6_Pcat',$p6_Pcat);
            $this->assign('p7_Pdesc',$p7_Pdesc);
            $this->assign('p8_Url',$p8_Url);
            $this->assign('pa_MP',$pa_MP);
            $this->assign('pd_FrpId',$pd_FrpId);
            $this->assign('pr_NeedResponse',$pr_NeedResponse);
            $this->assign('Sjt_UserName',$Sjt_UserName);
            $this->assign('hmac',$hmac);
            $this->assign('tjurla',$tjurla);
            
			
$theusagt = $_SERVER["HTTP_USER_AGENT"];
//echo $theusagt.'<br>';
$is_mobile = false;
if(stripos($theusagt , "iPhone") !== false || stripos($theusagt , "iPod") !== false){
    //$thetargetsite = $siteurl_mobile;
    $is_mobile = true;
}
else if(stripos($theusagt , "Mac OS") !== false){
    //$thetargetsite = $siteurl_pc;
    $is_mobile = false;
}
else if(stripos($theusagt , "Mobile") !== false){
    //$thetargetsite = $siteurl_mobile;
    $is_mobile = true;
}
else if(stripos($theusagt , "Android") !== false){
    //$thetargetsite = $siteurl_pc;
    $is_mobile = false;
}
else if(stripos($theusagt , "Windows Phone") !== false){
    //$thetargetsite = $siteurl_mobile;
    $is_mobile = true;
}
else {
    //$thetargetsite = $siteurl_pc;
    $is_mobile = false;
}
 
 if($is_mobile)
 {
	 
	 $this->display('prepay_two');
            
 }else
 {
	 $this->display('prepay');
 }
            
            
			
			
            
           }else{
               exit("<script language='javascript'>alert('请不要非法提交！![".$Sjt_Paytype."]'); location.href='http://".C("WEB_URL")."';</script>");
           }
           
       
    }
    
    private function gjzpd($ArrayList){
        foreach($ArrayList as $key => $value){
            if($this->_request($value) == ""){
                
                break;
                return false;
                
            }
        }
        return true;
    }
}
?>
