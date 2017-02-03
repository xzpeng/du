<?php
  class GuofubaoAction extends PayAction{
      public function Post(){
            $this->PayName = "Guofubao";
            $this->TradeDate = date("YmdHis");
            //exit($this->TradeDate);
            $this->Paymoneyfen = 1;
            $this->check();
            $this->Orderadd();
                    
                    
            $tjurl = "https://www.gopay.com.cn/PGServer/Trans/WebClientAction.do"; 
            
                    
            //$this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_Guofubao_MerChantUrl.html";      //商户通知地址
            $this->_Merchant_url= "";      //商户通知地址
                
            $this->_Return_url= "http://".C("WEB_URL")."/Payapi_Guofubao_ReturnUrl.html";   //用户通知地址
                    
            $Sjapi = M("Sjapi");
            $this->_MerchantID = $Sjapi->where("apiname='guofubao'")->getField("shid"); //商户ID
            $this->_Md5Key = $Sjapi->where("apiname='guofubao'")->getField("key"); //密钥   
            $zhanghu = $Sjapi->where("apiname='guofubao'")->getField("zhanghu");  //账号
            $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            $version = "2.1";    // 网关版本号        
            $charset = 1;      // 字符集   
            $language = 1;      // 网关语言版本  
            $signType = 1;        //  报文加密方式
            $tranCode = 8888;          //  交易代码
            $merchantID = $this->_MerchantID;        // 商户代码
            $merOrderNum = $this->TransID;        // 订单号
            $tranAmt = $this->sjt_OrderMoney;                   // 交易金额
            $feeAmt = "";                       // 商户提取佣金金额
            $currencyType = 156;             //币种
            $frontMerUrl = $this->_Merchant_url;                 //  商户前台通知地址
            $backgroundMerUrl = $this->_Return_url;         // 商户后台通知地址
            $tranDateTime = $this->TradeDate;                   //  交易时间
            $virCardNoIn = $zhanghu;                       //  国付宝转入账户
            $tranIP = $this->getClientIP();                                   // 用户浏览器IP
            $isRepeatSubmit = 0;                     //  订单是否允许重复提交
            $goodsName = "53anet";                                 //   商品名称
            $goodsDetail = "53anet";                                //    商品详情
            $buyerName = "53anet";                                      // 买方姓名
            $buyerContact = "53anet";                                  // 买方联系方式
            $merRemark1 = "53anet";                                       //  商户备用
            $merRemark2 = "53anet";                                         //  商户备用
            $bankCode = $this->Sjt_PayID;                                               //  银行代码
            $userType = 1;     
            $gfb = new gfbAction();                                            //  用户类型
            $gopayServerTime = $gfb->getGopayServerTime();                              // 服务器时间
            
             $signStr='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=[]gopayOutOrderId=[]tranIP=['.$tranIP.']respCode=[]gopayServerTime=['.$gopayServerTime.']VerficationCode=['.$this->_Md5Key.']';
            // echo $signStr;
             $signValue = md5($signStr);
?>
<form name='Form1' id="Form1" action='<?php echo $tjurl; ?>' method='post'>   
<input type="hidden" id="version" name="version" value="<?echo "$version"; ?>" size="50"/>
        <input type="hidden" id="charset" name="charset" value="<?echo "$charset"; ?>"  size="50"/>
        <input type="hidden" id="language" name="language" value="<?echo "$language"; ?>"  size="50"/>
        <input type="hidden" id="signType" name="signType" value="<?echo "$signType"; ?>"  size="50"/>
        <input type="hidden" id="tranCode" name="tranCode" value="<?echo "$tranCode"; ?>"  size="50"/>
        <input type="hidden" id="merchantID" name="merchantID" value="<?echo "$merchantID"; ?>"  size="50"/>
        <input type="hidden" id="merOrderNum" name="merOrderNum" value="<?echo "$merOrderNum"; ?>"  size="50" />
        <input type="hidden" id="tranAmt" name="tranAmt" value="<?echo "$tranAmt"; ?>"  size="50"/>
        <input type="hidden" id="feeAmt" name="feeAmt" value="<?echo "$feeAmt"; ?>"  size="50"/>
        <input type="hidden" id="currencyType" name="currencyType" value="<?echo "$currencyType"; ?>"  size="50"/>
        <input type="hidden"  id="frontMerUrl" name="frontMerUrl" value="<?echo "$frontMerUrl"; ?>"  size="50"/>
        <input type="hidden"  id="backgroundMerUrl" name="backgroundMerUrl" value="<?echo "$backgroundMerUrl"; ?>"  size="50"/>
        <input type="hidden"  id="tranDateTime" name="tranDateTime" value="<?echo "$tranDateTime"; ?>"  size="50"/>
        <input type="hidden"  id="virCardNoIn" name="virCardNoIn" value="<?echo "$virCardNoIn"; ?>"  size="50"/>
        <input type="hidden"  id="tranIP" name="tranIP" value="<?echo "$tranIP"; ?>"  size="50"/>
        <input type="hidden"  id="isRepeatSubmit" name="isRepeatSubmit" value="<?echo "$isRepeatSubmit"; ?>"  size="50"/>
        <input type="hidden"  id="goodsName" name="goodsName" value="<?echo "$goodsName"; ?>"  size="50"/>
        <input type="hidden"  id="goodsDetail" name="goodsDetail" value="<?echo "$goodsDetail"; ?>"  size="50"/>
        <input type="hidden"  id="buyerName" name="buyerName" value="<?echo "$buyerName"; ?>"  size="50"/>
        <input type="hidden"  id="buyerContact" name="buyerContact" value="<?echo "$buyerContact"; ?>"  size="50"/>
        <input type="hidden"  id="merRemark1" name="merRemark1" value="<?echo "$merRemark1"; ?>"  size="50"/>
        <input type="hidden"  id="merRemark2" name="merRemark2" value="<?echo "$merRemark2"; ?>"  size="50"/>
        <input type="hidden"  id="signValue" name="signValue" value="<?echo "$signValue"; ?>"  size="50"/>
        <input type="hidden"  id="bankCode" name="bankCode" value="<?echo "$bankCode"; ?>"  size="50"/>
        <input type="hidden"  id="userType" name="userType" value="<?echo "$userType"; ?>"  size="50"/>
        <input type="hidden"  id="gopayServerTime" name="gopayServerTime" value="<?echo "$gopayServerTime"; ?>"  size="50"/>
    
    <input type="submit" value="ok"/>
</form>
<?php  
               $this->Echots();             
      }
      
      public function  MerChantUrl(){
          $respCode = $this->_post("respCode");
          if($respCode != "0000"){
              
              echo $respCode;
              echo "<br>";
              $msgExt = $this->_post("msgExt");
              echo $msgExt;
            
          }else{
          /**************************************************************************************************************************/ 
            $version = $_POST["version"];
            $charset = $_POST["charset"];
            $language = $_POST["language"];
            $signType = $_POST["signType"];
            $tranCode = $_POST["tranCode"];
            $merchantID = $_POST["merchantID"];
            $merOrderNum = $_POST["merOrderNum"];
            $tranAmt = $_POST["tranAmt"];
            $feeAmt = $_POST["feeAmt"];
            $frontMerUrl = $_POST["frontMerUrl"];
            $backgroundMerUrl = $_POST["backgroundMerUrl"];
            $tranDateTime = $_POST["tranDateTime"];
            $tranIP = $_POST["tranIP"];
            $respCode = $_POST["respCode"];
            $msgExt = $_POST["msgExt"];
            $orderId = $_POST["orderId"];
            $gopayOutOrderId = $_POST["gopayOutOrderId"];
            $bankCode = $_POST["bankCode"];
            $tranFinishTime = $_POST["tranFinishTime"];
            $merRemark1 = $_POST["merRemark1"];
            $merRemark2 = $_POST["merRemark2"];
            $signValue = $_POST["signValue"];
            
            $Sjapi = M("Sjapi");
            $Md5Key = $Sjapi->where("apiname='guofubao'")->getField("key"); //密钥   
            $signValue2='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=['.$orderId.']gopayOutOrderId=['.$gopayOutOrderId.']tranIP=['.$tranIP.']respCode=['.$respCode.']gopayServerTime=[]VerficationCode=['.$Md5Key.']';
            $signValue2 = md5($signValue2);
            if($signValue==$signValue2){
                    /*************************************************************************************************************/
                     $_TransID = $merOrderNum;
                     $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                
                //通知跳转页面
                $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url"); 
                //商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                
                $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
              
               $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
                    
                    $Paycost = M("Paycost");
                      $Sjfl = M("Sjfl");
                      if($typepay == 0 || $typepay == 1 || $typepay == 3){
                          $fv = $Paycost->where("UserID=".$UserID)->getField("wy");
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField("wy");
                          } 
                          
                          $sjflmoney = $Sjfl->where("jkname='yinlian'")->getField("wy"); //上家费率
                          
                      }else{
                          $ywm = $this->dkname($payname);
                           $fv = $Paycost->where("UserID=".$UserID)->getField($ywm);
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField($ywm);
                          } 
                          
                          $sjflmoney = $Sjfl->where("jkname='yinlian'")->getField($ywm); //上家费率
                      }
                      
                      if($sjflmoney == 0){
                             $sjflmoney = 1;
                          }
                           
                     $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");
                
                     $Userapiinformation = D("Userapiinformation");
                     $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                     
                     
                  if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                       $Money = D("Money");
                       $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                       
                       $data["Money"] = $OrderMoney + $Y_Money;
                       $Money->where("UserID=".$UserID)->save($data); //更新账户金额
                        
                               //-------------------------------------------自动提款-------------------------------------------------
                              $this->zdtk($UserID);
                               //-------------------------------------------自动提款-------------------------------------------------
                /////////////////////////////////////////////////////////////////////////////////////////////////
                   
                    $User = M("User");
                        
                        $sjUserID = $User->where("id=".$UserID)->getField("SjUserID");
                        
                        if($sjUserID){
                            
                             $Paycost = M("Paycost");
       
                             $sjfl = $Paycost->where("UserID=".$sjUserID)->getField("wy");
                        
                             $fl = $Paycost->where("UserID=".$UserID)->getField("wy");
                        
                            if($sjfl == 0){
                                 $sjfl = $Paycost->where("UserID=0")->getField("wy");
                             }
                             
                             if($fl == 0){
                                 $fl = $Paycost->where("UserID=0")->getField("wy");
                             }
                        
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
                              
                               //-------------------------------------------自动提款-------------------------------------------------
                              $this->zdtk($sjUserID);
                               //-------------------------------------------自动提款-------------------------------------------------
                             
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
                       $data["sjflmoney"] = $tranAmt - $tranAmt * $sjflmoney; //上家手续费
                       $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                   
                   }
                        $_SuccTime = date("YmdHis"); 
                        $_Result = "1";
                        $_resultDesc = "00";
                        echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
                        echo "<input type=\"hidden\" name=\"Sjt_MerchantID\" value=\"".$Sjt_MerchantID."\">";
                        echo "<input type=\"hidden\" name=\"Sjt_Username\" value=\"".$Sjt_Username."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_TransID\" value=\"".$_TransID."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$_Result."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"".$_resultDesc."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_factMoney\" value=\"".$tranAmt."\">";       
                        echo "<input type=\"hidden\" name=\"Sjt_SuccTime\" value=\"".$_SuccTime."\">";
                        ////////////////r9_BType/////////////////////
                        echo "<input type='hidden' name='Sjt_BType' value='1' />";
                        ////////////////r9_BType////////////////////
                        
                        $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$tranAmt.$_SuccTime."1".$Sjt_Key);
                        echo "<input type=\"hidden\" name=\"Sjt_Sign\" value=\"".$Sjt_Md5Sign."\">";  
                        echo "</from>";
                        echo "<script type=\"text/javascript\">";
                        echo "document.Form1.submit();";
                        echo "</script>";
              
                  /*************************************************************************************************************/
            }else{
                echo "请不要非法提交！";
            }
          /**************************************************************************************************************************/   
              
          }
          
      }
      
      public function ReturnUrl(){
             /**************************************************************************************************************************/ 
            $version = $_POST["version"];
            $charset = $_POST["charset"];
            $language = $_POST["language"];
            $signType = $_POST["signType"];
            $tranCode = $_POST["tranCode"];
            $merchantID = $_POST["merchantID"];
            $merOrderNum = $_POST["merOrderNum"];
            $tranAmt = $_POST["tranAmt"];
            $feeAmt = $_POST["feeAmt"];
            $frontMerUrl = $_POST["frontMerUrl"];
            $backgroundMerUrl = $_POST["backgroundMerUrl"];
            $tranDateTime = $_POST["tranDateTime"];
            $tranIP = $_POST["tranIP"];
            $respCode = $_POST["respCode"];
            $msgExt = $_POST["msgExt"];
            $orderId = $_POST["orderId"];
            $gopayOutOrderId = $_POST["gopayOutOrderId"];
            $bankCode = $_POST["bankCode"];
            $tranFinishTime = $_POST["tranFinishTime"];
            $merRemark1 = $_POST["merRemark1"];
            $merRemark2 = $_POST["merRemark2"];
            $signValue = $_POST["signValue"];
            
            $Sjapi = M("Sjapi");
            $Md5Key = $Sjapi->where("apiname='guofubao'")->getField("key"); //密钥   
            $signValue2='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=['.$orderId.']gopayOutOrderId=['.$gopayOutOrderId.']tranIP=['.$tranIP.']respCode=['.$respCode.']gopayServerTime=[]VerficationCode=['.$Md5Key.']';
            $signValue2 = md5($signValue2);
            if($signValue==$signValue2){
                if($respCode=='0000'){
                //////////////////////////////////////////////////////////////////////////////////////////////
                 /*************************************************************************************************************/
                     $_TransID = $merOrderNum;
                     $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                
                //通知跳转页面
                $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                
                $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
              
               $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
                    
                    $Paycost = M("Paycost");
                      $Sjfl = M("Sjfl");
                      if($typepay == 0 || $typepay == 1 || $typepay == 3){
                          $fv = $Paycost->where("UserID=".$UserID)->getField("wy");
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField("wy");
                          } 
                          
                          $sjflmoney = $Sjfl->where("jkname='yinlian'")->getField("wy"); //上家费率
                          
                      }else{
                          $ywm = $this->dkname($payname);
                           $fv = $Paycost->where("UserID=".$UserID)->getField($ywm);
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField($ywm);
                          } 
                          
                          $sjflmoney = $Sjfl->where("jkname='yinlian'")->getField($ywm); //上家费率
                      }
                      
                      if($sjflmoney == 0){
                             $sjflmoney = 1;
                          }
                           
                     $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");
                
                     $Userapiinformation = D("Userapiinformation");
                     $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                     
                     
                  if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                       $Money = D("Money");
                       $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                       
                       $data["Money"] = $OrderMoney + $Y_Money;
                       $Money->where("UserID=".$UserID)->save($data); //更新账户金额
                        /////////////////////////////////////////////////////////////////////////////////////////////////
                   
                    $User = M("User");
                        
                        $sjUserID = $User->where("id=".$UserID)->getField("SjUserID");
                        
                        if($sjUserID){
                            
                             $Paycost = M("Paycost");
       
                             $sjfl = $Paycost->where("UserID=".$sjUserID)->getField("wy");
                        
                             $fl = $Paycost->where("UserID=".$UserID)->getField("wy");
                        
                            if($sjfl == 0){
                                 $sjfl = $Paycost->where("UserID=0")->getField("wy");
                             }
                             
                             if($fl == 0){
                                 $fl = $Paycost->where("UserID=0")->getField("wy");
                             }
                        
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
                       $data["sjflmoney"] = $tranAmt - $tranAmt * $sjflmoney; //上家手续费
                       $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                   
                   }
                   
                $_SuccTime = date("YmdHis"); 
                $_Result = "1";
                $_resultDesc = "00";    
               $Ordertz = M("Ordertz");
               $ordertzlist = $Ordertz->where("Sjt_TransID = '".$_TransID."'")->select();
               if(!$ordertzlist){
                   $data["Sjt_MerchantID"] = $Sjt_MerchantID;
                   $data["Sjt_UserName"] = $Sjt_Username;
                   $data["Sjt_TransID"] = $_TransID;
                   $data["Sjt_Return"] = $_Result;
                   $data["Sjt_Error"] = $_resultDesc;
                  //$_factMoney = $_factMoney/100;
                   $tranAmt = number_format($tranAmt,3);
                   $data["Sjt_factMoney"] = $tranAmt;
                   $data["Sjt_SuccTime"] = $_SuccTime;
                   $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$tranAmt.$_SuccTime."2".$Sjt_Key);
                   $data["Sjt_Sign"] = $Sjt_Md5Sign;
                   $data["Sjt_urlname"] = $Sjt_Return_url;
                   $data["Sjt_BType"] = 2;
                   $Ordertz->add($data);
               } 
               //$datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$_TransID."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$tranAmt."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
              // $tjurl = $Sjt_Return_url."?".$datastr; 
              // $contents = fopen($tjurl,"r"); 
              // $contents=fread($contents,4096); 
              // if($contents == "ok"){
               //  $data["success"] = 1;
               //  $Ordertz->where("Sjt_TransID = '".$_TransID."'")->save($data);
              // }
              
               $sings = "returncode=1&userid=".($UserID+10000)."&orderid=".$_TransID."&keyvalue=".$Sjt_Key;
    
           $sings = md5($sings);
    
           $tjurl = $Sjt_Return_url."?returncode=1&userid=".($UserID+10000)."&orderid=".$_TransID."&money=".$tranAmt."&sign=".$sings."&ext=".$ext;
           
           $contents = file_get_contents($tjurl);
               
               if(strtolower($contents) == "ok"){
                 $data["success"] = 1;
                 $Ordertz->where("Sjt_TransID = '".$_TransID."'")->save($data);
                 
               }
    
              
                  /*************************************************************************************************************/
                /////////////////////////////////////////////////////////////////////////////////////////////
                  echo 'RespCode=0000|JumpURL=http://'.C("WEB_URL").'/Payapi_Guofubao_Succeed.html?TransID='.$_TransID."&Money=".$tranAmt; 
                }else{
                  echo 'RespCode=9999|JumpURL=';
                } 
            }
          /**************************************************************************************************************************/   
      }
      
      public function Succeed(){
          $TransID = $this->_request("TransID");
          $Money = $this->_request("Money");
          echo "53a平台提示您：<span style='color:#f00; font-szie:20px;'>充值成功！</span><br>";
          echo "订单号：".$TransID."<br>";
          echo "订单金额：".$Money;
      }
       private function getClientIP()  {       //获取IP
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
