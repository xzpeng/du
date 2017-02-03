<?php
    class BaoFuGameAction extends GameAction{
        
        public function Post(){
            
            $this->PayName = "BaoFu";
            $this->TradeDate = date("Y-m-d H:i:s");
            $this->Paymoneyfen = 100;
            $this->check();
            $this->Orderadd();
            
            $CardNO = $this->_post("Sjt_CardNumber");
            $CardPWD = $this->_post("Sjt_CardPassword");
            $CardAddress = 0;
            
            $tjurl = "";
            $NoticeType = "";
            
            $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_BaoFu_MerChantUrl.html";      //商户通知地址
        
        $this->_Return_url= "http://".C("WEB_URL")."/Payapi_BaoFu_ReturnUrl.html";   //用户通知地址
            
             $Sjapi = M("Sjapi");
             $this->_MerchantID = $Sjapi->where("apiname='".$this->PayName."'")->getField("shid"); //商户ID
             $this->_Md5Key = $Sjapi->where("apiname='".$this->PayName."'")->getField("key"); //密钥   
             $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
             
           //  exit($this->sjt_OrderMoney);
           
           $_Md5Sign = "";
           
           if($this->_post("Sjt_Paytype") == "g"){
               
                $tjurl = "http://paygate.baofoo.com/PayReceive/cardpay.aspx";
                
                $_Md5Sign = md5($this->_MerchantID.$this->Sjt_PayID.$this->TradeDate.$this->TransID.$CardNO.$CardPWD.$CardAddress.$this->sjt_OrderMoney.$this->_Merchant_url.$this->_Return_url."0".$this->_Md5Key);
                $NoticeType = 0;
                
            }else{
                if($this->_post("Sjt_Paytype") == "b"){
                    
                    $tjurl = "http://paygate.baofoo.com/PayReceive/payindex.aspx";
                    
                     $_Md5Sign=md5($this->_MerchantID.$this->Sjt_PayID.$this->TradeDate.$this->TransID.$this->sjt_OrderMoney.$this->_Merchant_url.$this->_Return_url."1".$this->_Md5Key);
                     $NoticeType = 1;
                    
                }
            }
            
            
            
            echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$tjurl."\">";
             
            echo "<input type=\"hidden\" name=\"MerchantID\" value=\"".$this->_MerchantID."\" />";
            echo "<input type=\"hidden\" name=\"PayID\" value=\"".$this->Sjt_PayID."\" />";
            echo "<input type=\"hidden\" name=\"TradeDate\" value=\"".$this->TradeDate."\" />";
            echo "<input type=\"hidden\" name=\"TransID\" value=\"".$this->TransID."\" />";
            echo "<input type=\"hidden\" name=\"OrderMoney\" value=\"".$this->sjt_OrderMoney."\" />";
            echo "<input type=\"hidden\" name=\"ProductName\" value=\"".$this->ProductName."\" />";
            echo "<input type=\"hidden\" name=\"Username\" value=\"".$this->Username."\" />"; 
            echo "<input type=\"hidden\" name=\"AdditionalInfo\" value=\"".$this->AdditionalInfo."\" />";
            
            echo "<input type=\"hidden\" name=\"CardNO\" value=\"".$CardNO."\" />";

            echo "<input type=\"hidden\" name=\"CardPWD\" value=\"".$CardPWD."\" />";

            echo "<input type=\"hidden\" name=\"CardAddress\" value=\"".$CardAddress."\" />";            
            
            echo "<input type=\"hidden\" name=\"Merchant_url\" value=\"".$this->_Merchant_url."\" />";
            echo "<input type=\"hidden\" name=\"Return_url\" value=\"".$this->_Return_url."\" />";
            echo "<input type=\"hidden\" name=\"NoticeType\" value=\"".$NoticeType."\" />";
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
            if($_Result == 0){
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
                
                $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                
                 if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                   $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   
                   $data["Money"] = $OrderMoney + $Y_Money;
                   $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                   
                    ///////////////////////////////////////////////////////////
                   $Moneydb = M("Moneydb");
                   $data["UserID"] = $UserID;
                   $data["money"] = $OrderMoney;
                   $data["ymoney"] = $Y_Money;
                   $data["gmoney"] = $OrderMoney + $Y_Money;
                   $data["datetime"] = date("Y-m-d H:i:s");
                   $data["lx"] = 2;
                   $Moneydb->add($data);   
                   
                   
                   //////////////////////////////////////////////////////////
                   
                   $data["Zt"] = 1;   
                   $data["TradeDate"] = date("Y-m-d h:i:s");  
                   //$data["OrderMoney"] = $_factMoney/100;
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
        
         $Sjapi = M("Sjapi");
        $_Md5Key = $Sjapi->where("apiname='baofu'")->getField("key"); //密钥   
        
        
        //$_Md5Key="affrekku4p87b6bc";   //密钥
        $_WaitSign=md5($_MerchantID.$_TransID.$_Result.$_resultDesc.$_factMoney.$_additionalInfo.$_SuccTime.$_Md5Key);
        if ($_Md5Sign == $_WaitSign) {
            if($_Result == 0){
                echo("没有支付成功！");
                exit($_resultDesc);
            }else{
                $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
              
                //后台通知地址
                $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
              
              $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");
                
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                
                if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                   $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   $data["Money"] = $OrderMoney + $Y_Money;
                   $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                      ///////////////////////////////////////////////////////////
                                                                 ///////////////////////////////////////////////////////////
                   $Moneydb = M("Moneydb");
                   $data["UserID"] = $UserID;
                   $data["money"] = $OrderMoney;
                   $data["ymoney"] =  $Y_Money;
                   $data["gmoney"] = $Y_Money + $OrderMoney;
                   $data["datetime"] = date("Y-m-d H:i:s");
                   $data["lx"] = 1;
                   $Moneydb->add($data);
                   //////////////////////////////////////////////////////////
                   
                   //////////////////////////////////////////////////////////
                   $data["Zt"] = 1;   
                   $data["TradeDate"] = date("Y-m-d h:i:s");  
                   $Order->where("UserID=".$UserID)->save($data); //将订单设置为成功
                   
                   
               ////////////////////////////////////////////////////////  
               $Ordertz = M("Ordertz");
               $data["Sjt_MerchantID"] = $Sjt_MerchantID;
               $data["Sjt_UserName"] = $Sjt_Username;
               $data["Sjt_TransID"] = $_TransID;
               $data["Sjt_Return"] = $_Result;
               $data["Sjt_Error"] = $_resultDesc;
               $_factMoney = $_factMoney/100;
               $_factMoney = number_format($_factMoney,2);
               $data["Sjt_factMoney"] = $_factMoney;
               $data["Sjt_SuccTime"] = $_SuccTime;
               $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$_factMoney.$_SuccTime.$Sjt_Key);
               $data["Sjt_Sign"] = $Sjt_Md5Sign;
               $data["Sjt_urlname"] = $Sjt_Return_url;
               $Ordertz->add($data);
               ///////////////////////////////////////////////////////
                   
                }
              
                exit("ok");
            }
            //处理想处理的事情，验证通过，根据提交的参数判断支付结果
        } 
        else {
            exit("no");
        } 
    }
        
    }
?>