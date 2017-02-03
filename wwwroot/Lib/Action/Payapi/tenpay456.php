<?php
  class TenpayAction extends Action{
    private $cmdno = "1";   //业务代码, 财付通支付支付接口填  1  
    private $date = "";  //商户日期：如20051212 
    private $bank_type = "";    //银行类型:财付通支付填0
    private $desc = "";   //交易的商品名称
    private $purchaser_id = "328662397";    //用户(买方)的财付通帐户(QQ或EMAIL)
    private $bargainor_id = "1214018501";    //商家的商户号,有腾讯公司唯一分配
    private $transaction_id = "";   //交易号(订单号)
    private $sp_billno = "";    //商户系统内部的定单号，此参数仅在对账时提供,28个字符内。
    private $total_fee = 0.00;   //总金额，以分为单位,不允许包含任何字符
    private $fee_type = "1";    //现金支付币种，目前只支持人民币，码编请参见附件中的
    private $return_url = "";    //接收财付通返回结果的URL(推荐使用ip)
    private $attach = "null";   //商家数据包，原样返回
    private $spbill_create_ip = "";  //用户IP（非商户服务器IP），为了防止欺诈，支付时财付通会校验此IP
    private $sign = "";   //MD5签名结果
    private $key = "e4c82b7c9722f3bf513ada06c231709e";
    
    
      private $Sjt_MerchantID = "";  //盛捷通商户ID
      private $Sjt_TransID = "";   //sjt商品编号
     // private $Sjt_TradeDate = date("Y-m-d h:i:s");   //订单提交的时间
      private $Sjt_OrderMoney = 0.01;  //订单金额
      private $Sjt_ProductName = "";   //商品名称
      private $Sjt_Username = "";   //支付用户名
      private $Sjt_AdditionalInfo = "";    //订单附加信息
      private $Sjt_Merchant_url = "";  //通知地址
      private $Sjt_Return_url = "";   //后台处理地址
      private $Sjt_PayID = "";   //支付渠道
      
      private $Sjt_Return = 1; //返回状态   1为成功，0 为失败
      private $Sjt_Error = 0;  //错误编号     
            
    
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
        
        
        $this->bank_type = $this->_post("Sjt_PayID");   //获取盛捷通商户提交的支付渠道
        if($this->bank_type == NUll || $this->bank_type == ""){  //判断盛捷通商户提交的支付渠道字段是否存在
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 3;
            $this->RunError();
        }
        
        $Bankpay = M("Bankpay");
        $this->bank_type = $Bankpay->where("id=".$this->bank_type)->getField("tenpay");
        if(!$this->bank_type){
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
        
        ////////////////////////////////////////////////////////////////////////////////////
        if(!session("?UserName") || !session("?UserType") || !session("?UserID")){   
            
            $Userapiinformation = D("Userapiinformation");
            $WebsiteUrl = $Userapiinformation->where("UserID=".(intval($this->Sjt_MerchantID)-10000))->getField("WebsiteUrl");   //获取用户设置的网址
            $yuming = $_SERVER["HTTP_REFERER"];  //获取提交的网址的域名

           if(strstr($yuming,$WebsiteUrl) == false){    //判断提交的网址域名与用户设置的域名相同
                 $this->Sjt_Return = 0;
                 $this->Sjt_Error = 6;
                 $this->RunError();
           } 
           
           
            $this->Sjt_Key = $Userapiinformation->where("UserID=".(intval($this->Sjt_MerchantID)-10000))->getField("Key");   //获取用户的密钥
            if($this->Sjt_Key != $this->_post("Sjt_Key")){    //判断盛捷通商户提交的密钥是否正确
                $this->Sjt_Return = 0;
                $this->Sjt_Error = 9;
                $this->RunError();
            }
           
           
        }
        ///////////////////////////////////////////////////////////////////////////////////
        
       
         
         
        $this->Sjt_Return_url = $this->_post("Sjt_Return_url");      //盛捷通商户底层（后台）通知地址
        if($this->Sjt_Return_url == Null || $this->Sjt_Return_url == ""){
           $this->Sjt_Return = 0;
           $this->Sjt_Error = 8;
           $this->ReturnUrl();
       }  
        
      
        
        
        $Sjapi = M("Sjapi");
        $this->bargainor_id = $Sjapi->where("apiname='tenpay'")->getField("shid"); //商户ID
        $this->key = $Sjapi->where("apiname='tenpay'")->getField("key"); //密钥   
        
        $Order = M("Order");
        $id_id = $Order->order("id desc")->limit(1)->getField("id");
        $this->Sjt_TransID = $this->bargainor_id.date("Ymd").(1000000000+$id_id);
        //$_TransID=$this->Sjt_TransID;//流水号
        ////////////////////////////////////////////////////////////////////////////////////////
        
         
        
        $this->Sjt_Merchant_url = $this->_post("Sjt_Merchant_url");   //盛捷通商户通知地址   
        $this->Sjt_Return_url = $this->_post("Sjt_Return_url");      //盛捷通商户底层（后台）通知地址
              
        $this->return_url = "http://".C("WEB_URL")."/Payapi_Tenpay_MerChantUrl.html";
        
        //支付用户名
        $this->Sjt_Username = iconv('GB2312', 'UTF-8', $this->_post("Sjt_UserName"));
        
        //产品附加信息
        $this->Sjt_AdditionalInfo = iconv('GB2312', 'UTF-8', $this->_post("Sjt_AdditionalInfo"));
        
        //商品名称
       $this->Sjt_ProductName = iconv('GB2312', 'UTF-8', $this->_post("Sjt_ProductName"));
       
        $this->desc = $this->Sjt_ProductName;
        
        $this->transaction_id = $this->Sjt_TransID;  //订单号
        $this->sp_billno = $this->Sjt_TransID;
        $this->total_fee = $this->Sjt_OrderMoney*100; //订单金额
        $this->date = date("Ymd");
        $this->spbill_create_ip = $_SERVER['REMOTE_ADDR'];
        
        $Order = M("Order");
        $data["UserID"] = intval($this->Sjt_MerchantID)-10000;      //商户编号
        $data["TransID"] = $this->Sjt_TransID;          //订单号
        $data["TradeDate"] = date("Y-m-d h:i:s");    //订单时间
        $data["OrderMoney"] = $this->Sjt_OrderMoney;     //订单金额
        $data["ProductName"] = $this->Sjt_ProductName;     //商品名称 
        $data["Username"] = iconv('GB2312', 'UTF-8', $this->_post("Sjt_UserName"));  //支付用户名
        $data["AdditionalInfo"] = $this->Sjt_AdditionalInfo;   //订单附加信息
        $data["Sjt_Merchant_url"] = $this->Sjt_Merchant_url;     //跳转地址
        $data["Sjt_Return_url"] = $this->Sjt_Return_url;    //通知地址
        $Order->add($data);
        
        
        
        $this->sign = strtoupper(md5("cmdno=".$this->cmdno."&date=".$this->date."&bargainor_id=".$this->bargainor_id."&transaction_id=".$this->transaction_id."&sp_billno=".$this->sp_billno."&total_fee=".$this->total_fee."&fee_type=".$this->fee_type."&return_url=".$this->return_url."&attach=".$this->attach."&spbill_create_ip=".$this->spbill_create_ip."&key=".$this->key));
        
        
        echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"http://service.tenpay.com/cgi-bin/v3.0/payservice.cgi\">"; 
        echo "<input type=\"hidden\" name=\"cmdno\" value=\"".$this->cmdno."\" />";
        echo "<input type=\"hidden\" name=\"date\" value=\"".$this->date."\" />";
        echo "<input type=\"hidden\" name=\"bank_type\" value=\"".$this->bank_type."\" />";
        echo "<input type=\"hidden\" name=\"desc\" value=\"".$this->desc."\" />";
        echo "<input type=\"hidden\" name=\"purchaser_id\" value=\"".$this->purchaser_id."\" />";
        echo "<input type=\"hidden\" name=\"bargainor_id\" value=\"".$this->bargainor_id."\" />";
        echo "<input type=\"hidden\" name=\"transaction_id\" value=\"".$this->transaction_id."\" />";
        echo "<input type=\"hidden\" name=\"sp_billno\" value=\"".$this->sp_billno."\" />";
        echo "<input type=\"hidden\" name=\"total_fee\" value=\"".$this->total_fee."\" />";
        echo "<input type=\"hidden\" name=\"fee_type\" value=\"".$this->fee_type."\" />";
        echo "<input type=\"hidden\" name=\"return_url\" value=\"".$this->return_url."\" />";
        echo "<input type=\"hidden\" name=\"attach\" value=\"".$this->attach."\" />";
        echo "<input type=\"hidden\" name=\"spbill_create_ip\" value=\"".$this->spbill_create_ip."\" />";
        echo "<input type=\"hidden\" name=\"sign\" value=\"".$this->sign."\" />";
      
       //echo "<input type='submit' value='adgsd'>";
        echo "</form>";  
        echo "<script type=\"text/javascript\">";
        echo "document.Form1.submit()";
        echo "</script>";
        
    }
    
    
     public function MerChantUrl(){
          $cmdno = $this->_get("cmdno");
          $pay_result = $this->_get("pay_result"); 
          $pay_info = $this->_get("pay_info"); 
          $date = $this->_get("date"); 
          $bargainor_id = $this->_get("bargainor_id"); 
          $transaction_id = $this->_get("transaction_id"); 
          $sp_billno = $this->_get("sp_billno"); 
          $total_fee = $this->_get("total_fee"); 
          $fee_type = $this->_get("fee_type"); 
          $attach = $this->_get("attach"); 
          $sign = $this->_get("sign"); 
         
         $Sjapi = M("Sjapi");
         $this->key = $Sjapi->where("apiname='tenpay'")->getField("key"); //密钥   
         
          $Sjt_sign = md5("cmdno=".$cmdno."&pay_result=".$pay_result."&date=".$date."&transaction_id=".$transaction_id."&sp_billno=".$sp_billno."&total_fee=".$total_fee."&fee_type=".$fee_type."&attach=".$attach."&key=".$this->key);
          
          if($sign == strtoupper($Sjt_sign)){
              
                $Order = D("Order");
                $UserID = $Order->where("TransID = '".$transaction_id."'")->getField("UserID");
                
                //通知跳转页面
$Sjt_Merchant_url = $Order->where("TransID = '".$transaction_id."'")->getField("Sjt_Merchant_url"); 
                //后台通知地址
               // $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
              $Sjt_MerchantID = intval($Order->where("TransID = '".$transaction_id."'")->getField("UserID")) + 10000;
                 //在商户网站冲值的用户的用户名
              $Sjt_Username = $Order->where("TransID = '".$transaction_id."'")->getField("Username");
                
                $Sjt_Zt = intval($Order->where("TransID = '".$transaction_id."'")->getField("Zt"));  
                
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                
                 if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                 
                   $data["Zt"] = 1;   
                   $data["TradeDate"] = date("Y-m-d h:i:s");  
                   $data["OrderMoney"] = $total_fee/100;
                   $list = $Order->where("TransID = '".$transaction_id."'")->save($data); //将订单设置为成功   
                 
                 if($list){
                   $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   $data["Money"] = $total_fee/100 + $Y_Money;
                   
                   //exit($total_fee."---------".$Y_Money);
                   $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                 }
                   
                   
                  
                }
                
              
                $is_success = 1;
              
                $sjt_Error = "01";
             
                $total_fee = $total_fee/100;  
               
                $_SuccTime = date("Ymdhis");     
              
                $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$transaction_id.$is_success.$sjt_Error.$total_fee.$_SuccTime.$Sjt_Key);
                
                
                $show_url = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$transaction_id."&Sjt_Return=".$is_success."&Sjt_Error=".$sjt_Error."&Sjt_factMoney=".$total_fee."&Sjt_SuccTime=".$_SuccTime."&Sjt_Sign=".$Sjt_Md5Sign;
                echo "<html>";
                echo "<head>";
                echo "<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">";
                echo "<script type=\"text/javascript\">";
                
                ///////////////////////////////////////////////////////////////////////////////////////
                 if(!session("?UserName") || !session("?UserType") || !session("?UserID")){  
                  
                    echo "window.location.href='".$Sjt_Merchant_url."?".$show_url."';";
                
                 }else{
                     
                    echo "window.location.href='http://". C("WEB_URL") ."/Payapi_Index_success.html'";  
                 }
                 //////////////////////////////////////////////////////////////////////////////////////
                 
                echo "</script>";
                echo "</head>";
                echo "<body>";
                echo "</body>";
                echo "</html>";
                
                
                
                exit;
                
                
          }else{
              $Order = D("Order"); 
              $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
              $this->Sjt_Return = 0;
              $this->Sjt_Error = "0001";
              //$this->Sjt_Error =  "cmdno=".$cmdno."&pay_result=".$pay_result."&date=".$date."&transaction_id=".$transaction_id."&sp_billno=".$sp_billno."&total_fee=".$total_fee."&fee_type=".$fee_type."&attach=".$attach."&key=".$this->key."-----------".md5("cmdno=".$cmdno."&pay_result=".$pay_result."&date=".$date."&transaction_id=".$transaction_id."&sp_billno=".$sp_billno."&total_fee=".$total_fee."&fee_type=".$fee_type."&attach=".$attach."&key=".$this->key);  //密钥错误
              
              
                   $this->Sjt_Merchant_url = $Sjt_Merchant_url;
               
              $this->RunError();  
          }
          
      }
      
      public function ReturnUrl(){
          
      } 
    
    public function RunError(){
       if($this->Sjt_Merchant_url == "" || $this->Sjt_Merchant_url == null || $this->Sjt_Merchant_url == "no"){
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
