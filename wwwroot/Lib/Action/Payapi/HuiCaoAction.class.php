<?php
  class HuiCaoAction extends PayAction{
      
      public function Post(){
            $this->PayName = "HuiCao";
            $this->TradeDate = date("YmdHis");
            $this->Paymoneyfen = 1;
            $this->check();
            $this->Orderadd();
            
            
            $tjurl = "";
            $NoticeType = "";
            
            $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_HuiCao_MerChantUrl.html";      //商户通知地址
        
        $this->_Return_url= "http://".C("WEB_URL")."/Payapi_HuiCao_ReturnUrl.html";   //用户通知地址
            
             $Sjapi = M("Sjapi");
             $this->_MerchantID = $Sjapi->where("apiname='huicao'")->getField("shid"); //商户ID
             $this->_Md5Key = $Sjapi->where("apiname='huicao'")->getField("key"); //密钥   
             $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
             
             /////////////////////////////////////////////////////////////////////////////////////////////////////////
                $MD5key = $this->_Md5Key;        //MD5私钥
     $MerNo =  $this->_MerchantID;                    //商户号
     $BillNo =$this->TransID;        //[必填]订单号(商户自己产生：要求不重复)
     $Amount = $this->sjt_OrderMoney;                //[必填]订单金额
  
     $ReturnURL = $this->_Merchant_url;             //[必填]返回数据给商户的地址(商户自己填写):::注意请在测试前将该地址告诉我方人员;否则测试通不过
     $Remark = "";  //[选填]升级。
     

    $md5src = $MerNo."&".$BillNo."&".$Amount."&".$ReturnURL."&".$MD5key;        //校验源字符串
    $SignInfo = strtoupper(md5($md5src));        //MD5检验结果


     $AdviceURL =$this->_Return_url;   //[必填]支付完成后，后台接收支付结果，可用来更新数据库值
     $orderTime = date("YmdHis");   //[必填]交易时间YYYYMMDDHHMMSS
     $defaultBankNumber =$this->Sjt_PayID;   //[选填]银行代码s 

     //送货信息(方便维护，请尽量收集！如果没有以下信息提供，请传空值:'')
     //因为关系到风险问题和以后商户升级的需要，如果有相应或相似的内容的一定要收集，实在没有的才赋空值,谢谢。

    $products="products info";// '------------------物品信息
 ?>
 <form action="https://pay.ecpss.cn/sslpayment" method="post" name="Form1" id="Form1">
  <table align="center" style="display: none;">
    
    <tr>
      <td></td>
      <td><input type="hidden" name="MerNo" value="<?=$MerNo?>"></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="hidden" name="BillNo" value="<?=$BillNo?>"></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="hidden" name="Amount" value="<?=$Amount?>"></td>
    </tr>

    <tr>
      <td></td>
      <td><input type="hidden" name="ReturnURL" value="<?=$ReturnURL?>" ></td>
    </tr>
    
     <tr>
      <td></td>
      <td><input type="hidden" name="AdviceURL" value="<?=$AdviceURL?>" ></td>
    </tr>
     <tr>
      <td></td>
      <td><input type="hidden" name="orderTime" value="<?=$orderTime?>">></td>
    </tr>
    
     <tr>
      <td></td>
      <td><input type="hidden" name="defaultBankNumber" value="<?=$defaultBankNumber?>"></td>
    </tr>

    <tr>
      <td></td>
      <td><input type="hidden" name="SignInfo" value="<?=$SignInfo?>"></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="hidden" name="Remark" value="<?=$Remark?>"></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="hidden" name="products" value="<?=$products?>"></td>
    </tr>
  </table>
  <p align="center">
    <input type="submit" name="b1" value="Payment">
  </p>
</form>
 <?php   
  $this->Echots();   
             ////////////////////////////////////////////////////////////////////////////////////////////////////////
      }
      
      public function MerChantUrl(){
          
          $Sjapi = M("Sjapi");
        $_Md5Key = $Sjapi->where("apiname='huicao'")->getField("key"); //密钥   
           //MD5私钥
    $MD5key = $_Md5Key;

    //订单号
    $BillNo = $this->_request("BillNo");
    //金额
    $Amount = $this->_request("Amount");
    //支付状态
    $Succeed = $this->_request("Succeed");
    //支付结果
    $Result = $this->_request("Result");
    //取得的MD5校验信息
    $SignMD5info = $this->_request("SignMD5info"); 
                                    //SignMD5info
    //备注
    $Remark = $this->_request("Remark");


     //  echo($BillNo."========".$Amount."=========".$Succeed."==========".$Result."============".$SignMD5info."==========".$Remark.">>>>>>>>>".$$MD5key = $_Md5Key."<br>");
    //校验源字符串
  $md5src = $BillNo."&".$Amount."&".$Succeed."&".$MD5key;
  //MD5检验结果
    $md5sign = strtoupper(md5($md5src));
    
     if ($SignMD5info==$md5sign){  
           if ($Succeed!="88"){
                 $this->Sjt_Return = 0;
                $this->Sjt_Error = "";
                $Order = D("Order");    
                $this->Sjt_Merchant_url = $Order->where("TransID = '".$BillNo."'")->getField("Sjt_Merchant_url");
                $this->RunError();
           }else{
               ////////////////////////////////////////////////////////////////////////////////////////////////
               $Order = D("Order");
               
               $_TransID =  $BillNo;
               
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                
                //通知跳转页面
                $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url"); 
               // exit($Sjt_Merchant_url);
                //后台通知地址
               // $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                
                $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                
                 $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
              //////////////////////////////////////////////////////////////////////
              
               $_factMoney=  $tranAmt;
              
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
                
               // $_factMoney = $_factMoney / 100;
                
                
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
                  ## $Money = D("Money");
                   ## $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   
                  ##  $data["Money"] = $OrderMoney + $Y_Money;
                 ##   $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                   
                      ////////////////////////////////////////////////////////////////更新上级金额
                      ##   $User = M("User");
                        
                     ##    $sjUserID = $User->where("id=".$UserID)->getField("SjUserID");
                        
                      ##   if($sjUserID){
                            
                          ##    $Paycost = M("Paycost");
       
                          ##    $sjfl = $Paycost->where("UserID=".$sjUserID)->getField("wy");
                        
                           ##   $fl = $Paycost->where("UserID=".$UserID)->getField("wy");
                        
                            ##  $tcfl = (1-$fl)-(1-$sjfl);
                             
                           ##   $tcmoney = $tcfl*$tranAmt;
                             
                          ##    $sjY_Money = $Money->where("UserID=".$sjUserID)->getField("Money");
                       
                          ##    $data["Money"] = $tcmoney + $sjY_Money;
                          ##    $Money->where("UserID=".$sjUserID)->save($data); //更新上级账户金额
                       ##  }
                                               
                       
        
                       ///////////////////////////////////////////////////////////////
                   
                   ///////////////////////////////////////////////////////////
                 ##   $Moneydb = M("Moneydb");
                 ##   $data["UserID"] = $UserID;
                  ##  $data["money"] = $OrderMoney;
                  ##  $data["ymoney"] =  $Y_Money;
                  ##  $data["gmoney"] = $Y_Money + $OrderMoney;
                  ##  $data["datetime"] = date("Y-m-d H:i:s");
                  ##  $data["lx"] = 1;
                  ##  $Moneydb->add($data);
                   //////////////////////////////////////////////////////////
                   
                 ##   $data["Zt"] = 1;   
                   
                 ##   $data["sjflmoney"] = $_factMoney - $_factMoney * $sjflmoney; //上家手续费
                 ##   $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                   
                }
        }else{
            $Order->where("TransID = '".$_TransID."'")->delete();
        }
                      // exit("----".$Sjt_Merchant_url."---------");
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
               ///////////////////////////////////////////////////////////////////////////////////////////////
           }
     }else{
            $Order = D("Order"); 
            $Sjt_Merchant_url = $Order->where("TransID = '".$BillNo."'")->getField("Sjt_Merchant_url");
            $this->Sjt_Return = 0;
            $this->Sjt_Error =  "9======".$SignMD5info."====".$md5sign;  //密钥错误
            $this->Sjt_Merchant_url = $Sjt_Merchant_url;
            $this->RunError();
     }
    
    
    
      }
       
      public function ReturnUrl(){
                             
          $Sjapi = M("Sjapi");
        $_Md5Key = $Sjapi->where("apiname='huicao'")->getField("key"); //密钥   
           //MD5私钥
    $MD5key = $_Md5Key;

    //订单号
    $BillNo = $this->_request("BillNo");
    //金额
    $Amount = $this->_request("Amount");
    //支付状态
    $Succeed = $this->_request("Succeed");
    //支付结果
    $Result = $this->_request("Result");
    //取得的MD5校验信息
    $SignMD5info = $this->_request("SignMD5info"); 
    //备注
    $Remark = $this->_request("Remark");



    //校验源字符串
  $md5src = $BillNo."&".$Amount."&".$Succeed."&".$MD5key;
  //MD5检验结果
    $md5sign = strtoupper(md5($md5src));
    
     if ($SignMD5info==$md5sign){  
           if ($Succeed!="88"){
                 $this->Sjt_Return = 0;
                $this->Sjt_Error = "";
                $Order = D("Order");    
                $this->Sjt_Merchant_url = $Order->where("TransID = '".$BillNo."'")->getField("Sjt_Merchant_url");
                $this->RunError();
           }else{
               ////////////////////////////////////////////////////////////////////////////////////////////////
               $Order = D("Order");
               
               $_TransID =  $BillNo;
               
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                
                //通知跳转页面
                $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url"); 
               // exit($Sjt_Merchant_url);
                //后台通知地址
                $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                
                $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                
                 $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
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
                
               // $_factMoney = $_factMoney / 100;
                
                
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
                 // $Money = D("Money");
                 //  $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                  // $baofu_Money = $Money->where("UserID=".$UserID)->getField("huicao");
                 //实际金额
                 //  $data["Money"] = $OrderMoney + $Y_Money;
                  // $data["huicao"] = $baofu_Money + $OrderMoney;
                 //  $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                   
                    $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                  // $OrderMoney =$OrderMoney; //实际金额
                   $data["Money"] = $OrderMoney + $Y_Money;
                   $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                   
                   ////////////////////////////////////////////////////////////////更新上级金额
                        $User = M("User");
                        
                        $sjUserID = $User->where("id=".$UserID)->getField("SjUserID");
                        
                        $tcmoney = 0;
                        
                        if($sjUserID){
                            
                             
                             
                             //////////////////////////////////////////////////////////////////////////////////////////////
                             
                             $Paycost = M("Paycost");
       
                             $sjfl = $Paycost->where("UserID=".$sjUserID)->getField("wy");
                        
                             $fl = $Paycost->where("UserID=".$UserID)->getField("wy");
                        
                             $tcfl = (1-$fl)-(1-$sjfl);
                             
                             $tcmoney = $tcfl*$tranAmt;
                             
                             $sjY_Money = $Money->where("UserID=".$sjUserID)->getField("Money");
                       
                             $data["Money"] = $tcmoney + $sjY_Money;
                             $Money->where("UserID=".$sjUserID)->save($data); //更新上级账户金额
                             
                             /////////////////////////////////////////////////////////////////////////////////////////////
                             
                             
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
                                               
                       
                  
                       ///////////////////////////////////////////////////////////////
                   ///////////////////////////////////////////////////////////
                   $Moneybd = M("Moneybd");
                   $data["UserID"] = $UserID;
                   $data["money"] = $OrderMoney;
                   $data["ymoney"] =  $baofu_Money;
                   $data["gmoney"] = $baofu_Money + $OrderMoney;
                   $data["datetime"] = date("Y-m-d H:i:s");
              
                   $data["lx"] = 1;
                   $result = $Moneybd->add($data);
                   
                   //////////////////////////////////////////////////////////
                   $data = array();
                   $data["Zt"] = 1;   
                   $data["TcMoney"] = $tcmoney;
                 
                   $Order->where("TransID='".$_TransID."'")->save($data); //将订单设置为成功            
                    
                }
        }else{
            if($Diaodangkg == 1){  //如果掉单，删除掉订单信息
                 $Order->where("TransID='".$_TransID."'")->delete();
              }
        }
                
                         ////////////////////////////////////////////////////////  
               $Ordertz = M("Ordertz");
               $ordertzlist = $Ordertz->where("Sjt_TransID = '".$_TransID."'")->select();
               if(!$ordertzlist){
                   $data["Sjt_MerchantID"] = $Sjt_MerchantID;
                   $data["Sjt_UserName"] = $Sjt_Username;
                   $data["Sjt_TransID"] = $_TransID;
                   $data["Sjt_Return"] = $_Result;
                   $data["Sjt_Error"] = $_resultDesc;
                  //$_factMoney = $_factMoney/100;
                   $_factMoney = number_format($_factMoney,3);
                   $data["Sjt_factMoney"] = $_factMoney;
                   $data["Sjt_SuccTime"] = $_SuccTime;
                   $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$_factMoney.$_SuccTime."2".$Sjt_Key);
                   $data["Sjt_Sign"] = $Sjt_Md5Sign;
                   $data["Sjt_urlname"] = $Sjt_Return_url;
                   $data["Sjt_BType"] = 2;
                   //////////////////////////////////////////////////////
                   //掉单设置
                   if($Diaodangkg == 1){
                       $data["Diaodang"] = 1;
                       $data["datetime"] = date("Y-m-d H:i:s");
                   }
                   
                   /////////////////////////////////////////////////////
                   $Ordertz->add($data);
               }
               ///////////////////////////////////////////////////////
               //返回状态
               if($Diaodangkg == 0 || ($Diaodangkg == 1 && $Diaodan_Type == 3)){
               
               $datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$_TransID."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$_factMoney."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
               $tjurl = $Sjt_Return_url."?".$datastr; 
               $contents = fopen($tjurl,"r"); 
               $contents=fread($contents,4096); 
               if($contents == "ok"){
                 $data["success"] = 1;
                 $Ordertz->where("Sjt_TransID = '".$_TransID."'")->save($data);
               }else{
                  // $data["Sjt_UserName"] = $contents;
                  // $Ordertz->where("Sjt_TransID = '".$this->_post("out_trade_no")."'")->save($data);
               }
          }
               ///////////////////////////////////////////////////////
              
                exit("ok");
               ///////////////////////////////////////////////////////////////////////////////////////////////
           }
     }else{
     exit("no_no");             
     }
    
    
    
      } 
      
      public function sqlexecute(){
            
          $Model = M(); 
       
          $Model->execute("insert into pay_sjapi(apiname,myname,payname) values('huicao','汇朝快捷','HuiCao');");
       
          $Model->execute("ALTER TABLE `pay_bankpay`  ADD COLUMN `huicao` varchar(100);"); 
         
          $Model->execute("update pay_bankpay set huicao = 'CMB' where Sjt = 'zsyh'");
          $Model->execute("update pay_bankpay set huicao = 'ICBC' where Sjt = 'gsyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'CCB' where Sjt = 'jsyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'SPDB' where Sjt = 'shpdfzyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'ABC' where Sjt = 'nyyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'CMBC' where Sjt = 'msyh'"); 
          $Model->execute("update pay_bankpay set huicao = '' where Sjt = 'szfzyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'CIB' where Sjt = 'xyyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'BOCOM' where Sjt = 'jtyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'CEB' where Sjt = 'gdyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'BOCSH' where Sjt = 'zgyh'");
          $Model->execute("update pay_bankpay set huicao = 'PAB' where Sjt = 'payh'"); 
          $Model->execute("update pay_bankpay set huicao = 'GDB' where Sjt = 'gfyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'CNCB' where Sjt = 'zxyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'PSBC' where Sjt = 'zgyzcxyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'BCCB' where Sjt = 'bjyh'"); 
          $Model->execute("update pay_bankpay set huicao = 'BOS' where Sjt = 'shyh'"); 
          $Model->execute("update pay_bankpay set huicao = '' where Sjt = ''"); 
          $Model->execute("update pay_bankpay set huicao = '' where Sjt = ''"); 
          $Model->execute("update pay_bankpay set huicao = '' where Sjt = ''"); 
          
          exit("ok");
            
      }
  }
?>
