<?php
  class YibaoAction extends PayAction{
      public function Post(){
           $this->PayName = "Yibao";
            $this->TradeDate = date("YmdHis");
            $this->Paymoneyfen = 100;
            $this->check();
            $this->Orderadd();
            
            
            $tjurl = "https://www.yeepay.com/app-merchant-proxy/node";
            
            $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_Yibao_MerChantUrl.html";      //商户通知地址
        
        $this->_Return_url= "http://".C("WEB_URL")."/Payapi_Yibao_ReturnUrl.html";   //用户通知地址
            
             $Sjapi = M("Sjapi");
             $this->_MerchantID = $Sjapi->where("apiname='".$this->PayName."'")->getField("shid"); //商户ID
             $this->_Md5Key = $Sjapi->where("apiname='".$this->PayName."'")->getField("key"); //密钥   
             $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
             ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
             #    商家设置用户购买商品的支付信息.
##易宝支付平台统一使用GBK/GB2312编码方式,参数如用到中文，请注意转码
# 业务类型
    # 支付请求，固定值"Buy" .    
    $p0_Cmd = "Buy";
    
    $p1_MerId = $this->_MerchantID;
        
    #    送货地址
    # 为"1": 需要用户将送货地址留在易宝支付系统;为"0": 不需要，默认为 "0".
    $p9_SAF = "0";
#    商户订单号,选填.
##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，易宝支付会自动生成随机的商户订单号.
$p2_Order                    =  $this->TransID;

#    支付金额,必填.
##单位:元，精确到分.
$p3_Amt                        = $this->sjt_OrderMoney / 100;

#    交易币种,固定值"CNY".
$p4_Cur                        = "CNY";

#    商品名称
##用于支付时显示在易宝支付网关左侧的订单产品信息.
$p5_Pid                        = "zy";

#    商品种类
$p6_Pcat                    = "zy";

#    商品描述
$p7_Pdesc                    = "zy";

#    商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
$p8_Url                        = $this->_Merchant_url;    

#    商户扩展信息
##商户可以任意填写1K 的字符串,支付成功时将原样返回.                                                
$pa_MP                        = "zy";

#    支付通道编码
##默认为""，到易宝支付网关.若不需显示易宝支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.            
$pd_FrpId                    = $this->Sjt_PayID;

#    应答机制
##默认为"1": 需要应答机制;
$pr_NeedResponse    = "1";

#调用签名函数生成签名串
$hmac = $this->getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);
             ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<form name='Form1' id="Form1" action='<?php echo $tjurl; ?>' method='post'>
<input type='hidden' name='p0_Cmd'                    value='<?php echo $p0_Cmd; ?>'>
<input type='hidden' name='p1_MerId'                value='<?php echo $p1_MerId; ?>'>
<input type='hidden' name='p2_Order'                value='<?php echo $p2_Order; ?>'>
<input type='hidden' name='p3_Amt'                    value='<?php echo $p3_Amt; ?>'>
<input type='hidden' name='p4_Cur'                    value='<?php echo $p4_Cur; ?>'>
<input type='hidden' name='p5_Pid'                    value='<?php echo $p5_Pid; ?>'>
<input type='hidden' name='p6_Pcat'                    value='<?php echo $p6_Pcat; ?>'>
<input type='hidden' name='p7_Pdesc'                value='<?php echo $p7_Pdesc; ?>'>
<input type='hidden' name='p8_Url'                    value='<?php echo $p8_Url; ?>'>
<input type='hidden' name='p9_SAF'                    value='<?php echo $p9_SAF; ?>'>
<input type='hidden' name='pa_MP'                        value='<?php echo $pa_MP; ?>'>
<input type='hidden' name='pd_FrpId'                value='<?php echo $pd_FrpId; ?>'>
<input type='hidden' name='pr_NeedResponse'    value='<?php echo $pr_NeedResponse; ?>'>
<input type='hidden' name='hmac'                        value='<?php echo $hmac; ?>'>
</form>
<?php    
 $this->Echots();          
      }
      
       public function MerChantUrl(){
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
#    解析返回参数.
$return = $this->getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#    判断返回签名是否正确（True/False）
$bRet = $this->CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#    以上代码和变量不需要修改.
         
#    校验码正确.
if($bRet){
    if($r1_Code=="1"){
        ############################################################################################################################################
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
    #    并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.                
        
        if($r9_BType=="1"){
           ##---------------------------------------------------------------------------------------------
            if($Diaodangkg == 0 || ($Diaodangkg == 1 && $Diaodan_Type == 3)){  
               // echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
               // echo "<input type=\"hidden\" name=\"Sjt_MerchantID\" value=\"".$Sjt_MerchantID."\">";
               // echo "<input type=\"hidden\" name=\"Sjt_Username\" value=\"".$Sjt_Username."\">";   
               // echo "<input type=\"hidden\" name=\"Sjt_TransID\" value=\"".$r6_Order."\">";   
               // $_Result = 1;
               // echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$_Result."\">";   
               // $_resultDesc = "00";
               // echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"".$_resultDesc."\">";   
               // echo "<input type=\"hidden\" name=\"Sjt_factMoney\" value=\"".$_factMoney."\">";   
               // $_SuccTime = date("YmdHis");    
               // echo "<input type=\"hidden\" name=\"Sjt_SuccTime\" value=\"".$_SuccTime."\">";
                ////////////////r9_BType/////////////////////
               // echo "<input type='hidden' name='Sjt_BType' value='1' />";
                ////////////////r9_BType////////////////////
              // $Userapiinformation = D("Userapiinformation");
             //  $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
              //  $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$r6_Order.$_Result.$_resultDesc.$_factMoney.$_SuccTime."1".$Sjt_Key);
              //  echo "<input type=\"hidden\" name=\"Sjt_Sign\" value=\"".$Sjt_Md5Sign."\">";  
              //  echo "</from>";
              //  echo "<script type=\"text/javascript\">";
              //  echo "document.Form1.submit();";
               // echo "</script>";
                
               // exit;
            //  }else{
                  ///////////////////////////////////////////////////////////////////////
                  //echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
               // echo "</from>";
               // echo "<script type=\"text/javascript\">";
               // echo "document.Form1.submit();";
               // echo "</script>";
                
               // exit;
                $Userapiinformation = D("Userapiinformation");
               $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                 $sings = "returncode=1&userid=".($UserID+10000)."&orderid=".$r6_Order."&keyvalue=".$Sjt_Key;
    
           $sings = md5($sings);
    
          
            //echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Return_url."\">";
               // echo "<input type=\"hidden\" name=\"returncode\" value=\"1\">";
               // echo "<input type=\"hidden\" name=\"userid\" value=\"".($UserID+10000)."\">";   
               // echo "<input type=\"hidden\" name=\"orderid\" value=\"".$r6_Order."\">";   
             
              /// echo "<input type=\"hidden\" name=\"money\" value=\"".$_factMoney."\">";   
            // echo "<input type=\"hidden\" name=\"sign\" value=\"".$sings."\">";   
            //    echo "</from>";
             //   echo "<script type=\"text/javascript\">";
              //  echo "document.Form1.submit();";
              //  echo "</script>";
                
          
    
           $tjurl = $Sjt_Merchant_url."?returncode=1&userid=".($UserID+10000)."&orderid=".$r6_Order."&money=".$_factMoney."&sign=".$sings."&ext=".$ext;
           
           $contents = file_get_contents($tjurl);
               //echo("1");
               if(strtolower($contents) == "ok"){
               //  $data["success"] = 1;
                // $Ordertz->where("Sjt_TransID = '".$r6_Order."'")->save($data);
                // echo "success";
                $this->Succeed($r6_Order,$_factMoney);
               //  exit("<script>widnow.location.href='http://".C("WEB_URL")."/Payapi_Guofubao_Succeed.html?TransID=".$r6_Order."&Money=".$_factMoney."</script>"); 
               }else{
                   echo "<br>".$tjurl;
               }
                  //////////////////////////////////////////////////////////////////////
              }
           ##---------------------------------------------------------------------------------------------
        }elseif($r9_BType=="2"){
            #如果需要应答机制则必须回写流,以success开头,大小写不敏感.
             ////////////////////////////////////////////////////////  
               $Ordertz = M("Ordertz");
               $ordertzlist = $Ordertz->where("Sjt_TransID = '".$r6_Order."'")->select();
                $_Result = 1;
               $_resultDesc = "00";
               $_SuccTime = date("YmdHis");  
               $Userapiinformation = D("Userapiinformation");
               $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
               $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$r6_Order.$_Result.$_resultDesc.$_factMoney.$_SuccTime."2".$Sjt_Key);
               if(!$ordertzlist){
                   $data["Sjt_MerchantID"] = $Sjt_MerchantID;
                   $data["Sjt_UserName"] = $Sjt_Username;
                   $data["Sjt_TransID"] = $r6_Order;
                   $data["Sjt_Return"] = $_Result;
                   $data["Sjt_Error"] = $_resultDesc;
                  //$_factMoney = $_factMoney/100;
                   $_factMoney = number_format($_factMoney,3);
                   $data["Sjt_factMoney"] = $_factMoney;
                   $data["Sjt_SuccTime"] = $_SuccTime;
                  // $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$_factMoney.$_SuccTime."2".$Sjt_Key);
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
            ######################################################################################################################
           //  if($Diaodangkg == 0 || ($Diaodangkg == 1 && $Diaodan_Type == 3)){
              
               
              // $datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$r6_Order."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$_factMoney."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
             //  $tjurl = $Sjt_Return_url."?".$datastr; 
             //  $contents = fopen($tjurl,"r"); 
             //  $contents=fread($contents,4096); 
             //  if($contents == "ok"){
              //   $data["success"] = 1;
               //  $Ordertz->where("Sjt_TransID = '".$_TransID."'")->save($data);
             //  }else{
                  // $data["Sjt_UserName"] = $contents;
                  // $Ordertz->where("Sjt_TransID = '".$this->_post("out_trade_no")."'")->save($data);
            //   }
               
           $sings = "returncode=1&userid=".($UserID+10000)."&orderid=".$r6_Order."&keyvalue=".$Sjt_Key;
    
           $sings = md5($sings);
    
           $tjurl = $Sjt_Merchant_url."?returncode=1&userid=".($UserID+10000)."&orderid=".$r6_Order."&money=".$_factMoney."&sign=".$sings."&ext=".$ext;
           
           $contents = file_get_contents($tjurl);
               echo("2");
               if(strtolower($contents) == "ok"){
                 $data["success"] = 1;
                 $Ordertz->where("Sjt_TransID = '".$r6_Order."'")->save($data);
                
               //  exit("<script>widnow.location.href='http://".C("WEB_URL")."/Payapi_Guofubao_Succeed.html?TransID=".$r6_Order."&Money=".$_factMoney."</script>"); 
               }else{
                   //$data["success"] = 1;
                   //$Ordertz->where("Sjt_TransID = '".$r6_Order."'")->save($data);
                   //echo "success";
               }
               
                echo "success";
            #####################################################################################################################
                      
       // }
    }
    
}else{
    echo "交易信息被篡改";
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////           
       }
       }
       public function Succeed($TransID,$Money){
         // $TransID = $this->_request("TransID");
          //$Money = $this->_request("Money");
          echo "53a平台提示您：<span style='color:#f00; font-szie:20px;'>充值成功！</span><br>";
          echo "订单号：".$TransID."<br>";
          echo "订单金额：".$Money;
      }
      public function ReturnUrl(){
            
       }
       
       
       #签名函数生成签名串
function getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse)
{
   $p0_Cmd = "Buy";
   $p9_SAF = "0";
    //include 'merchantProperties.php';
    
     $Sjapi = M("Sjapi");
     $p1_MerId = $Sjapi->where("apiname='yibao'")->getField("shid"); //商户ID
     $merchantKey = $Sjapi->where("apiname='yibao'")->getField("key"); //密钥
     $logName    = "YeePay_HTML.log";   
        
    #进行签名处理，一定按照文档中标明的签名顺序进行
  $sbOld = "";
  #加入业务类型
  $sbOld = $sbOld.$p0_Cmd;
  #加入商户编号
  $sbOld = $sbOld.$p1_MerId;
  #加入商户订单号
  $sbOld = $sbOld.$p2_Order;     
  #加入支付金额
  $sbOld = $sbOld.$p3_Amt;
  #加入交易币种
  $sbOld = $sbOld.$p4_Cur;
  #加入商品名称
  $sbOld = $sbOld.$p5_Pid;
  #加入商品分类
  $sbOld = $sbOld.$p6_Pcat;
  #加入商品描述
  $sbOld = $sbOld.$p7_Pdesc;
  #加入商户接收支付成功数据的地址
  $sbOld = $sbOld.$p8_Url;
  #加入送货地址标识
  $sbOld = $sbOld.$p9_SAF;
  #加入商户扩展信息
  $sbOld = $sbOld.$pa_MP;
  #加入支付通道编码
  $sbOld = $sbOld.$pd_FrpId;
  #加入是否需要应答机制
  $sbOld = $sbOld.$pr_NeedResponse;
    $this->logstr($p2_Order,$sbOld,$this->HmacMd5($sbOld,$merchantKey));
  return $this->HmacMd5($sbOld,$merchantKey);
  
} 

function getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType)
{
  
    //include 'merchantProperties.php';
  
     $Sjapi = M("Sjapi");
     $p1_MerId = $Sjapi->where("apiname='yibao'")->getField("shid"); //商户ID
     $merchantKey = $Sjapi->where("apiname='yibao'")->getField("key"); //密钥
     $logName    = "YeePay_HTML.log";   
  
    #取得加密前的字符串
    $sbOld = "";
    #加入商家ID
    $sbOld = $sbOld.$p1_MerId;
    #加入消息类型
    $sbOld = $sbOld.$r0_Cmd;
    #加入业务返回码
    $sbOld = $sbOld.$r1_Code;
    #加入交易ID
    $sbOld = $sbOld.$r2_TrxId;
    #加入交易金额
    $sbOld = $sbOld.$r3_Amt;
    #加入货币单位
    $sbOld = $sbOld.$r4_Cur;
    #加入产品Id
    $sbOld = $sbOld.$r5_Pid;
    #加入订单ID
    $sbOld = $sbOld.$r6_Order;
    #加入用户ID
    $sbOld = $sbOld.$r7_Uid;
    #加入商家扩展信息
    $sbOld = $sbOld.$r8_MP;
    #加入交易结果返回类型
    $sbOld = $sbOld.$r9_BType;

    $this->logstr($r6_Order,$sbOld,$this->HmacMd5($sbOld,$merchantKey));
    return $this->HmacMd5($sbOld,$merchantKey);

}


#    取得返回串中的所有参数
function getCallBackValue(&$r0_Cmd,&$r1_Code,&$r2_TrxId,&$r3_Amt,&$r4_Cur,&$r5_Pid,&$r6_Order,&$r7_Uid,&$r8_MP,&$r9_BType,&$hmac)
{  
    $r0_Cmd        = $_REQUEST['r0_Cmd'];
    $r1_Code    = $_REQUEST['r1_Code'];
    $r2_TrxId    = $_REQUEST['r2_TrxId'];
    $r3_Amt        = $_REQUEST['r3_Amt'];
    $r4_Cur        = $_REQUEST['r4_Cur'];
    $r5_Pid        = $_REQUEST['r5_Pid'];
    $r6_Order    = $_REQUEST['r6_Order'];
    $r7_Uid        = $_REQUEST['r7_Uid'];
    $r8_MP        = $_REQUEST['r8_MP'];
    $r9_BType    = $_REQUEST['r9_BType']; 
    $hmac            = $_REQUEST['hmac'];
    
    return null;
}

function CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac)
{
    if($hmac==$this->getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType))
        return true;
    else
        return false;
}
        
  
function HmacMd5($data,$key)
{
// RFC 2104 HMAC implementation for php.
// Creates an md5 HMAC.
// Eliminates the need to install mhash to compute a HMAC
// Hacked by Lance Rushing(NOTE: Hacked means written)

//需要配置环境支持iconv，否则中文参数不能正常处理
$key = iconv("GB2312","UTF-8",$key);
$data = iconv("GB2312","UTF-8",$data);

$b = 64; // byte length for md5
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

function logstr($orderid,$str,$hmac)
{
//include 'merchantProperties.php';

 $Sjapi = M("Sjapi");
     $p1_MerId = $Sjapi->where("apiname='yibao'")->getField("shid"); //商户ID
     $merchantKey = $Sjapi->where("apiname='yibao'")->getField("key"); //密钥
     $logName    = "YeePay_HTML.log";   
     
$james=fopen($logName,"a+");
fwrite($james,"\r\n".date("Y-m-d H:i:s")."|orderid[".$orderid."]|str[".$str."]|hmac[".$hmac."]");
fclose($james);
}
  }
?>
