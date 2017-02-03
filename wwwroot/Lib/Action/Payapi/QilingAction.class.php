<?php
  class QilingAction extends PayAction{
      
      public function Post(){
         
          $this->PayName = "Qiling";
          $this->TradeDate = date("Y-m-d H:i:s");
          $this->Paymoneyfen = 1;
          $this->check();
          $this->Orderadd();
          
          //通知地址
           $this->_Return_url= "http://".C("WEB_URL")."/Payapi_Qiling_ReturnUrl.html";
            
           ////////////////////////////////////////////////
            $Sjapi = M("Sjapi");
            $this->_MerchantID = $Sjapi->where("apiname='qiling'")->getField("shid"); //商户ID
            $this->_Md5Key = $Sjapi->where("apiname='qiling'")->getField("key"); //密钥   
             
            $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            ///////////////////////////////////////////////
            
            $userid = $this->_MerchantID;
            $orderid = $this->TransID;
            $typeid = $this->Sjt_PayID;  //产品类型编号
            $productid = $this->Sjt_PayID.$this->_request("Sjt_ProudctID"); //产品代码编号
            $cardno = $this->CardNO;
            $cardpwd = $this->CardPWD;
            $money = $this->sjt_OrderMoney;
            $url = $this->_Return_url;
            //$ext = $this->AdditionalInfo;
           // $ext = $this->_request("p7_Pdesc");
           $ext = "20kk";
            
            $sign = MD5(strtolower("userid=".$userid."&orderno=".$orderid."&typeid=".$typeid."&cardno=".$cardno."&encpwd=".$this->_request("p5_Pid")."&cardpwd=".$cardpwd."&cardpwdenc=".$this->_request("p6_Pcat")."&money=".$money."&url=".$url."&keyvalue=".$this->_Md5Key));
            // $sign ="userid=".$p1_MerId."&orderno=".$p2_Order."&typeid=".$this->_request("typeid")."&cardno=".$Sjt_CardNumber."&encpwd=".$p5_Pid."&cardpwd=".$Sjt_CardPassword."&cardpwdenc=".$p6_Pcat."&money=".$p3_Amt."&url=".$p8_Url."&keyvalue=".$key;
             
//$urlStr = "http://tong.yzch.net/sale.aspx?userid=".$userid."&orderid=".$orderid."&typeid=".$typeid."&productid=".$productid."&cardno=".$cardno."&cardpwd=".$cardpwd."&money=".$money."&url=".$url."&sign=".$sign."&ext=".$ext;

$urlStr = "http://rootvip.yzch.net/sale.ashx?userid=".$userid."&orderno=".$orderid."&typeid=".$typeid."&cardno=".$cardno."&encpwd=".$this->_request("p5_Pid")."&cardpwd=".$cardpwd."&cardpwdenc=".$this->_request("p6_Pcat")."&money=".$money."&url=".$url."&sign=".$sign."&ext=".$ext;



if($this->_request("jianrong") == "ok"){
  
    // $result = var_dump(file_get_contents($urlStr));
  
    $result =file_get_contents($urlStr); //提交
    

   // $result =file_get_contents($urlStr); //提交
    
   exit($result);
    


}


//$contents = fopen($urlStr,"r"); 
//$contents=fread($contents,4096); 
//$fhstr = explode("&",$contents);
//$fhsign = md5($fhstr[0]."&".$fhstr[1]."&keyvalue=".$this->_Md5Key);
//$signmd5 = explode("=",$fhstr[2]);
$result =file_get_contents($urlStr);
//if($fhsign == $signmd5[1]){
 if($result){
 $b=split("&",$result);
  $fhzt=split("=",$b[0]);
   // $fhzt = explode("=",$fhstr[0]);
   if($fhzt[1] == 1){
       echo "ok"."&".$orderid;
   }else{
        $error =split("=",$b[2]);
        switch($error[1]){
              case "2001":
              echo "参数为空";
              break;
              
              case "2002":
              echo "无效的商户";
              break;  
              
              case "2003":
              echo "签名错误";
              break;
              
              case "2008":
              echo "订单号已存在";
              break;
              
              case "2009":
              echo "产品被用户关闭或维护";
              break;
              
              case "2011":
              echo "卡号或卡密长度错误或金额不正确";
              break;
              
              case "2014":
              echo "该卡已超过系统规定的失败次数";
              break;
              
              case "2015":
              echo "该卡已成功";
              break;
              
               case "2016":
              echo "该卡已失败";
              break;
              
              case "2017":
              echo "访卡正在处理中";
              break;

              case "3000":
              echo "未知";
              break;
              
              default:
              echo "未知错误".$fhzt[1];
            }   
   }
    
    //echo $fhzt[1];
}else{
  //  echo $fhsign."-----".$fhstr[2]."<br>";
   // echo $signmd5[1]."<br>";
   //
     echo "未知错误";
}

      }
      
       public function MerChantUrl(){
           
       }
       
        public function ReturnUrl(){
           
            
            //$returncode = $this->_request("returncode"); //返回代码,1代表收购成功,11代表收购失败
           // $userid = $this->_request("userid"); //商户ID
           // $orderid = $this->_request("orderid"); //商户流水号
           // $typeid = $this->_request("typeid"); //产品类型ID
           // $productid = $this->_request("productid"); //产品ID
           // $cardno = $this->_request("cardno"); //卡号
           // $cardpwd = $this->_request("cardpwd"); //卡密
           // $money = $this->_request("money"); //产品提交金额
           // $realmoney = $this->_request("realmoney"); //产品实际金额
           // $cardstatus = $this->_request("cardstatus"); // 卡状态 1:成功 0:失败  其它为异常
           // $sign = $this->_request("sign"); //签名数据 32位小写的组合加密验证串
           // $ext = $this->_request("ext"); //商户扩展信息，返回时原样返回，此参数如用到中文，请注意转码
           // $errtype = $this->_request("errtype");
            
            
            $returncode = $this->_request("returncode"); //返回代码,1代表收购成功,11代表收购失败
            $userid = $this->_request("userid"); //商户ID
            $orderid = $this->_request("orderno"); //商户流水号

            $yzchorderno = $this->_request("yzchorderno"); //商户流水号            
           // $typeid = $this->_request("typeid"); //产品类型ID
           // $productid = $this->_request("productid"); //产品ID
            
          ///  $cardno = $this->_request("cardno"); //卡号
          //  $cardpwd = $this->_request("cardpwd"); //卡密
            
            $money = $this->_request("money"); //产品提交金额
            $realmoney = $this->_request("realmoney"); //产品实际金额
            $cardstatus = $this->_request("cardstatus"); // 卡状态 1:成功 0:失败  其它为异常
            $sign = $this->_request("sign"); //签名数据 32位小写的组合加密验证串
            $ext = $this->_request("ext"); //商户扩展信息，返回时原样返回，此参数如用到中文，请注意转码
            $errtype = $this->_request("errtype");
            $message = $this->_request("message");
            
            
            $Sjapi = M("Sjapi");
            $_Md5Key = $Sjapi->where("apiname='qiling'")->getField("key"); //密钥   
            //$signs=md5("returncode=".$returncode."&userid=".$userid."&orderid=".$orderid."&typeid=".$typeid."&productid=".$productid."&cardno=".$cardno."&cardpwd=".$cardpwd."&money=".$money."&realmoney=".$realmoney."&cardstatus=".$cardstatus."&keyvalue=".$_Md5Key."");
            
            $signs=md5("returncode=".$returncode."&yzchorderno=".$yzchorderno."&userid=".$userid."&orderno=".$orderid."&money=".$money."&realmoney=".$realmoney."&keyvalue=".$_Md5Key);
            
            if($sign == $signs){
                
                if($returncode != 1){
                   
                   ///////////////////////////////////////////////////////////////////
                    $Order = D("Order");
                    $Sjt_MerchantID = $Order->where("TransID = '".$orderid."'")->getField("UserID");
                     
                   $Ordertz = M("Ordertz");
                   $ordertzlist = $Ordertz->where("Sjt_TransID = '".$orderid."'")->select();
                   if(!$ordertzlist){
                       $data["Sjt_MerchantID"] = $userid;
                       //$data["Sjt_UserName"] = $Sjt_Username;
                       $data["Sjt_TransID"] = $orderid;
                       $data["Sjt_Return"] = $returncode;
                       $data["Sjt_Error"] = $errtype;
                       $data["success "] = 2;
                       $Ordertz->add($data);
                   }
                $Order = D("Order");
                
                $UserID = $Order->where("TransID = '".$orderid."'")->getField("UserID");    
                $Sjt_Return_url = $Order->where("TransID = '".$orderid."'")->getField("Sjt_Return_url");
                 
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                 
             $signs="returncode=".$returncode."&yzchorderno=".$yzchorderno.strtolower("&userid=".(intval($UserID)+10000)."&orderno=".$orderid."&money=".$money."&realmoney=".$realmoney."&keyvalue=".$Sjt_Key);
             $md5_sign = md5($signs);
              
               $tjurl="returncode=".$returncode."&yzchorderno=".$yzchorderno."&userid=".(intval($UserID)+10000)."&orderno=".$orderid."&money=".$money."&realmoney=".$realmoney."&usermoney=".$this->_request("usermoney")."&errtype=".$errtype."&message=".$message."&ext=".$ext."&sign=".$md5_sign;
               
               $tjurl = $Sjt_Return_url."?".$tjurl;
               
               $contents = file_get_contents($tjurl);
               
              
                ///////////////////////////////////////////////////////////////////
                    
                }else{
                    
                    
                    ////////////////////////////////////////////////////////////////
                $Order = D("Order");
                
                $UserID = $Order->where("TransID = '".$orderid."'")->getField("UserID");
                  
                    //后台通知地址
                $Sjt_Return_url = $Order->where("TransID = '".$orderid."'")->getField("Sjt_Return_url");
                    //盛捷通商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$orderid."'")->getField("UserID");
                     //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$orderid."'")->getField("Username");
                  
                $typepay = $Order->where("TransID = '".$orderid."'")->getField("typepay");
                  
                $payname = $Order->where("TransID = '".$orderid."'")->getField("payname");
                
                  $tranAmt = $Order->where("TransID = '".$orderid."'")->getField("trademoney");
                  
                  //////////////////////////////////////////////////////////////////////
                     $Paycost = M("Paycost");
                      $Sjfl = M("Sjfl");
                      if($typepay == 0 || $typepay == 1){
                          $fv = $Paycost->where("UserID=".$UserID)->getField("wy");
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField("wy");
                          }
                          
                  $sjflmoney = $Sjfl->where("jkname='qiling'")->getField("wy"); //上家费率 
                      }else{
                          $ywm = $this->dkname($payname);
                           $fv = $Paycost->where("UserID=".$UserID)->getField($ywm);
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField($ywm);
                          }
                          
                  $sjflmoney = $Sjfl->where("jkname='qiling'")->getField($ywm); //上家费率       
                      }
                      
                  if($sjflmoney == 0){
                     $sjflmoney = 1;
                  }
                  //////////////////////////////////////////////////////////////////////
                $Sjt_Zt = $Order->where("TransID = '".$orderid."'")->getField("Zt");
                
                $Userapiinformation = D("Userapiinformation");
                
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                    
                
                if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                   $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   $OrderMoney = $money * $fv; //实际金额
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
                   $data["lx"] = 2;
                   $Moneybd->add($data);
                   //////////////////////////////////////////////////////////
                   
                   
                   $data["Zt"] = 1;   
                   $data["TcMoney"] = $tcmoney;
                   //$data["TradeDate"] = date("Y-m-d h:i:s");  
                   $jiaoyijine = $money * $fv;
                   $data["OrderMoney"] = $jiaoyijine;     //实际订单金额
                   $data["trademoney"] = $money;   //交易金额
                   $data["sxfmoney"] = $money - $jiaoyijine; //手续费
                   $data["sjflmoney"] = $money - $money * $sjflmoney; //上家手续费
                   $Order->where("TransID='".$orderid."'")->save($data); //将订单设置为成功     
                   
                }
                 ////////////////////////////////////////////////////////  
                $Ordertz = M("Ordertz");
                $ordertzlist = $Ordertz->where("Sjt_TransID = '".$orderid."'")->select();
               if(!$ordertzlist){
                   $data["Sjt_MerchantID"] = $userid;
                   $data["Sjt_UserName"] = $Sjt_Username;
                   $data["Sjt_TransID"] = $orderid;
                   $data["Sjt_Return"] = $returncode;
                   $data["Sjt_Error"] = $errtype;
                   $_factMoney = number_format($money,3);
                   $data["Sjt_factMoney"] = $_factMoney;
                   $_SuccTime = date("Ymdhis");
                   $data["Sjt_SuccTime"] = $_SuccTime;
                   $Sjt_Md5Sign = md5($userid.$Sjt_Username.$orderid.$returncode.$errtype.$_factMoney.$_SuccTime."2".$Sjt_Key);
                   $data["Sjt_Sign"] = $Sjt_Md5Sign;
                   $data["Sjt_urlname"] = $Sjt_Return_url;
                   $data["Sjt_BType"] = 2;
                   $Ordertz->add($data);
               }
               ///////////////////////////////////////////////////////
              // $datastr = "Sjt_MerchantID=".$userid."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$orderid."&Sjt_Return=".$returncode."&Sjt_Error=".$errtype."&Sjt_factMoney=".$_factMoney."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
              // $tjurl = $Sjt_Return_url."?".$datastr; 
               //$contents = fopen($tjurl,"r"); 
               //$contents=fread($contents,4096); 
              // if($contents == "ok"){
                // $data["success"] = 1;
                // $Ordertz->where("Sjt_TransID = '".$orderid."'")->save($data);
              // }
              
              //$signs="returncode=".$returncode."&userid=".(intval($UserID)+10000)."&orderid=".$orderid."&typeid=".$typeid."&productid=".$productid."&cardno=".$cardno."&cardpwd=".$cardpwd."&money=".$money."&realmoney=".$realmoney."&cardstatus=".$cardstatus."&keyvalue=".$Sjt_Key."";
 $signs="returncode=".$returncode."&yzchorderno=".$yzchorderno.strtolower("&userid=".(intval($UserID)+10000)."&orderno=".$orderid."&money=".$money."&realmoney=".$realmoney."&keyvalue=".$Sjt_Key);
             $md5_sign = md5($signs);
              
               $tjurl="returncode=".$returncode."&yzchorderno=".$yzchorderno."&userid=".(intval($UserID)+10000)."&orderno=".$orderid."&money=".$money."&realmoney=".$realmoney."&usermoney=".$this->_request("usermoney")."&errtype=".$errtype."&message=".$message."&ext=".$ext."&sign=".$md5_sign;
               
               $tjurl = $Sjt_Return_url."?".$tjurl;
               
               $contents = file_get_contents($tjurl);
               
               if(strtolower($contents) == "ok"){
                 $data["success"] = 1;
                 $Ordertz->where("Sjt_TransID = '".$orderid."'")->save($data);
                  exit("ok"); 
               }
              
               
                    
                }
                
            }else{
                 exit("returncode=".$returncode."&yzchorderno=".$yzchorderno."&userid=".$userid."&orderno=".$orderid."&money=".$money."&realmoney=".$realmoney."&keyvalue=".$_Md5Key."------".$this->Sjt_Error);
            }
            
       }
       
       
      
  }
?>
