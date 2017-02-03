<?php
  class YidongAction extends PayAction{
      
     public function Post(){
         header("Content-Type:text/html; charset=utf-8");
          $this->PayName = "yidong";
          $this->TradeDate = date("Y-m-d H:i:s");
          $this->Paymoneyfen = 100;
          $this->check();
          $this->Orderadd();
            
          $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_Yidong_MerChantUrl.html";      //商户通知地址
        
           $this->_Return_url= "http://".C("WEB_URL")."/Payapi_Yidong_ReturnUrl.html";   //用户通知地址
          ////////////////////////////////////////////////
          $Sjapi = M("Sjapi");
          $this->_MerchantID = $Sjapi->where("apiname='10086'")->getField("shid"); //商户ID
          $this->_Md5Key = $Sjapi->where("apiname='10086'")->getField("key"); //密钥   
          $zhanghu = $Sjapi->where("apiname='".$this->PayName."'")->getField("zhanghu");
          $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
          ///////////////////////////////////////////////
          
          $characterSet = "02";
          $callbackUrl = $this->_Merchant_url;
          $notifyUrl = $this->_Return_url;
          $ipAddress = $this->getClientIP();
          $merchantId = $this->_MerchantID;
          $requestId = $this->TransID;
          $signType = "MD5";
          $type = "GWDirectPay";
          $version = "2.0.0";
        //  $merchantCert = $this->_Md5Key;
          
          $amount = $this->sjt_OrderMoney;
          $bankAbbr = $this->Sjt_PayID;
          $currency = "00";
          $orderDate = date("Ymd");
          $orderId = $this->TransID;
          $merAcDate = date("Ymd");
          $period = "5";
          $periodUnit = "01";
          $merchantAbbr = $this->ProductName;
          $productDesc = $this->AdditionalInfo;
          $productId = $this->TransID;
          $productName = $this->ProductName;
          $productNum = 1;
          $reserved1 = "aaaaaaaaaaaaaaaaaaaa";
          $reserved2 = "bbbbbbbbbbbbbbbbbbbb";
          $userToken = "88china";
          $showUrl = "http://p.88china.com/";
          $couponsFlag = "00";
          
          //############################################################################################################
         
        //#############################################################################################################
          
         $signData = $characterSet.$callbackUrl  . $notifyUrl   . $ipAddress 
                      . $merchantId  . $requestId   . $signType    . $type
                          . $version     . $amount      . $bankAbbr    . $currency
                          . $orderDate   . $orderId     . $merAcDate   . $period 
                          . $periodUnit  . $merchantAbbr. $productDesc . $productId
                          . $productName . $productNum  . $reserved1   . $reserved2
                          . $userToken   . $showUrl     . $couponsFlag;    
          
         $hmac=$this->MD5sign($this->_Md5Key,$signData);
          
         echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"https://ipos.10086.cn/ips/cmpayService\">";
    ?>
    <input type="hidden" name="characterSet" value="<?php echo $characterSet ?>" />
            <input type="hidden" name="callbackUrl" value="<?php echo $callbackUrl ?>" />
            <input type="hidden" name="notifyUrl" value="<?php echo $notifyUrl ?>" />
            <input type="hidden" name="ipAddress" value="<?php echo $ipAddress ?>" />
            <input type="hidden" name="merchantId" value="<?php echo $merchantId ?>" />
            <input type="hidden" name="requestId" value="<?php echo $requestId ?>" />
            <input type="hidden" name="signType" value="<?php echo $signType ?>" />
            <input type="hidden" name="type" value="<?php echo $type ?>" />
            <input type="hidden" name="version" value="<?php echo $version ?>" />
            <input type="hidden" name="hmac" value="<?php echo $hmac ?>" />
            <input type="hidden" name="amount" value="<?php echo $amount ?>" />
            <input type="hidden" name="bankAbbr" value="<?php echo $bankAbbr ?>" />
            <input type="hidden" name="currency" value="<?php echo $currency ?>" />
            <input type="hidden" name="orderDate" value="<?php echo $orderDate ?>" />
            <input type="hidden" name="orderId" value="<?php echo $orderId ?>" />
            <input type="hidden" name="merAcDate" value="<?php echo $merAcDate ?>" />
            <input type="hidden" name="period" value="<?php echo $period ?>" />
            <input type="hidden" name="periodUnit" value="<?php echo $periodUnit ?>" />
            <input type="hidden" name="merchantAbbr" value="<?php echo $merchantAbbr ?>" />
            <input type="hidden" name="productDesc" value="<?php echo $productDesc ?>" />
            <input type="hidden" name="productId" value="<?php echo $productId ?>" />
            <input type="hidden" name="productName" value="<?php echo $productName ?>" />
            <input type="hidden" name="productNum" value="<?php echo $productNum ?>" />
            <input type="hidden" name="reserved1" value="<?php echo $reserved1 ?>" />
            <input type="hidden" name="reserved2" value="<?php echo $reserved2 ?>" />
            <input type="hidden" name="userToken" value="<?php echo $userToken ?>" />
            <input type="hidden" name="showUrl" value="<?php echo $showUrl ?>" />
            <input type="hidden" name="couponsFlag" value="<?php echo $couponsFlag ?>" />
    <?php     
          echo "正在提交......";
          echo "</form>";
        
           $this->Echots();       
     }
     
     
    public function MerChantUrl(){
        //####################################################################################
        $merchantId       = $_REQUEST["merchantId"];
        $payNo                   = $_REQUEST["payNo"];
        $returnCode       = $_REQUEST["returnCode"];
        $message              = $_REQUEST["message"];
        $signType          = $_REQUEST["signType"];
        $type             = $_REQUEST["type"];
        $version            = $_REQUEST["version"];
        $amount         = $_REQUEST["amount"];
        $amtItem              = $_REQUEST["amtItem"];        
        $bankAbbr              = $_REQUEST["bankAbbr"];
        $mobile               = $_REQUEST["mobile"];
        $orderId              = $_REQUEST["orderId"];
        $payDate              = $_REQUEST["payDate"];
        $accountDate    = $_REQUEST["accountDate"];
        $reserved1          = $_REQUEST["reserved1"];
        $reserved2          = $_REQUEST["reserved2"];
        $status                  = $_REQUEST["status"];
        $orderDate      = $_REQUEST["orderDate"];
        $fee            = $_REQUEST["fee"];
        $vhmac                  = $_REQUEST["hmac"];
        //$signKey        = $GLOBALS['signKey'];
        $Sjapi = M("Sjapi");
        $signKey = $Sjapi->where("apiname='10086'")->getField("key"); //密钥   
        $_TransID = $orderId;
        $_Result = $returnCode;
        $_resultDesc = $message;
        $_SuccTime = $payDate;
        //###################################################################################
          //组装签字符串
        $signData = $merchantId .$payNo.$returnCode .$message
               .$signType   .$type        .$version    .$amount
               .$amtItem    .$bankAbbr    .$mobile     .$orderId
               .$payDate    .$accountDate .$reserved1  .$reserved2
               .$status     .$orderDate   .$fee;
               
        //MD5方式签名
        $hmac=$this->MD5sign($signKey,$signData);
     //####################################################################################
     if($hmac == $vhmac){
         //*********************************************************************************//
         if($returnCode==000000){
            ##################################################################################################
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
                
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
              
               $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
              //////////////////////////////////////////////////////////////////////
              $Paycost = M("Paycost");
              $Sjfl = M("Sjfl");
              if($typepay == 0 || $typepay == 1){
                  $fv = $Paycost->where("UserID=".$UserID)->getField("wy");
                  if($fv == 0){
                      $fv = $Paycost->where("UserID=0")->getField("wy");
                  } 
                  
                  $sjflmoney = $Sjfl->where("jkname='baofu'")->getField("wy"); //上家费率
                  
              }else{
                  $ywm = $this->dkname($payname);
                   $fv = $Paycost->where("UserID=".$UserID)->getField($ywm);
                  if($fv == 0){
                      $fv = $Paycost->where("UserID=0")->getField($ywm);
                  } 
                  
                  $sjflmoney = $Sjfl->where("jkname='baofu'")->getField($ywm); //上家费率
              }
              
              if($sjflmoney == 0){
                     $sjflmoney = 1;
                  }
              
              /////////////////////////////////////////////////////////////////////
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                $_factMoney = $amount / 100;
           ##################################################################################################
           
                            /////////////////////////////////////////////////////////////////
                /************************掉单设置*****************************/
                $System = M("System");
                $Diaodangkg = 0;   //掉单开关，默认关闭
                //获取系统掉单总开关 $Diaodan_OnOff  0为关闭，1为打开
                $Diaodan_OnOff = $System->where("UserID=0")->getField("Diaodan_OnOff");
                if($Diaodan_OnOff == 1){
              //获取单独系统掉单的开关 $Diaodan_User_OnOff 0为关闭，1为打开
    $Diaodan_User_OnOff = $System->where("UserID=".$UserID)->getField("Diaodan_User_OnOff");
                    if($Diaodan_User_OnOff == 1){
                        $Diaodangkg = 1;  //设置为掉单打开
                    //是响应系统掉单设置还是单独设置 $dd_Diaodan_OnOff 0为独立，1到系统
    $dd_Diaodan_OnOff = $System->where("UserID=".$UserID)->getField("Diaodan_OnOff");
                        if($dd_Diaodan_OnOff == 0){
                            //独立设置
                            /////////////////////////////////////////////////
    //开始时间                        
    $Diaodan_Kdate = $System->where("UserID=".$UserID)->getField("Diaodan_Kdate");  
    //结束时间
    $Diaodan_Sdate = $System->where("UserID=".$UserID)->getField("Diaodan_Sdate");  
    //开始金额
    $Diaodan_Kmoney = $System->where("UserID=".$UserID)->getField("Diaodan_Kmoney");
    //结束金额 
    $Diaodan_Smoney = $System->where("UserID=".$UserID)->getField("Diaodan_Smoney");
    //掉单频率
    $Diaodan_Pinlv = $System->where("UserID=".$UserID)->getField("Diaodan_Pinlv");
    //掉单类型
    $Diaodan_Type = $System->where("UserID=".$UserID)->getField("Diaodan_Type"); 
                            ////////////////////////////////////////////////
                        }else{
                            //系统设置
                            if($dd_Diaodan_OnOff == 1){
                                //系统设置
                                /////////////////////////////////////////////////
    //开始时间                        
    $Diaodan_Kdate = $System->where("UserID=0")->getField("Diaodan_Kdate");  
    //结束时间
    $Diaodan_Sdate = $System->where("UserID=0")->getField("Diaodan_Sdate");  
    //开始金额
    $Diaodan_Kmoney = $System->where("UserID=0")->getField("Diaodan_Kmoney");
    //结束金额 
    $Diaodan_Smoney = $System->where("UserID=0")->getField("Diaodan_Smoney");
    //掉单频率
    $Diaodan_Pinlv = $System->where("UserID=0")->getField("Diaodan_Pinlv");
    //掉单类型
    $Diaodan_Type = $System->where("UserID=0")->getField("Diaodan_Type");                              
                                ////////////////////////////////////////////////
                            }
                        }
                    }
                }
                
                
                if($Diaodangkg == 1){
                    $hour = date("H");  //获取当前的小时
                    if($Diaodan_Kdate > $Diaodan_Sdate){
                        $hour = $hour + 24;
                        $Diaodan_Sdate = $Diaodan_Sdate + 24;
                    }
                    
                    $ddpl = $System->where("UserID=0")->getField("ddpl");
                    $ddpl = $ddpl + 1; //掉单频率
                    
                    if($hour >= $Diaodan_Kdate && $hour <= $Diaodan_Sdate){
                    if($_factMoney >= $Diaodan_Kmoney && $_factMoney <= $Diaodan_Smoney){
                           
                        
                           if($ddpl % $Diaodan_Pinlv == 0){
                               $Diaodangkg = 1;
                           }else{
                              $Diaodangkg = 0;
                           } 
                            
                        }else{
                            $Diaodangkg = 0;
                        }
                        
                        $data["ddpl"] = $ddpl;
                        $System->where("UserID=0")->save($data);//增加掉单频率
                        
                        
                    }else{
                        $Diaodangkg = 0;
                        $data["ddpl"] = 0;
                        $System->where("UserID=0")->save($data);//不在掉单时间设置为0
                    }
                    
                     
                }
                
                //如果订单不存在，直接设置为掉单
                $count = $Order->where("TransID = '".$_TransID."'")->count();
                if($count <= 0){
                    $Diaodangkg = 1;
                    $data["ddpl"] = $ddpl - 1;
                    $System->where("UserID=0")->save($data);//订单不变
                }
               /************************掉单设置*****************************/
              ////////////////////////////////////////////////////////////////
                           if($Diaodangkg == 0){        
                            
                             if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                               $Money = D("Money");
                               $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                               
                               $data["Money"] = $OrderMoney + $Y_Money;
                               $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                                /////////////////////////////////////////////////////////////////////////////////////////////////
                   
                    $User = M("User");
                        
                        $sjUserID = $User->where("id=".$UserID)->getField("SjUserID");
                        
                        if($sjUserID){
                            
                             $Paycost = M("Paycost");
       
                             $sjfl = $Paycost->where("UserID=".$sjUserID)->getField("wy");
                        
                             $fl = $Paycost->where("UserID=".$UserID)->getField("wy");
                        
                             $tcfl = (1-$fl)-(1-$sjfl);
                             
                             if($tcfl < 0){
                                 $tcfl = 0;
                             }
                             
                             $tcmoney = $tcfl*$tranAmt;
                             
                             $sjY_Money = $Money->where("UserID=".$sjUserID)->getField("Money");
                       
                             $data["Money"] = $tcmoney + $sjY_Money;
                             $Money->where("UserID=".$sjUserID)->save($data); //更新上级账户金额
                             
                             
                             ##############################################################################################
                               $Moneybd = M("Moneybd");
                               $data["UserID"] = $sjUserID;
                               $data["money"] = $tcmoney;
                               $data["ymoney"] =  $sjY_Money;
                               $data["gmoney"] = $tcmoney + $sjY_Money;
                               $data["datetime"] = date("Y-m-d H:i:s");
                               $data["lx"] = 2;
                                $result = $Moneybd->add($data);
                             ##############################################################################################
                             
                        }
                 ///////////////////////////////////////////////////////////
                               $Moneybd = M("Moneybd");
                               $data["UserID"] = $UserID;
                               $data["money"] = $OrderMoney;
                               $data["ymoney"] =  $Y_Money;
                               $data["gmoney"] = $Y_Money + $OrderMoney;
                               $data["datetime"] = date("Y-m-d H:i:s");
                               $data["lx"] = 1;
                               $Moneybd->add($data);
                               //////////////////////////////////////////////////////////
                               
                               $data["Zt"] = 1;   
                               $data["TcMoney"] = $tcmoney;
                               $data["sjflmoney"] = $_factMoney - $_factMoney * $sjflmoney; //上家手续费
                               $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                               
                            }
                    }else{
                        $Order->where("TransID = '".$_TransID."'")->delete();
                    }
                    
                    
                    if($Diaodangkg == 0 || ($Diaodangkg == 1 && $Diaodan_Type == 3)){  
                        echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
                        echo "<input type=\"hidden\" name=\"Sjt_MerchantID\" value=\"".$Sjt_MerchantID."\">";
                        echo "<input type=\"hidden\" name=\"Sjt_Username\" value=\"".$Sjt_Username."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_TransID\" value=\"".$_TransID."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$_Result."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"".$_resultDesc."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_factMoney\" value=\"".$_factMoney."\">";       
                        echo "<input type=\"hidden\" name=\"Sjt_SuccTime\" value=\"".$_SuccTime."\">";
                        ////////////////r9_BType/////////////////////
                        echo "<input type='hidden' name='Sjt_BType' value='1' />";
                        ////////////////r9_BType////////////////////
                        echo "<input type='hidden' name='Sjt_key' value='".$Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$_factMoney.$_SuccTime."1".$Sjt_Key."' />";
                        $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$_factMoney.$_SuccTime."1".$Sjt_Key);
                        echo "<input type=\"hidden\" name=\"Sjt_Sign\" value=\"".$Sjt_Md5Sign."\">";  
                        echo "</from>";
                        echo "<script type=\"text/javascript\">";
                        echo "document.Form1.submit();";
                        echo "</script>";
                        
                        exit;
                      }else{
                          ///////////////////////////////////////////////////////////////////////
                          echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
                        echo "</from>";
                        echo "<script type=\"text/javascript\">";
                        echo "document.Form1.submit();";
                        echo "</script>";
                        
                        exit;
                          //////////////////////////////////////////////////////////////////////
                      }
              ##########################################################################################################
         }else{
             
                $this->Sjt_Return = 0;
                $this->Sjt_Error = $message;
                $Order = D("Order");    
                $this->Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
                $this->RunError();
                
         }
        //*********************************************************************************// 
     }else{
         //*********************************************************************//
         $Order = D("Order"); 
            $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
            $this->Sjt_Return = 0;
            $this->Sjt_Error =  "9";  //密钥错误
            $this->Sjt_Merchant_url = $Sjt_Merchant_url;
            $this->RunError();
         //********************************************************************//
     }
     //##################################################################################
    }  
    
   public function ReturnUrl(){
               //####################################################################################
        $merchantId       = $_REQUEST["merchantId"];
        $payNo                   = $_REQUEST["payNo"];
        $returnCode       = $_REQUEST["returnCode"];
        $message              = $_REQUEST["message"];
        $signType          = $_REQUEST["signType"];
        $type             = $_REQUEST["type"];
        $version            = $_REQUEST["version"];
        $amount         = $_REQUEST["amount"];
        $amtItem              = $_REQUEST["amtItem"];        
        $bankAbbr              = $_REQUEST["bankAbbr"];
        $mobile               = $_REQUEST["mobile"];
        $orderId              = $_REQUEST["orderId"];
        $payDate              = $_REQUEST["payDate"];
        $accountDate    = $_REQUEST["accountDate"];
        $reserved1          = $_REQUEST["reserved1"];
        $reserved2          = $_REQUEST["reserved2"];
        $status                  = $_REQUEST["status"];
        $orderDate      = $_REQUEST["orderDate"];
        $fee            = $_REQUEST["fee"];
        $vhmac                  = $_REQUEST["hmac"];
        //$signKey        = $GLOBALS['signKey'];
        $Sjapi = M("Sjapi");
        $signKey = $Sjapi->where("apiname='10086'")->getField("key"); //密钥   
        $_TransID = $orderId;
        $_Result = $returnCode;
        $_resultDesc = $message;
        $_SuccTime = $payDate;
        //###################################################################################
          //组装签字符串
        $signData = $merchantId .$payNo.$returnCode .$message
               .$signType   .$type        .$version    .$amount
               .$amtItem    .$bankAbbr    .$mobile     .$orderId
               .$payDate    .$accountDate .$reserved1  .$reserved2
               .$status     .$orderDate   .$fee;
               
        //MD5方式签名
        $hmac=$this->MD5sign($signKey,$signData);
     //####################################################################################
     if($hmac == $vhmac){
         //*********************************************************************************//
         if($returnCode==000000){
            ##################################################################################################
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
                
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
              
               $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
              //////////////////////////////////////////////////////////////////////
              $Paycost = M("Paycost");
              $Sjfl = M("Sjfl");
              if($typepay == 0 || $typepay == 1){
                  $fv = $Paycost->where("UserID=".$UserID)->getField("wy");
                  if($fv == 0){
                      $fv = $Paycost->where("UserID=0")->getField("wy");
                  } 
                  
                  $sjflmoney = $Sjfl->where("jkname='baofu'")->getField("wy"); //上家费率
                  
              }else{
                  $ywm = $this->dkname($payname);
                   $fv = $Paycost->where("UserID=".$UserID)->getField($ywm);
                  if($fv == 0){
                      $fv = $Paycost->where("UserID=0")->getField($ywm);
                  } 
                  
                  $sjflmoney = $Sjfl->where("jkname='baofu'")->getField($ywm); //上家费率
              }
              
              if($sjflmoney == 0){
                     $sjflmoney = 1;
                  }
              
              /////////////////////////////////////////////////////////////////////
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                $_factMoney = $amount / 100;
           ##################################################################################################
           
                            /////////////////////////////////////////////////////////////////
                /************************掉单设置*****************************/
                $System = M("System");
                $Diaodangkg = 0;   //掉单开关，默认关闭
                //获取系统掉单总开关 $Diaodan_OnOff  0为关闭，1为打开
                $Diaodan_OnOff = $System->where("UserID=0")->getField("Diaodan_OnOff");
                if($Diaodan_OnOff == 1){
              //获取单独系统掉单的开关 $Diaodan_User_OnOff 0为关闭，1为打开
    $Diaodan_User_OnOff = $System->where("UserID=".$UserID)->getField("Diaodan_User_OnOff");
                    if($Diaodan_User_OnOff == 1){
                        $Diaodangkg = 1;  //设置为掉单打开
                    //是响应系统掉单设置还是单独设置 $dd_Diaodan_OnOff 0为独立，1到系统
    $dd_Diaodan_OnOff = $System->where("UserID=".$UserID)->getField("Diaodan_OnOff");
                        if($dd_Diaodan_OnOff == 0){
                            //独立设置
                            /////////////////////////////////////////////////
    //开始时间                        
    $Diaodan_Kdate = $System->where("UserID=".$UserID)->getField("Diaodan_Kdate");  
    //结束时间
    $Diaodan_Sdate = $System->where("UserID=".$UserID)->getField("Diaodan_Sdate");  
    //开始金额
    $Diaodan_Kmoney = $System->where("UserID=".$UserID)->getField("Diaodan_Kmoney");
    //结束金额 
    $Diaodan_Smoney = $System->where("UserID=".$UserID)->getField("Diaodan_Smoney");
    //掉单频率
    $Diaodan_Pinlv = $System->where("UserID=".$UserID)->getField("Diaodan_Pinlv");
    //掉单类型
    $Diaodan_Type = $System->where("UserID=".$UserID)->getField("Diaodan_Type"); 
                            ////////////////////////////////////////////////
                        }else{
                            //系统设置
                            if($dd_Diaodan_OnOff == 1){
                                //系统设置
                                /////////////////////////////////////////////////
    //开始时间                        
    $Diaodan_Kdate = $System->where("UserID=0")->getField("Diaodan_Kdate");  
    //结束时间
    $Diaodan_Sdate = $System->where("UserID=0")->getField("Diaodan_Sdate");  
    //开始金额
    $Diaodan_Kmoney = $System->where("UserID=0")->getField("Diaodan_Kmoney");
    //结束金额 
    $Diaodan_Smoney = $System->where("UserID=0")->getField("Diaodan_Smoney");
    //掉单频率
    $Diaodan_Pinlv = $System->where("UserID=0")->getField("Diaodan_Pinlv");
    //掉单类型
    $Diaodan_Type = $System->where("UserID=0")->getField("Diaodan_Type");                              
                                ////////////////////////////////////////////////
                            }
                        }
                    }
                }
                
                
                if($Diaodangkg == 1){
                    $hour = date("H");  //获取当前的小时
                    if($Diaodan_Kdate > $Diaodan_Sdate){
                        $hour = $hour + 24;
                        $Diaodan_Sdate = $Diaodan_Sdate + 24;
                    }
                    
                    $ddpl = $System->where("UserID=0")->getField("ddpl");
                    $ddpl = $ddpl + 1; //掉单频率
                    
                    if($hour >= $Diaodan_Kdate && $hour <= $Diaodan_Sdate){
                    if($_factMoney >= $Diaodan_Kmoney && $_factMoney <= $Diaodan_Smoney){
                           
                        
                           if($ddpl % $Diaodan_Pinlv == 0){
                               $Diaodangkg = 1;
                           }else{
                              $Diaodangkg = 0;
                           } 
                            
                        }else{
                            $Diaodangkg = 0;
                        }
                        
                        $data["ddpl"] = $ddpl;
                        $System->where("UserID=0")->save($data);//增加掉单频率
                        
                        
                    }else{
                        $Diaodangkg = 0;
                        $data["ddpl"] = 0;
                        $System->where("UserID=0")->save($data);//不在掉单时间设置为0
                    }
                    
                     
                }
                
                //如果订单不存在，直接设置为掉单
                $count = $Order->where("TransID = '".$_TransID."'")->count();
                if($count <= 0){
                    $Diaodangkg = 1;
                    $data["ddpl"] = $ddpl - 1;
                    $System->where("UserID=0")->save($data);//订单不变
                }
               /************************掉单设置*****************************/
              ////////////////////////////////////////////////////////////////
                           if($Diaodangkg == 0){        
                            
                             if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                               $Money = D("Money");
                               $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                               
                               $data["Money"] = $OrderMoney + $Y_Money;
                               $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                                /////////////////////////////////////////////////////////////////////////////////////////////////
                   
                    $User = M("User");
                        
                        $sjUserID = $User->where("id=".$UserID)->getField("SjUserID");
                        
                        if($sjUserID){
                            
                             $Paycost = M("Paycost");
       
                             $sjfl = $Paycost->where("UserID=".$sjUserID)->getField("wy");
                        
                             $fl = $Paycost->where("UserID=".$UserID)->getField("wy");
                        
                             $tcfl = (1-$fl)-(1-$sjfl);
                             
                             if($tcfl < 0){
                                 $tcfl = 0;
                             }
                             
                             $tcmoney = $tcfl*$tranAmt;
                             
                             $sjY_Money = $Money->where("UserID=".$sjUserID)->getField("Money");
                       
                             $data["Money"] = $tcmoney + $sjY_Money;
                             $Money->where("UserID=".$sjUserID)->save($data); //更新上级账户金额
                             
                             
                             ##############################################################################################
                               $Moneybd = M("Moneybd");
                               $data["UserID"] = $sjUserID;
                               $data["money"] = $tcmoney;
                               $data["ymoney"] =  $sjY_Money;
                               $data["gmoney"] = $tcmoney + $sjY_Money;
                               $data["datetime"] = date("Y-m-d H:i:s");
                               $data["lx"] = 2;
                                $result = $Moneybd->add($data);
                             ##############################################################################################
                             
                        } 
                 ///////////////////////////////////////////////////////////
                               $Moneybd = M("Moneybd");
                               $data["UserID"] = $UserID;
                               $data["money"] = $OrderMoney;
                               $data["ymoney"] =  $Y_Money;
                               $data["gmoney"] = $Y_Money + $OrderMoney;
                               $data["datetime"] = date("Y-m-d H:i:s");
                               $data["lx"] = 1;
                               $Moneybd->add($data);
                               //////////////////////////////////////////////////////////
                               
                               $data["Zt"] = 1;   
                                $data["TcMoney"] = $tcmoney;
                               $data["sjflmoney"] = $_factMoney - $_factMoney * $sjflmoney; //上家手续费
                               $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                               
                            }
                    }else{
                        $Order->where("TransID = '".$_TransID."'")->delete();
                    }
                    
                    
                    if($Diaodangkg == 0 || ($Diaodangkg == 1 && $Diaodan_Type == 3)){  
                        $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$_factMoney.$_SuccTime."1".$Sjt_Key);
                        $datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$_TransID."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$_factMoney."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
               $tjurl = $Sjt_Return_url."?".$datastr; 
               $contents = fopen($tjurl,"r"); 
               $contents=fread($contents,4096); 
               if($contents == "ok"){
                 $data["success"] = 1;
                 $Ordertz->where("Sjt_TransID = '".$_TransID."'")->save($data);
               }else{
                   
               }
               exit("SUCCESS");
             }
              ##########################################################################################################
         }else{
             
                $this->Sjt_Return = 0;
                $this->Sjt_Error = $message;
                $Order = D("Order");    
                $this->Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
                $this->RunError();
                
         }
        //*********************************************************************************// 
     }else{
         //*********************************************************************//
        echo "no_no";
         //********************************************************************//
     }
     //##################################################################################
   }  
     
 /*获取用户IP地址*/
private function getClientIP()  
{  
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
        
     //MD5方式签名
       private function MD5sign($okey,$odata)
        {
             $signdata=$this->hmac("",$odata);                 
             return $this->hmac($okey,$signdata);
        }
        
       private function hmac ($key, $data)
        {
          $key = iconv('gb2312', 'utf-8', $key);
          $data = iconv('gb2312', 'utf-8', $data);
          $b = 64;
          if (strlen($key) > $b) {
          $key = pack("H*",md5($key));
          }
          $key = str_pad($key, $b, chr(0x00));
          $ipad = str_pad('', $b, chr(0x36));
          $opad = str_pad('', $b, chr(0x5c));
          $k_ipad = $key ^ $ipad ;
          $k_opad = $key ^ $opad;
         return md5($k_opad . pack("H*",md5($k_ipad . $data)));
        }    
  }
?>
