<?php

class BaoFuAction extends PayAction{
    
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
        $_MerchantID = $Sjapi->where("apiname='baofu'")->getField("shid"); //商户ID
        $_Md5Key = $Sjapi->where("apiname='baofu'")->getField("key"); //密钥   
        
        $Order = M("Order");
        $id_id = $Order->order("id desc")->limit(1)->getField("id");
        $this->Sjt_TransID = $this->Sjt_MerchantID.date("Ymd").(1000000000+$id_id);
        $_TransID=$this->Sjt_TransID;//流水号
        
        $_PayID=$this->Sjt_PayID;//支付方式
        $_TradeDate = date("Ymdhis");//交易时间  date("Ymdhis");
        $_OrderMoney=$this->Sjt_OrderMoney*100;//订单金额
        $_ProductName=iconv('GB2312', 'UTF-8', $this->_post("Sjt_ProductName"));//产品名称
        
        $_Username=$Sjt_Name;//盛捷通支付用户名
        
        $_AdditionalInfo=iconv('GB2312', 'UTF-8', $this->_post("Sjt_AdditionalInfo"));//订单附加消息
        
        $_Merchant_url= "http://".C("WEB_URL")."/Payapi_BaoFu_MerChantUrl.html";      //商户通知地址
        
        $_Return_url= "http://".C("WEB_URL")."/Payapi_BaoFu_ReturnUrl.html";   //用户通知地址
        
        $_NoticeType= 1;//通知方式  服务器通知和页面通知。支付成功后，自动重定向到“通知商户地址”
        
        $_Md5Sign=md5($_MerchantID.$_PayID.$_TradeDate.$_TransID.$_OrderMoney.$_Merchant_url.$_Return_url.$_NoticeType.$_Md5Key);
        
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
        
        echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"http://paygate.baofoo.com/ PayReceive/payindex.aspx\">";
        echo "<input type=\"hidden\" name=\"MerchantID\" value=\"".$_MerchantID."\" />";
        echo "<input type=\"hidden\" name=\"PayID\" value=\"".$_PayID."\" />";
        echo "<input type=\"hidden\" name=\"TradeDate\" value=\"".$_TradeDate."\" />";
        echo "<input type=\"hidden\" name=\"TransID\" value=\"".$_TransID."\" />";
        echo "<input type=\"hidden\" name=\"OrderMoney\" value=\"".$_OrderMoney."\" />";
        echo "<input type=\"hidden\" name=\"ProductName\" value=\"".$_ProductName."\" />";
        echo "<input type=\"hidden\" name=\"Username\" value=\"".$_Username."\" />"; 
        echo "<input type=\"hidden\" name=\"AdditionalInfo\" value=\"".$_AdditionalInfo."\" />";
        echo "<input type=\"hidden\" name=\"Merchant_url\" value=\"".$_Merchant_url."\" />";
        echo "<input type=\"hidden\" name=\"Return_url\" value=\"".$_Return_url."\" />";
        echo "<input type=\"hidden\" name=\"NoticeType\" value=\"".$_NoticeType."\" />";
        echo "<input type=\"hidden\" name=\"Md5Sign\" value=\"".$_Md5Sign."\" />";

        echo "</form>";
        echo "<script type=\"text/javascript\">";
        echo "document.Form1.submit();";
        echo "</script>";
    }
    
    
    public function MerChantUrl(){     //商户通知地址
        header("Content-Type:text/html; charset=utf-8"); 
        $_MerchantID = $this->_request("MerchantID");   //商户号
        $_TransID = $this->_request("TransID");     //商户流水号
        $_Result = $this->_request("Result");    //支付结果(1:成功,0:失败)
        $_resultDesc = $this->_request("resultDesc");    //支付结果描述
        $_factMoney = $this->_request("factMoney");    //实际成交金额
        $_additionalInfo = $this->_request("additionalInfo");    //订单附加消息
        $_SuccTime = $this->_request("SuccTime");    //交易成功时间
        $_Md5Sign = $this->_request("Md5Sign");    //Md5签名字段
        
        $Sjapi = M("Sjapi");
        $_Md5Key = $Sjapi->where("apiname='baofu'")->getField("key"); //密钥   
        
       // $_Md5Key="affrekku4p87b6bc";   //密钥
        $_WaitSign=md5($_MerchantID.$_TransID.$_Result.$_resultDesc.$_factMoney.$_additionalInfo.$_SuccTime.$_Md5Key);
        if ($_Md5Sign == $_WaitSign) {
            if($_REQUEST == 0){
               // echo("没有支付成功！");
                //exit($_resultDesc);
                $this->Sjt_Return = 0;
                $this->Sjt_Error = $_resultDesc;
                $Order = D("Order");    
                $this->Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
                $this->RunError();
                
            }else{
                $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                
                //通知跳转页面
                $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url"); 
                //后台通知地址
               // $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                
                $Sjt_Zt = $Order->where("UserID=".$UserID)->getField("Zt");  
                
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                
                 if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                   $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   $data["Money"] = $_factMoney/100 + $Y_Money;
                   $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                   
                   $data["Zt"] = 1;   
                   $data["TradeDate"] = date("Y-m-d h:i:s");  
                   $data["OrderMoney"] = $_factMoney/100;
                   $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                   
                }
                
                $_factMoney = $_factMoney/100;
                
                echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
                echo "<input type=\"hidden\" name=\"Sjt_MerchantID\" value=\"".$Sjt_MerchantID."\">";
                echo "<input type=\"hidden\" name=\"Sjt_Username\" value=\"".$Sjt_Username."\">";   
                echo "<input type=\"hidden\" name=\"Sjt_TransID\" value=\"".$_TransID."\">";   
                echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$_Result."\">";   
                echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"".$_resultDesc."\">";   
                echo "<input type=\"hidden\" name=\"Sjt_factMoney\" value=\"".$_factMoney."\">";       
                echo "<input type=\"hidden\" name=\"Sjt_SuccTime\" value=\"".$_SuccTime."\">";
                $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$_factMoney.$_SuccTime.$Sjt_Key);
                echo "<input type=\"hidden\" name=\"Sjt_Sign\" value=\"".$Sjt_Md5Sign."\">";  
                echo "</from>";
                echo "<script type=\"text/javascript\">";
                echo "document.Form1.submit();";
                echo "</script>";
                
                exit;
            }
            //处理想处理的事情，验证通过，根据提交的参数判断支付结果
        } 
        else {
            //exit ("Fail");
           // exit($_Result."-----".$_resultDesc;
           
            $Order = D("Order"); 
            $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
            $this->Sjt_Return = 0;
            $this->Sjt_Error =  "9";  //密钥错误
            $this->Sjt_Merchant_url = $Sjt_Merchant_url;
            $this->RunError();
        } 
    }
    
    
    public function ReturnUrl(){       //底层通知地址
        $_MerchantID = $this->_request("MerchantID");   //商户号
        $_TransID = $this->_request("TransID");     //商户流水号
        $_Result = $this->_request("Result");    //支付结果(1:成功,0:失败)
        $_resultDesc = $this->_request("resultDesc");    //支付结果描述
        $_factMoney = $this->_request("factMoney");    //实际成交金额
        $_additionalInfo = $this->_request("additionalInfo");    //订单附加消息
        $_SuccTime = $this->_request("SuccTime");    //交易成功时间
        $_Md5Sign = $this->_request("Md5Sign");    //Md5签名字段
        $_Md5Key="affrekku4p87b6bc";   //密钥
        $_WaitSign=md5($_MerchantID.$_TransID.$_Result.$_resultDesc.$_factMoney.$_additionalInfo.$_SuccTime.$_Md5Key);
        if ($_Md5Sign == $_WaitSign) {
            if($_REQUEST == 0){
                echo("没有支付成功！");
                exit($_resultDesc);
            }else{
                $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
               // $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url"); 
                
                //$Order->where("TransID = '".$_TransID."'")->save($data);
                $Sjt_Zt = $Order->where("UserID=".$UserID)->getField("Zt");
                
               // $Userapiinformation = D("Userapiinformation");
                //$Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                  
                
                if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                   $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   $data["Money"] = $_factMoney/100 + $Y_Money;
                   $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                   
                   $data["Zt"] = 1;   
                   $data["TradeDate"] = date("Y-m-d h:i:s");  
                   $Order->where("UserID=".$UserID)->save($data); //将订单设置为成功
                   
                }
               
                exit("ok");
            }
            //处理想处理的事情，验证通过，根据提交的参数判断支付结果
        } 
        else {
            //exit ("Fail");
           // exit($_Result."-----".$_resultDesc);
            exit("no");
        } 
    }
    
    
    private function RunError(){
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
    
}

?>