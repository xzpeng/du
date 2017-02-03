<?php
    class QilingBankAction extends PayAction{
        
         public function Post(){
         
          $this->PayName = "Qiling";
          $this->TradeDate = date("Y-m-d H:i:s");
          $this->Paymoneyfen = 1;
          $this->check();
          $this->Orderadd();
          
          //通知地址
           $this->_Return_url= "http://".C("WEB_URL")."/Payapi_QilingBank_ReturnUrl.html";
            $tjurl = "http://paybank.yzch.net/Pay.aspx";
           ////////////////////////////////////////////////
            $Sjapi = M("Sjapi");
            $this->_MerchantID = $Sjapi->where("apiname='qiling'")->getField("shid"); //商户ID
            $this->_Md5Key = $Sjapi->where("apiname='qiling'")->getField("key"); //密钥   
             
            $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            ///////////////////////////////////////////////
            
            $userid = $this->_MerchantID;
            $orderid = $this->TransID;
            $money = $this->sjt_OrderMoney;
            $url = $this->_Return_url;
            $aurl = "";
            $bankid = $this->Sjt_PayID;
            $ext = "20kk";
            
            $sings = "userid=".$userid."&orderid=".$orderid."&bankid=".$bankid."&keyvalue=".$this->_Md5Key;
            
            $sings = md5($sings);
    
      
?>
<form name='Form1' id="Form1" action='<?php echo $tjurl; ?>' method='post'>
<input type='hidden' name='userid'                    value='<?php echo $userid; ?>'>
<input type='hidden' name='orderid'                value='<?php echo $orderid; ?>'>
<input type='hidden' name='money'                value='<?php echo $money; ?>'>
<input type='hidden' name='url'                    value='<?php echo $url; ?>'>
<input type='hidden' name='aurl'                    value='<?php echo $aurl; ?>'>
<input type='hidden' name='bankid'                    value='<?php echo $bankid; ?>'>
<input type='hidden' name='ext'                    value='<?php echo $ext; ?>'>
<input type='hidden' name='sign'                value='<?php echo $sings; ?>'>
</form>
<?php    
 $this->Echots();  
    }
    
    
    public function ReturnUrl(){
        $returncode = $this->_request("returncode");
        $userid = $this->_request("userid");
        $orderid = $this->_request("orderid");
        $money = $this->_request("money");
        $sign = $this->_request("sign");
        $ext = $this->_request("ext");
        
        $Userapiinformation = D("Userapiinformation");
                
        $Sjapi = M("Sjapi");
        $_Md5Key = $Sjapi->where("apiname='qiling'")->getField("key"); //密钥   
        
         $sings = "returncode=".$returncode."&userid=".$userid."&orderid=".$orderid."&keyvalue=".$_Md5Key;
            
         $sings = md5($sings);
         
         if($sings == $sign && intval($returncode) == 1 ){
             
             $r6_Order = $orderid;
             
              $Order = D("Order");
                $UserID = $Order->where("TransID = '".$r6_Order."'")->getField("UserID");
                
                //通知跳转页面
                $Sjt_Merchant_url = $Order->where("TransID = '".$r6_Order."'")->getField("Sjt_Merchant_url"); 
                //后台通知地址
               // $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$r6_Order."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$r6_Order."'")->getField("Username");
                
                $OrderMoney = $Order->where("TransID = '".$r6_Order."'")->getField("OrderMoney");
                
                 $tranAmt = $Order->where("TransID = '".$r6_Order."'")->getField("trademoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$r6_Order."'")->getField("Zt");  
                
              $typepay = $Order->where("TransID = '".$r6_Order."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$r6_Order."'")->getField("payname");
              //////////////////////////////////////////////////////////////////////
              $Paycost = M("Paycost");
              $Sjfl = M("Sjfl");
              if($typepay == 0 || $typepay == 1  || $typepay == 3){
                  $fv = $Paycost->where("UserID=".$UserID)->getField("wy");
                  if($fv == 0){
                      $fv = $Paycost->where("UserID=0")->getField("wy");
                  } 
                  
                  $sjflmoney = $Sjfl->where("jkname='yibao'")->getField("wy"); //上家费率
                  
              }else{
                  $ywm = $this->dkname($payname);
                   $fv = $Paycost->where("UserID=".$UserID)->getField($ywm);
                  if($fv == 0){
                      $fv = $Paycost->where("UserID=0")->getField($ywm);
                  } 
                  
                  $sjflmoney = $Sjfl->where("jkname='yibao'")->getField($ywm); //上家费率
              }
              
              if($sjflmoney == 0){
                     $sjflmoney = 1;
                  }
              
              /////////////////////////////////////////////////////////////////////
               $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                $_factMoney = $r3_Amt;
                
                
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
                $count = $Order->where("TransID = '".$r6_Order."'")->count();
                if($count <= 0){
                    $Diaodangkg = 1;
                    $data["ddpl"] = $ddpl - 1;
                    $System->where("UserID=0")->save($data);//订单不变
                }
               /************************掉单设置*****************************/
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
                   //$data["sjflmoney"] = $_factMoney - $_factMoney * $sjflmoney; //上家手续费
                   $Order->where("TransID = '".$r6_Order."'")->save($data); //将订单设置为成功
                   
                }
        }else{
            $Order->where("TransID = '".$r6_Order."'")->delete();
        }
              ////////////////////////////////////////////////////////////////
        ############################################################################################################################################
    #    需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
    #    并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生
           $sings = "returncode=".$returncode."&userid=".($UserID+10000)."&orderid=".$orderid."&keyvalue=".$Sjt_Key;
    
           $sings = md5($sings);
    
           $tjurl = $Sjt_Merchant_url."?returncode=".$returncode."&userid=".($UserID+10000)."&orderid=".$orderid."&money=".$money."&sign=".$sings."&ext=".$ext;
           
           $contents = file_get_contents($tjurl);
               
               if($contents == "ok"){
                  exit("ok"); 
               }
    
         }
        
    }
    
    }
?>
