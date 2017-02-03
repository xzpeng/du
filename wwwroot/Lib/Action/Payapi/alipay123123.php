<?php
  class AlipayAction extends Action{
      
      private $service = "create_direct_pay_by_user";   //接口名称
      private $partner = "2088801266952689";   //合作者身份ID
      private $_input_charset = "utp-8";  //盛捷通网站的编码格式
      private $sign_type = "MD5";   //签名方式
      private $sign = "";  //签名
      private $notify_url = "";  //后端异步提交地址
      private $return_url = "";  //前台跳转地址
      private $out_trade_no = "";   //订单号
      private $subject = "";   //商品名称
      private $payment_type = "1";   //支付类型（商品购买）
      private $defaultbank = "CMB";     //默认网银
      private $paymethod = "bankPay";    //默认支付方式
      private $key = "q52c8i0q9rdccfn24ovgfj9fxlfow4bh";
      private $seller_email = "2455516646@qq.com";    //收款人支付宝账号
      private $total_fee = 0.00;    //交易金额
      
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
      private $Sjt_Error = "01";  //错误编号     
            
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
        
        
        $this->defaultbank = $this->_post("Sjt_PayID");   //获取盛捷通商户提交的支付渠道
        if($this->defaultbank == NUll || $this->defaultbank == ""){  //判断盛捷通商户提交的支付渠道字段是否存在
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 3;
            $this->RunError();
        }
        
        $Bankpay = M("Bankpay");
        $this->defaultbank = $Bankpay->where("id=".$this->defaultbank)->getField("alipay");
        if(!$this->defaultbank){
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
        $this->partner = $Sjapi->where("apiname='alipay'")->getField("shid"); //商户ID
        $this->key = $Sjapi->where("apiname='alipay'")->getField("key"); //密钥   
        $this->seller_email = $Sjapi->where("apiname='alipay'")->getField("zhanghu ");  //账户
        
        $Order = M("Order");
        $id_id = $Order->order("id desc")->limit(1)->getField("id");
        $this->Sjt_TransID = $this->Sjt_MerchantID.date("Ymd").(1000000000+$id_id);
        //$_TransID=$this->Sjt_TransID;//流水号
        //、、、、、、、、、、、、、、、、、、、、、、、、、、、、、、、、、、、、、、、
         
        
        //$this->Sjt_Merchant_url = $this->_post("Sjt_Merchant_url");   //盛捷通商户通知地址   
        //$this->Sjt_Return_url = $this->_post("Sjt_Return_url");      //盛捷通商户底层（后台）通知地址
              
        $this->return_url = "http://".C("WEB_URL")."/Payapi_Alipay_MerChantUrl.html";
        $this->notify_url = "http://".C("WEB_URL")."/Payapi_Alipay_ReturnUrl.html";
        
       // $this->subject = $this->_post("Sjt_ProductName");  //商品名称
        $this->subject = iconv('GB2312', 'UTF-8', $this->_post("Sjt_ProductName"));
        $this->Sjt_ProductName = $this->subject;
        $this->Sjt_AdditionalInfo =iconv('GB2312', 'UTF-8', $this->_post("Sjt_AdditionalInfo"));
        
        $this->out_trade_no = $this->Sjt_TransID;  //订单号
        $this->total_fee = $this->Sjt_OrderMoney; //订单金额
        
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
        
        $this->sign = "_input_charset=".$this->_input_charset."&defaultbank=".$this->defaultbank."&notify_url=".$this->notify_url."&out_trade_no=".$this->out_trade_no."&partner=".$this->partner."&payment_type=".$this->payment_type."&paymethod=".$this->paymethod."&return_url=".$this->return_url."&seller_email=".$this->seller_email."&service=".$this->service."&subject=".$this->subject."&total_fee=".$this->total_fee.$this->key;
        
       // exit($this->sign);
        
        $this->sign = md5($this->sign);
        
          
        echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"https://mapi.alipay.com/gateway.do\">"; 
        echo "<input type=\"hidden\" name=\"service\" value=\"".$this->service."\" />";
        echo "<input type=\"hidden\" name=\"partner\" value=\"".$this->partner."\" />";
        echo "<input type=\"hidden\" name=\"seller_email\" value=\"".$this->seller_email."\" />";
        echo "<input type=\"hidden\" name=\"_input_charset\" value=\"".$this->_input_charset."\" />";
        echo "<input type=\"hidden\" name=\"sign_type\" value=\"".$this->sign_type."\" />";
        echo "<input type=\"hidden\" name=\"sign\" value=\"".$this->sign."\" />";
        echo "<input type=\"hidden\" name=\"notify_url\" value=\"".$this->notify_url."\" />";
        echo "<input type=\"hidden\" name=\"return_url\" value=\"".$this->return_url."\" />";
        echo "<input type=\"hidden\" name=\"out_trade_no\" value=\"".$this->out_trade_no."\" />";
        echo "<input type=\"hidden\" name=\"subject\" value=\"".$this->subject."\" />";
        echo "<input type=\"hidden\" name=\"payment_type\" value=\"".$this->payment_type."\" />";
        echo "<input type=\"hidden\" name=\"defaultbank\" value=\"".$this->defaultbank."\" />";
        echo "<input type=\"hidden\" name=\"paymethod\" value=\"".$this->paymethod."\" />";
        echo "<input type=\"hidden\" name=\"total_fee\" value=\"".$this->total_fee."\" />";
        echo "</form>";  
        echo "<script type=\"text/javascript\">";
        echo "document.Form1.submit()";
        echo "</script>";
      }
      
      public function MerChantUrl(){
          
          $parameter = array(
               "is_success"  => $this->_post("is_success"),
               
              // "sign_type"   => $this->_post("sign_type"),
               
               //"sign"        => $this->_post("sign"),
               
               "out_trade_no"   =>  $this->_get("out_trade_no"),
               
               "subject"        => $this->_get("subject"),
               
               "payment_type"   => $this->_get("payment_type"),
               
               "exterface"      => $this->_get("exterface"),
               
               "trade_no"       => $this->_get("trade_no"),
               
               "trade_status"   => $this->_get("trade_status"),
               
               "notify_id"      => $this->_get("notify_id"),
               
               "notify_time"    => $this->_get("notify_time"),
               
               "notify_type"    => $this->_get("notify_type"),
               
               "seller_email"   => $this->_get("seller_email"),
               
               "buyer_email"    => $this->_get("buyer_email"),
               
               "seller_id"      =>  $this->_get("seller_id"),
               
               "buyer_id"       => $this->_get("buyer_id"),
               
               "total_fee"      => $this->_get("total_fee"),
               
               "body"           => $this->_get("body"),
               
               "bank_seq_no"    => $this->_get("bank_seq_no"),
               
               "extra_common_param"   => $this->_get("extra_common_param")
          );
          
          
          $sign = $this->_get("sign");
          
          sort($parameter);
          
          $Sjt_sign = "";
          
          foreach($parameter as $key => $val){
              if($val != "" && $val != NULL){
                  $Sjt_sign = $Sjt_sign.$key."=".$val."&";
              } 
          } 
          
          $Sjt_sign = substr($Sjt_sign,0,strlen($Sjt_sign)-1);
          $Sjapi = M("Sjapi");
          $_Md5Key = $Sjapi->where("apiname='alipay'")->getField("key"); //密钥   
          $Sjt_sign = md5($Sjt_sign.$_Md5Key);
          if($sign == $Sjt_sign){
              
              if($is_success == 0){
                  
                $this->Sjt_Return = 0;
                $this->Sjt_Error = "0000";
                $Order = D("Order");    
          $this->Sjt_Merchant_url = $Order->where("TransID = '".$parameter["out_trade_no"]."'")->getField("Sjt_Merchant_url");
                $this->RunError();
                
              }else{
                  
                $Order = D("Order");
                $UserID = $Order->where("TransID = '".$parameter["out_trade_no"]."'")->getField("UserID");
                
                //通知跳转页面
$Sjt_Merchant_url = $Order->where("TransID = '".$parameter["out_trade_no"]."'")->getField("Sjt_Merchant_url"); 
                //后台通知地址
               // $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
              $Sjt_MerchantID = $Order->where("TransID = '".$parameter["out_trade_no"]."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
              $Sjt_Username = $Order->where("TransID = '".$parameter["out_trade_no"]."'")->getField("Username");
                
                $Sjt_Zt = $Order->where("UserID=".$UserID)->getField("Zt");  
                
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                
                 if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                   $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   $data["Money"] = $total_fee + $Y_Money;
                   $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                   
                   $data["Zt"] = 1;   
                   $data["TradeDate"] = date("Y-m-d h:i:s");  
                   $data["OrderMoney"] = $total_fee;
                   $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                   
                }
                
                //$_factMoney = $_factMoney/100;
                
                echo "<from id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
                echo "<input type=\"hidden\" name=\"Sjt_MerchantID\" value=\"".$Sjt_MerchantID."\">";
                echo "<input type=\"hidden\" name=\"Sjt_Username\" value=\"".$Sjt_Username."\">";   
                echo "<input type=\"hidden\" name=\"Sjt_TransID\" value=\"".$out_trade_no."\">";   
                echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$is_success."\">";   
                echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"01\">";   
                echo "<input type=\"hidden\" name=\"Sjt_factMoney\" value=\"".$total_fee."\">"; 
                $_SuccTime = date("Ymdhis");      
                echo "<input type=\"hidden\" name=\"Sjt_SuccTime\" value=\"".$_SuccTime."\">";
                $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$out_trade_no.$is_success."01".$total_fee.$_SuccTime.$Sjt_Key);
                echo "<input type=\"hidden\" name=\"Sjt_Sign\" value=\"".$Sjt_Md5Sign."\">";  
                echo "</from>";
                echo "<script type=\"text/javascript\">";
                echo "document.Form1.submit();";
                echo "</script>";
                
                exit;
                  
              }
              
          }else{
                $Order = D("Order"); 
                $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
                $this->Sjt_Return = 0;
                $this->Sjt_Error =  "0001";  //密钥错误
                $this->Sjt_Merchant_url = $Sjt_Merchant_url;
                $this->RunError();  
          }
      }
      
      public function ReturnUrl(){
          $parameter = array(
                 
                 $notify_time       =>    $this->_post("notify_time"),
                 $notify_type       =>    $this->_post("notify_type"),
                 $notify_id       =>    $this->_post("notify_id"),
                 $out_trade_no       =>    $this->_post("out_trade_no"),
                 $subject       =>    $this->_post("subject"),
                 $payment_type       =>    $this->_post("payment_type"),
                 $trade_no       =>    $this->_post("trade_no"),
                 $trade_status       =>    $this->_post("trade_status"),
                 $gmt_create       =>    $this->_post("gmt_create"),
                 $gmt_payment       =>    $this->_post("gmt_payment"),
                 $gmt_close       =>    $this->_post("gmt_close"),
                 $refund_status       =>    $this->_post("refund_status"),
                 $gmt_refund       =>    $this->_post("gmt_refund"),
                 $seller_email       =>    $this->_post("seller_email"),
                 $buyer_email       =>    $this->_post("buyer_email"),
                 $seller_id       =>    $this->_post("seller_id"),
                 $buyer_id       =>    $this->_post("buyer_id"),
                 $price       =>    $this->_post("price"),
                 $total_fee       =>    $this->_post("total_fee"),
                 $quantity       =>    $this->_post("quantity"),
                 $body       =>    $this->_post("body"),
                 $discount       =>    $this->_post("discount"),
                 $is_total_fee_adjust       =>    $this->_post("is_total_fee_adjust"),
                 $use_coupon       =>    $this->_post("use_coupon"),  
                 $error_code       =>    $this->_post("error_code"),
                 $bank_seq_no       =>    $this->_post("bank_seq_no"),
                 $extra_common_param       =>    $this->_post("extra_common_param"),
                 $out_channel_type       =>    $this->_post("out_channel_type"),
                 $out_channel_amount       =>    $this->_post("out_channel_amount"),
                 $out_channel_inst       =>    $this->_post("out_channel_inst")
                 
          );
          
          $sign = $this->_post("sign");
          
          sort($parameter);
          
          $Sjt_sign = "";
          
          foreach($parameter as $key => $val){
              if($val != "" && $val != NULL){
                  $Sjt_sign = $Sjt_sign.$key."=".$val."&";
              } 
          } 
          
          $Sjt_sign = substr($Sjt_sign,0,strlen($Sjt_sign)-1);
          $Sjapi = M("Sjapi");
          $_Md5Key = $Sjapi->where("apiname='alipay'")->getField("key"); //密钥   
          $Sjt_sign = md5($Sjt_sign.$_Md5Key);
          
          if($sign == $Sjt_sign){  //
              
              $Order = D("Order");
              $UserID = $Order->where("TransID = '".$parameter["out_trade_no"]."'")->getField("UserID");
                
                //通知跳转页面
$Sjt_Return_url = $Order->where("TransID = '".$parameter["out_trade_no"]."'")->getField("Sjt_Return_url"); 
                //后台通知地址
               // $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
              $Sjt_MerchantID = $Order->where("TransID = '".$parameter["out_trade_no"]."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
              $Sjt_Username = $Order->where("TransID = '".$parameter["out_trade_no"]."'")->getField("Username");
                
                $Sjt_Zt = $Order->where("UserID=".$UserID)->getField("Zt");  
                
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                
                 if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                   $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   $data["Money"] = $total_fee + $Y_Money;
                   $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                   
                   $data["Zt"] = 1;   
                   $data["TradeDate"] = date("Y-m-d h:i:s");  
                   $data["OrderMoney"] = $total_fee;
                   $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                   
                }
                
                exit("success");
              
          }else{
              $Order = D("Order"); 
              $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
              $this->Sjt_Return = 0;
              $this->Sjt_Error =  "0001";  //密钥错误
              $this->Sjt_Merchant_url = $Sjt_Merchant_url;
              $this->RunError();  
          }
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
  }
?>
