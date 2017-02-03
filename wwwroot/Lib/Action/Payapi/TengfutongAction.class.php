<?php
  class TengfutongAction extends PayAction{
      
      public function Post(){
          $this->PayName = "Tengfutong";
            $this->TradeDate = date("YmdHis");
            //exit($this->TradeDate);
            $this->Paymoneyfen = 1;
            $this->check();
            $this->Orderadd();
                    
                    
            $tjurl = "https://www.tftpay.com/middleWeb/webHconn";
            
                    
            $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_Tengfutong_MerChantUrl.html";      //商户通知地址
                
            $this->_Return_url= "http://".C("WEB_URL")."/Payapi_Tengfutong_ReturnUrl.html";   //用户通知地址
                    
            $Sjapi = M("Sjapi");
            $this->_MerchantID = $Sjapi->where("apiname='tengfutong'")->getField("shid"); //商户ID
            $this->_Md5Key = $Sjapi->where("apiname='tengfutong'")->getField("key"); //密钥   
            $zhanghu = $Sjapi->where("apiname='tengfutong'")->getField("zhanghu");  //账号
            $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            ///////////////////////////////////////////////////////////////////////////////////////////////////////  
            $config = Array(
              "merId"     => $this->_MerchantID,                                            //商户id,8位
              "code"      => "ORD001",                                              //支付交易码
              "gateUrl"   => $tjurl,                                               //服务器端地址
              "priKeyPath"=> "cer/00033700.pfx",                                 //私钥文件
              "priKeyPass"=> "yohjAf",                                              //私钥密码
              "pubKeyPath"=> "cer/cacert.pem",                                   //公钥文件
              "charset"   => "UTF-8",                                               //编码
              "returnUrl" => $this->_Merchant_url,                         //返回地址，同步
              "notifyUrl" => $this->_Return_url,                         //返回地址,异步
              "payMethod" => "1",                                                   //默认支付方式
              "chkMethod" => "1",                                                   //签名方式
              "merBusType"=> "30",                                                  //商户业务类型
              "payType"   => "0",                                                     //付款类型
          );
          
            $ps = new ProcessingAction();
            $ps->setCharset($config["charset"]);
            $ps->setCode($config["code"]);
            $ps->setGateUrl($config["gateUrl"]);
            $ps->setMerId($config["merId"]);
          
            $ps->setParameter("returnUrl", $config["returnUrl"]);
            $ps->setParameter("merOrderUrl", $config["returnUrl"]);
            $ps->setParameter("notifyUrl", $config["notifyUrl"]);   
            $ps->setParameter("chkMethod", $config["chkMethod"]);      
            $ps->setParameter("merBusType", $config["merBusType"]);   
            $ps->setParameter("payType", $config["payType"]);    
            $ps->setParameter("payMethod", $config["payMethod"]); 
            
            $ps->setParameter("merOrderName", 'zfpt');
            $ps->setParameter("merOrderId", $this->TransID);
            $ps->setParameter("merOrderAmt", $this->sjt_OrderMoney);
            //$ps->setParameter("Price", "");
            $ps->setParameter("merOrderCount", 1);
            $ps->setParameter("Remark", "zfpt");
            
            ///////////////////////////////////////////////////////////////////
               
            //////////////////////////////////////////////////////////////////
            
            $ps->createXml();
           
            //签名处理
            $rsa = new RsaAction();
            $rsa->setPriKey($config["priKeyPath"], $config["priKeyPass"]);    //获取私钥 
            $rsa->setPubKey($config["pubKeyPath"]);   //获取公钥
            
            $signmessage = $rsa->getSslSign($ps->getXml());//签名 
             //echo("[".$signmessage."]<br>");
            //echo $config["priKeyPath"]."<br>";
           // echo $config["pubKeyPath"];
            if(!$rsa->isContinue())exit($this->TransCode("签名失败"));
            
            $ps->setParameter("Signmessage", $signmessage);//添加签名
            $ps->createXml();  //重组xml

            $data = $rsa->priEncrypt($ps->getXml()); //私钥加密
            if(!$rsa->isContinue())exit($this->TransCode("加密失败"));
            $xml = $ps->getServerData($data);   //获取服务器返回数据xml格式
            
            $data = $ps->loadXml($xml);   //解析xml
            
            
           
            //解析从服务器获取的xml，成功则解密返回的数据 
            if($data['Errorcode']=='0000000000'){
                //成功
                $xml = $rsa->pubDecrypt($data['returnMessage']);  //公钥解密

                if(!$rsa->isContinue())exit($this->TransCode("解密失败"));
                unset($data);
                $data = $ps->loadXml($xml);

                //检查是否需要验证签名  若无signmessage，则无需做签名验证
                if(isset($data['Signmessage'])){
                    $xml = preg_replace('/(<data name="Signmessage[^>]+>)/is', "", $xml);
                    if(!$rsa->getSslVerify($xml, $data['Signmessage'])){
                        exit($this->TransCode("签名验证失败"));
                    }
                }
                
                //echo "<br/>支付系统日期 :",$data['paySysDate'];
                //echo "<br/>后台流水    :",$data['serialNum'];
                //echo "<br/>商户号      :",$data['merNo'];
               // echo "<br/>商品订单号  :",$data['merOrderId'];
                //echo "<br/>商品名称    :",$data['merOrderName'];
               // echo "<br/>商品简称    :",$data['merShortName'];
               // echo "<br/>商品单价    :",$data['price'];
               // echo "<br/>订单总金额  :",$data['merOrderAmt'];
               // echo "<br/>购买数量    :",$data['merOrderCount'];
               // echo "<br/>付款类型    :",$data['payType'];
               // echo "<br/>卖方支付账号 :",$data['salePayAcct'];
               // echo "<br/>买方支付账号 :",$data['custPayAcct'];
               // echo "<br/>订单状态    :",$data['merOrderStatus'];
               // echo "<br/>支付URL     :",$data['payUrl'];
               $result = "
                            <form id='Form1' name='Form1' method='post' action='".$data['payUrl']."'>
                                    <input type='hidden' name='merNo' value='".$config['merId']."' />
                                    <input type='hidden' name='orderNo' value='".$data['merOrderId']."' />
                                  
                            </form>
                ";
                echo $result;
                $this->Echots();         
                
            }else{
                //失败
                echo $this->TransCode("<br/>错误:").$data['returnMessage']."------".$dta["Errorcode"];
            }
          
      }
      
      public function  MerChantUrl(){

                $sign = $_REQUEST['sign'];
                $xml = base64_decode($_REQUEST['xml']);
                if($xml =='' || !$xml) return false;

                $xml = simplexml_load_string($xml);

                $data = array();
                foreach($xml->group as $k=>$v){
                    $v = (array)$v;
                    foreach($v[data] as $k1=>$v1){
                        $v1 = (array)$v1;                        
                        $name  = $v1['@attributes']['name'];
                        $value  = $v1['@attributes']['value'];
                        $data[$name] = $value;
                        
                    }
                }

               // if($data['merNo'] != $this->cfg['seller_email']) return false;
                if ($data['merOrderStatus'] =='00' || $data['merOrderStatus'] =='')
                {
                       // $orderid = strip_tags($data['merOrderId'] ); 
                       // $list = order_info( $orderid );
                       // if ($list['order_amount'] == $data['merOrderAmt'])
                       // {

                           // change_order( $orderid );
                           // return TRUE;
                       // }
               //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /*************************************************************************************************************/
                     $_TransID = $data["merOrderId"];
                     $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                
                //通知跳转页面
                $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url"); 
                //商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                
                $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                
                $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
                    
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
                       
                       
                         ////////////////////////////////////////////////////////////////更新上级金额
                        $User = M("User");
                        
                        $sjUserID = $User->where("id=".$UserID)->getField("SjUserID");
                        
                        if($sjUserID){
                            
                             $Paycost = M("Paycost");
       
                             $sjfl = $Paycost->where("UserID=".$sjUserID)->getField("wy");
                        
                             $fl = $Paycost->where("UserID=".$UserID)->getField("wy");
                        
                             $tcfl = (1-$fl)-(1-$sjfl);
                             
                             $tcmoney = $tcfl*$tranAmt;
                             
                             $sjY_Money = $Money->where("UserID=".$sjUserID)->getField("Money");
                       
                             $data["Money"] = $tcmoney + $sjY_Money;
                             $Money->where("UserID=".$sjUserID)->save($data); //更新上级账户金额
                             
                             ##############################################################################################
                               $Moneybd = M("Moneybd");
                               $data["UserID"] = $sjUserID;
                               $data["money"] = $tcmoney;
                               $data["ymoney"] =  $sjY_Money;
                               $data["gmoney"] = (round($tcmoney,3) + round($sjY_Money,3));
                               $data["datetime"] =  date("Y-m-d H:i:s");
                               $data["lx"] = 2;
                               $result = $Moneybd->add($data);
                             ##############################################################################################
                        }
                                               
                       
        
                       ///////////////////////////////////////////////////////////////
                            ///////////////////////////////////////////////////////////
                       
                        $Moneybd = M("Moneybd");
                       $data["UserID"] = $UserID;
                       $data["money"] = $OrderMoney;
                       $data["ymoney"] =  $Y_Money;
                       $data["gmoney"] = (round($Y_Money,3) + round($OrderMoney,3));
                       $data["datetime"] = date("Y-m-d H:i:s");
                       $data["lx"] = 1;
                      $result = $Moneybd->add($data);
                     // exit($Moneydb);
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
                        exit;
                  /*************************************************************************************************************/
               /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
                }
                return FALSE;
      }
      
      public function ReturnUrl(){
          
             $sign = $_REQUEST['sign'];
                $xml = base64_decode($_REQUEST['xml']);
                if($xml =='' || !$xml) return false;

                $xml = simplexml_load_string($xml);

                $data = array();
                foreach($xml->group as $k=>$v){
                    $v = (array)$v;
                    foreach($v[data] as $k1=>$v1){
                        $v1 = (array)$v1;                        
                        $name  = $v1['@attributes']['name'];
                        $value  = $v1['@attributes']['value'];
                        $data[$name] = $value;
                        
                    }
                }

               // if($data['merNo'] != $this->cfg['seller_email']) return false;
                if ($data['merOrderStatus'] =='00' || $data['merOrderStatus'] =='')
                {
                       // $orderid = strip_tags($data['merOrderId'] ); 
                       // $list = order_info( $orderid );
                       // if ($list['order_amount'] == $data['merOrderAmt'])
                       // {

                           // change_order( $orderid );
                           // return TRUE;
                       // }
               //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /*************************************************************************************************************/
                 //////////////////////////////////////////////////////////////////////////////////////////////
                 /*************************************************************************************************************/
                     $_TransID = $data["merOrderId"];
                     $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                
                //通知跳转页面
                $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                
                $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                
                $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
                    
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
                       
                       
                       ////////////////////////////////////////////////////////////////更新上级金额
                        $User = M("User");
                        
                        $sjUserID = $User->where("id=".$UserID)->getField("SjUserID");
                        
                        if($sjUserID){
                            
                             $Paycost = M("Paycost");
       
                             $sjfl = $Paycost->where("UserID=".$sjUserID)->getField("wy");
                        
                             $fl = $Paycost->where("UserID=".$UserID)->getField("wy");
                        
                             $tcfl = (1-$fl)-(1-$sjfl);
                             
                             $tcmoney = $tcfl*$tranAmt;
                             
                             $sjY_Money = $Money->where("UserID=".$sjUserID)->getField("Money");
                       
                             $data["Money"] = $tcmoney + $sjY_Money;
                             $Money->where("UserID=".$sjUserID)->save($data); //更新上级账户金额
                             
                             ##############################################################################################
                               $Moneybd = M("Moneybd");
                               $data["UserID"] = $sjUserID;
                               $data["money"] = $tcmoney;
                               $data["ymoney"] =  $sjY_Money;
                               $data["gmoney"] = (round($tcmoney,3) + round($sjY_Money,3));
                               $data["datetime"] =  date("Y-m-d H:i:s");
                               $data["lx"] = 2;
                              $result = $Moneybd->add($data);
                               
                             ##############################################################################################
                        }
                                               
                       
        
                       ///////////////////////////////////////////////////////////////
                            ///////////////////////////////////////////////////////////
                       $Moneybd = M("Moneybd");
                       $data["UserID"] = $UserID;
                       $data["money"] = $OrderMoney;
                       $data["ymoney"] =  $Y_Money;
                       $data["gmoney"] = (round($Y_Money,3) + round($OrderMoney,3));
                       $data["datetime"] = date("Y-m-d H:i:s");
                       $data["lx"] = 1;
                      $result = $Moneybd->add($data);
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
               $datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$_TransID."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$tranAmt."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
               $tjurl = $Sjt_Return_url."?".$datastr; 
               $contents = fopen($tjurl,"r"); 
               $contents=fread($contents,4096); 
               if($contents == "ok"){
                 $data["success"] = 1;
                 $Ordertz->where("Sjt_TransID = '".$_TransID."'")->save($data);
               }
               return true;
              
                  /*************************************************************************************************************/
                /////////////////////////////////////////////////////////////////////////////////////////////
                  /*************************************************************************************************************/
               /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
                }
                return FALSE;
          
      }
      
       private function TransCode($Code){     //中文转码
           return iconv("GBK", "UTF-8", $Code);
      }
      
      public function tccs(){
          
                       //$Order = M("Order");
                       $Moneydb = M("Moneydb");
                       $Moneybd = M("Moneybd");
                      // $data["UserID"] = 449;
                       //$data["money"] = 11;
                      // $data["ymoney"] =  22;
                       //$data["gmoney"] = 1010;
                      // $data["datetime"] = date("Y-m-d H:i:s");
                       //$data["lx"] = 1;
                      $result =  $Moneybd->where("id=2")->select();
                     // $result = $Moneydb->add();
                      if($result){
                          echo "ok";
                      }else{
                          echo "no[".$result."]";
                      }
      }
  }
?>
