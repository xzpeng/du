<?php
  class TenpayAction extends PayAction{
      
    private $cmdno = "1";   //业务代码, 财付通支付支付接口填  1  
    private $date = "";  //商户日期：如20051212 
    private $bank_type = "0";    //银行类型:财付通支付填0
    private $desc = "";   //交易的商品名称
    private $purchaser_id = "328662397";    //用户(买方)的财付通帐户(QQ或EMAIL)
    private $bargainor_id = "";    //商家的商户号,有腾讯公司唯一分配
    private $transaction_id = "";   //交易号(订单号)
    private $sp_billno = "";    //商户系统内部的定单号，此参数仅在对账时提供,28个字符内。
    private $total_fee = 0.00;   //总金额，以分为单位,不允许包含任何字符
    private $fee_type = "1";    //现金支付币种，目前只支持人民币，码编请参见附件中的
    private $return_url = "";    //接收财付通返回结果的URL(推荐使用ip)
    private $attach = "null";   //商家数据包，原样返回
    private $spbill_create_ip = "";  //用户IP（非商户服务器IP），为了防止欺诈，支付时财付通会校验此IP
    private $sign = "";   //MD5签名结果
    private $key = "e4c82b7c9722f3bf513ada06c231709e";
      
      
      public function Post(){
          
            $this->PayName = "Tenpay";
            $this->TradeDate = date("Y-m-d H:i:s");
            $this->Paymoneyfen = 100;
            $this->check();
            $this->Orderadd();
            
  
   $Sjapi = M("Sjapi");
$this->_MerchantID = $Sjapi->where("apiname='".$this->PayName."'")->getField("shid");//商户ID
$this->_Md5Key = $Sjapi->where("apiname='".$this->PayName."'")->getField("key"); //密钥   
$this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
  
  /////////////////////////////////////////////////////////////////////////
  $this->date = date("Ymd");
  $this->bank_type = $this->Sjt_PayID;
  $this->desc = $this->ProductName;
  $this->bargainor_id = $this->_MerchantID;
  $this->key = $this->_Md5Key;
  $this->total_fee = $this->sjt_OrderMoney;
  $this->transaction_id = $this->_MerchantID.$this->TransID.strval(mt_rand(10, 99));
   $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_Tenpay_MerChantUrl.html";
  $this->return_url = $this->_Merchant_url;
  $this->spbill_create_ip = $_SERVER['REMOTE_ADDR'];
  $this->sp_billno = $this->_MerchantID.$this->TransID.strval(mt_rand(10, 99));
  ////////////////////////////////////////////////////////////////////////
        
        //exit("cmdno=".$this->cmdno."&date=".$this->date."&bargainor_id=".$this->bargainor_id."&transaction_id=".$this->transaction_id."&sp_billno=".$this->sp_billno."&total_fee=".$this->total_fee."&fee_type=".$this->fee_type."&return_url=".$this->return_url."&attach=".$this->attach."&spbill_create_ip=".$this->spbill_create_ip."&key=".$this->key);
        
        $this->sign = strtoupper(md5("cmdno=".$this->cmdno."&date=".$this->date."&bargainor_id=".$this->_MerchantID."&transaction_id=".$this->transaction_id."&sp_billno=".$this->sp_billno."&total_fee=".$this->total_fee."&fee_type=".$this->fee_type."&return_url=".$this->return_url."&attach=".$this->attach."&spbill_create_ip=".$this->spbill_create_ip."&key=".$this->_Md5Key));    
        echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi\">"; 
        echo "<input type=\"hidden\" name=\"cmdno\" value=\"".$this->cmdno."\" />";
        echo "<input type=\"hidden\" name=\"date\" value=\"".$this->date."\" />";
        echo "<input type=\"hidden\" name=\"bank_type\" value=\"".$this->bank_type."\" />";
        echo "<input type=\"hidden\" name=\"desc\" value=\"".$this->desc."\" />";
        echo "<input type=\"hidden\" name=\"purchaser_id\" value=\"".$this->purchaser_id."\" />";
        echo "<input type=\"hidden\" name=\"bargainor_id\" value=\"".$this->_MerchantID."\" />";
        echo "<input type=\"hidden\" name=\"transaction_id\" value=\"".$this->transaction_id."\" />";
        echo "<input type=\"hidden\" name=\"sp_billno\" value=\"".$this->sp_billno."\" />";
        echo "<input type=\"hidden\" name=\"total_fee\" value=\"".$this->total_fee."\" />";
        echo "<input type=\"hidden\" name=\"fee_type\" value=\"".$this->fee_type."\" />";
        echo "<input type=\"hidden\" name=\"return_url\" value=\"".$this->return_url."\" />";
        echo "<input type=\"hidden\" name=\"attach\" value=\"".$this->attach."\" />";
        echo "<input type=\"hidden\" name=\"spbill_create_ip\" value=\"".$this->spbill_create_ip."\" />";
        echo "<input type=\"hidden\" name=\"sign\" value=\"".$this->sign."\" />";
        echo "</form>";  
        //echo "<script type=\"text/javascript\">";
       // echo "document.Form1.submit()";
       // echo "</script>";
       $this->Echots(); 
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
         $MerchantID = $Sjapi->where("apiname='tenpay'")->getField("shid");//商户ID
          $Sjt_sign = md5("cmdno=".$cmdno."&pay_result=".$pay_result."&date=".$date."&transaction_id=".$transaction_id."&sp_billno=".$sp_billno."&total_fee=".$total_fee."&fee_type=".$fee_type."&attach=".$attach."&key=".$this->key);
          
          if($sign == strtoupper($Sjt_sign)){
              
                $transaction_id = str_replace($MerchantID,"",$transaction_id);
                $transaction_id = substr($transaction_id,0,16);
                $Order = D("Order");
                $UserID = $Order->where("TransID = '".$transaction_id."'")->getField("UserID");
                
                //通知跳转页面
$Sjt_Merchant_url = $Order->where("TransID = '".$transaction_id."'")->getField("Sjt_Merchant_url"); 
                //后台通知地址
                $Sjt_Return_url = $Order->where("TransID = '".$transaction_id."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
              $Sjt_MerchantID = intval($Order->where("TransID = '".$transaction_id."'")->getField("UserID")) + 10000;
                 //在商户网站冲值的用户的用户名
              $Sjt_Username = $Order->where("TransID = '".$transaction_id."'")->getField("Username");
                
                $Sjt_Zt = intval($Order->where("TransID = '".$transaction_id."'")->getField("Zt"));  
                 $total_fee_k = $Order->where("TransID = '".$transaction_id."'")->getField("OrderMoney");
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                
                 if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                 
                  $Money = D("Money");
                 $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                 $data["Money"] = $total_fee_k + $Y_Money;
                 $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                 
                 
                  ///////////////////////////////////////////////////////////
                   $Moneydb = M("Moneydb");
                   $data["UserID"] = $UserID;
                   $data["money"] = $total_fee_k;
                   $data["ymoney"] =  $Y_Money;
                   $data["gmoney"] = $Y_Money + $total_fee_k;
                   $data["datetime"] = date("Y-m-d H:i:s");
                   $data["lx"] = 2;
                   $Moneydb->add($data);
                   //////////////////////////////////////////////////////////
                   
                   
                   //////////////////////////////////////////////////////////
                   
                 $data["Zt"] = 1;   
                 //$data["TradeDate"] = date("Y-m-d h:i:s");  
                 $Order->where("TransID = '".$transaction_id."'")->save($data); 
                }
                
              
                $is_success = 1;
              
                $sjt_Error = "01";
             
                $total_fee = $total_fee;  
               
                $_SuccTime = date("Ymdhis");     
              
                $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$transaction_id.$is_success.$sjt_Error.$total_fee.$_SuccTime."1".$Sjt_Key);
                
                
                $show_url = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_Username=".$Sjt_Username."&Sjt_TransID=".$transaction_id."&Sjt_Return=".$is_success."&Sjt_Error=".$sjt_Error."&Sjt_factMoney=".$total_fee."&Sjt_SuccTime=".$_SuccTime."&Sjt_Sign=".$Sjt_Md5Sign."&Sjt_BType=1";
                
                ////////////////////////////////////////////////////////  
               $Ordertz = M("Ordertz");
               $list = $Ordertz->where("Sjt_TransID = '".$transaction_id."'")->select();
               if($list == NULL || $list == ""){
                   $data["Sjt_MerchantID"] = $Sjt_MerchantID;
                   $data["Sjt_UserName"] = $Sjt_Username;
                   $data["Sjt_TransID"] = $transaction_id;
                   $data["Sjt_Return"] = $is_success;
                   $data["Sjt_Error"] = $sjt_Error;
                   $_factMoney = $total_fee/100;
                   $_factMoney = number_format($_factMoney,2);
                   $data["Sjt_factMoney"] = $_factMoney;
                   $data["Sjt_SuccTime"] = $_SuccTime;
                   $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$_factMoney.$_SuccTime.$Sjt_Key);
                   $data["Sjt_Sign"] = $Sjt_Md5Sign;
                   $data["Sjt_urlname"] = $Sjt_Return_url;
                   $Ordertz->add($data);
               }
               
               
              # $datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$transaction_id."&Sjt_Return=".$is_success."&Sjt_Error=".$sjt_Error."&Sjt_factMoney=".$_factMoney."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
              # $tjurl = $Sjt_Return_url."?".$datastr; 
              // $contents = fopen($tjurl,"r"); 
               //$contents=fread($contents,4096); 
               #$contents = "ok";
               #if($contents == "ok"){
                # $data["success"] = 1;
                # $Ordertz->where("Sjt_TransID = '".$transaction_id."'")->save($data);
               #}else{
                  // $data["Sjt_UserName"] = $contents;
                  // $Ordertz->where("Sjt_TransID = '".$this->_post("out_trade_no")."'")->save($data);
               #}
               ///////////////////////////////////////////////////////
                
                
               # echo "<html>";
               # echo "<head>";
               # echo "<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">";
               # echo "<script type=\"text/javascript\">";
                
                ///////////////////////////////////////////////////////////////////////////////////////
                 //if(!session("?UserName") || !session("?UserType") || !session("?UserID")){  
                  
                  #  echo "window.location.href='".$Sjt_Merchant_url."?".$show_url."';";
                
                // }else{
                     
                   // echo "window.location.href='http://". C("WEB_URL") ."/Payapi_Index_success.html'";  
                /// }
                 //////////////////////////////////////////////////////////////////////////////////////
                 
               # echo "</script>";
                #echo "</head>";
                #echo "<body>";
               # echo "</body>";
               # echo "</html>";
                
                 $sings = "returncode=1&userid=".($UserID+10000)."&orderid=".$transaction_id."&keyvalue=".$Sjt_Key;
    
           $sings = md5($sings);
    
           $tjurl = $Sjt_Return_url."?returncode=1&userid=".($UserID+10000)."&orderid=".$transaction_id."&money=".$_factMoney."&sign=".$sings."&ext=".$ext;
           
           $contents = file_get_contents($tjurl);
               
               if(strtolower($contents) == "ok"){
                 $data["success"] = 1;
                 $Ordertz->where("Sjt_TransID = '".$transaction_id."'")->save($data);
                 $this->Succeed($transaction_id,$_factMoney);
                 
               }
               ///////////////////////////////////////////////////////
              //  R('/Home/Index/tzajax',array($this->_post("out_trade_no")));
               // exit("success");
                  
                
                exit;
                
                
          }else{
              $Order = D("Order"); 
              $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
              $this->Sjt_Return = 0;
              $this->Sjt_Error = "0001";
              $this->Sjt_Merchant_url = $Sjt_Merchant_url;
               
              $this->RunError();  
          }
          
      }
      
        public function Succeed($TransID,$Money){
         // $TransID = $this->_request("TransID");
          //$Money = $this->_request("Money");
          echo $this->TransCode("53a平台提示您：<span style='color:#f00; font-szie:20px;'>充值成功！</span><br>");
          echo $this->TransCode("订单号：".$TransID."<br>");
          echo $this->TransCode("订单金额：".$Money);
      }
      
       private function TransCode($Code){     //中文转码
          // return iconv("GBK", "UTF-8", $Code);
           return iconv("UTF-8", "GBK", $Code);
        }
      
  }
?>
