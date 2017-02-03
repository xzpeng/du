<?php
  class ShengFuTongAction extends Action{
      
        private $Sjt_Return = 1;   //返回状态 1为正常，0为失败 
        private $Sjt_Error = "01";   //错误编号
        private $Sjt_MerchantID;     //盛捷通商户号
        private $Sjt_PayID;      //盛捷通商户支付渠道
        private $Sjt_TransID;    //盛捷通商户流水号
        Private $Sjt_ProductName;  //商品名称
        private $Sjt_OrderMoney;   //盛捷通商户订单金额
        private $Sjt_Key;          //盛捷通商户密钥
        private $Sjt_Merchant_url = "";    //盛捷通商户通知地址
        private $Sjt_Return_url = "";      //盛捷通商户底层（后台）通知地址
        
      
      
      
      public function Post(){
            header("Content-Type:text/html; charset=utf-8");
        
       $this->Sjt_Merchant_url = $this->_post("Sjt_Merchant_url");   //盛捷通商户通知地址   
       if($this->Sjt_Merchant_url == NUll || $this->Sjt_Merchant_url == ""){
           $this->Sjt_Return = 0;
           $this->Sjt_Error = 7;
           $this->ReturnUrl();
       } 
        
        
        $this->Sjt_MerchantID = $this->_post("Sjt_MerchantID");   //获取盛捷通商户号
        if($this->Sjt_MerchantID == NULL || $this->Sjt_MerchantID == ""){  //判断盛捷通商户号是否存在
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 1;
            $this->RunError();
        }
        
        $User = D("User");
        $Sjt_Name = $User->where("id=".(intval($this->Sjt_MerchantID)-10000))->getField("UserName");   //获取用户名
        if(!$Sjt_Name){
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 2;
            $this->ReturnUrl();
        }
        
        
        $this->Sjt_PayID = $this->_post("Sjt_PayID");   //获取盛捷通商户提交的支付渠道
        if($this->Sjt_PayID == NUll || $this->Sjt_PayID == ""){  //判断盛捷通商户提交的支付渠道字段是否存在
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 3;
            $this->RunError();
        }
        
        $Bankpay = M("Bankpay");
        $this->Sjt_PayID = $Bankpay->where("id=".$this->Sjt_PayID)->getField("baofu");
        if(!$this->Sjt_PayID){
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 4;
            $this->RunError();
        }
        
        $this->Sjt_OrderMoney = $this->_post("Sjt_OrderMoney");  //获取盛捷通商户提交的订单金额
        if($this->Sjt_OrderMoney == NUll || $this->Sjt_OrderMoney == ""){  //判断盛捷通商户提交的订单金额字段是否存在
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 5;
            $this->RunError();
        }
        
        $Userapiinformation = D("Userapiinformation");
        $WebsiteUrl = $Userapiinformation->where("UserID=".(intval($this->Sjt_MerchantID)-10000))->getField("WebsiteUrl");   //获取用户设置的网址
        $yuming = $_SERVER["HTTP_REFERER"];  //获取提交的网址的域名
        
       // exit($yuming);

       
       if(strstr($yuming,$WebsiteUrl) == false){    //判断提交的网址域名与用户设置的域名相同
             $this->Sjt_Return = 0;
             $this->Sjt_Error = 6;
             $this->RunError();
       } 
         
         
        $this->Sjt_Return_url = $this->_post("Sjt_Return_url");      //盛捷通商户底层（后台）通知地址
        if($this->Sjt_Return_url == Null || $this->Sjt_Return_url == ""){
           $this->Sjt_Return = 0;
           $this->Sjt_Error = 8;
           $this->ReturnUrl();
       }  
        
        $this->Sjt_Key = $Userapiinformation->where("UserID=".(intval($this->Sjt_MerchantID)-10000))->getField("Key");   //获取用户的密钥
        
        if($this->Sjt_Key != $this->_post("Sjt_Key")){    //判断盛捷通商户提交的密钥是否正确
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 9;
            $this->RunError();
        }
        
        
        $Sjapi = M("Sjapi");
        $_MerchantID = $Sjapi->where("apiname='shengfutong'")->getField("shid"); //商户ID
        $_Md5Key = $Sjapi->where("apiname='shengfutong'")->getField("key"); //密钥   
        
        $Order = M("Order");
        $id_id = $Order->order("id desc")->limit(1)->getField("id");
        $this->Sjt_TransID = $this->Sjt_MerchantID.date("Ymd").(1000000000+$id_id);
        $_TransID=$this->Sjt_TransID;//流水号
        
        $_PayID=$this->Sjt_PayID;//支付方式
        $_TradeDate = date("Ymdhis");//交易时间  date("Ymdhis");
        $_OrderMoney=$this->Sjt_OrderMoney;//订单金额
        $_ProductName=iconv('GB2312', 'UTF-8', $this->_post("Sjt_ProductName"));//产品名称
        
        $_Username=$Sjt_Name;//盛捷通支付用户名
        
        $_AdditionalInfo=iconv('GB2312', 'UTF-8', $this->_post("Sjt_AdditionalInfo"));//订单附加消息
        
        $_Merchant_url= "http://".C("WEB_URL")."/Payapi_BaoFu_MerChantUrl.html";      //商户通知地址
        
        $_Return_url= "http://".C("WEB_URL")."/Payapi_BaoFu_ReturnUrl.html";   //用户通知地址
        
        $_NoticeType= 1;//通知方式  服务器通知和页面通知。支付成功后，自动重定向到“通知商户地址”
        
       // $_Md5Sign=md5($_MerchantID.$_PayID.$_TradeDate.$_TransID.$_OrderMoney.$_Merchant_url.$_Return_url.$_NoticeType.$_Md5Key);
        
        $Order = D("Order");
        $data["UserID"] = intval($this->Sjt_MerchantID)-10000;      //商户编号
        $data["TransID"] = $this->Sjt_TransID;          //订单号
        $data["TradeDate"] = date("Y-m-d h:i:s");    //订单时间
        $data["OrderMoney"] = $this->Sjt_OrderMoney;     //订单金额
        $data["ProductName"] = $_ProductName;     //商品名称
        $data["Username"] = iconv('GB2312', 'UTF-8', $this->_post("Sjt_UserName"));     //支付用户名
        $data["AdditionalInfo"] = $_AdditionalInfo;   //订单附加信息
        $data["Sjt_Merchant_url"] = $this->Sjt_Merchant_url;     //跳转地址
        $data["Sjt_Return_url"] = $this->Sjt_Return_url;    //通知地址
        
        $Order->add($data);
        
        echo '<form name="Form1" method="post" action="http://mas.sdo.com/web-acquire-channel/cashier.htm">';
        
        echo '<input type="hidden" name="Name" value="B2CPayment">';
        echo '<input type="hidden" name="Version" value="V4.1.1.1.1">';    
        echo '<input type="hidden" name="Charset" value="UTF-8">';    
        echo '<input type="hidden" name="MsgSender" value="'.$_MerchantID.'">'; 
        $datedate = date("YmdHis");   
        echo '<input type="hidden" name="SendTime" value="'.$datedate.'">';    
        echo '<input type="hidden" name="OrderNo" value="'.$this->Sjt_TransID.'">';    
        echo '<input type="hidden" name="OrderAmount" value="'.$_OrderMoney.'">';    
        echo '<input type="hidden" name="OrdeTime" value="'.$datedate.'">';    
        echo '<input type="hidden" name="PayType" value="PT001">';    
        echo '<input type="hidden" name="InstCode" value="'.$_PayID.'">'; 
        echo '<input type="hidden" name="PageUrl" value="'.$_Merchant_url.'">';
        echo '<input type="hidden" name="NotifyUrl" value="'.$_Return_url.'">'; 
        echo '<input type="hidden" name="ProductName" value="'.$_ProductName.'">'; 
        echo '<input type="hidden" name="BuyerContact" value="123456">'; 
        echo '<input type="hidden" name="BuyerIp" value="'.$this->getClientIP().'">'; 
        echo '<input type="hidden" name="Ext1" value="123asd">'; 
        echo '<input type="hidden" name="SignType" value="MD5">'; 
        $SigMsg = md5("B2CPayment"."V4.1.1.1.1"."UTF-8".$_MerchantID.$datedate.$this->Sjt_TransID.$_OrderMoney.$datedate."PT001".$_PayID.$_Merchant_url.$_Return_url.$_ProductName."123456".$this->getClientIP()."123asd".MD5.$_Md5Key);
        echo '<input type="hidden" name="SignMsg" value="'.$SigMsg.'">'; 
        echo '</form>';
        
        echo '<script type="text/javascript">';
        echo 'document.Form1.submit();';
        echo '</script>';
      }
      
      
      public function MerChantUrl(){
          header("Content-Type:text/html; charset=utf-8"); 
          $_Name = $this->_request("Name");
          $_Version = $this->_request("Version");
          $_Charset = $this->_request("Charset");
          $_TraceNo = $this->_request("TraceNo");
          $_MsgSender = $this->_request("MsgSender");
          $_SendTime = $this->_request("SendTime");
          $_InstCode = $this->_request("InstCode");
          $_OrderNo = $this->_request("OrderNo");
          $_OrderAmount = $this->request("OrderAmount");
          $_TransNo = $this->_request("TransNo");
          $_TransAmount = $this->_request("TransAmount");
          $_TransStatus = $this->_request("TransStatus");
          $_TransType = $this->_request("TransType");
          $_TransTime = $this->_request("TransTime");
          $_MerchantNo = $this->_request("MerchantNo");
          $_ErrorCode = $this->_request("ErrorCode");
          $_ErrorMsg = $this->_request("ErrorMsg");
          $_Ext1 = $this->_request("Ext1");
          $_Ext2 = $this->_request("Ext2");
          $_SignType = $this->_request("SignType");
          $_SignMsg = $this->_request("SignMsg");
      }
      
      public function ReturnUrl(){
          
      }       
      
      public function RunError(){
           if($this->Sjt_Merchant_url == "" || $this->Sjt_Merchant_url == null){
            echo $this->Sjt_Error;
        }else{
            echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$this->Sjt_Merchant_url."\">";
            echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$this->Sjt_Return."\">";
            echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"".$this->Sjt_Error."\">";
            echo "</from>";
            echo "<script type=\"text/javascript\">";
            echo "document.Form1.submit();";
            echo "</script>";
        }
       
        exit;
      }   
      
      
      
       private function getClientIP()  {  
                if(!empty($_SERVER["HTTP_CLIENT_IP"]))
                {
                    $cip = $_SERVER["HTTP_CLIENT_IP"];  
                }
                else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
                {
                    $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                }
                else if(!empty($_SERVER["REMOTE_ADDR"]))
                {
                    $cip = $_SERVER["REMOTE_ADDR"];  
                }
                else
                {
                    $cip = "unknown";  
                }
                return $cip;
                  
        } 
        
  }
?>
