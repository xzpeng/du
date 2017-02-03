<?php
  class HualianAction extends PayAction{
        public function Post(){
            
            $this->PayName = "Hualian";
            $this->TradeDate = date("YmdHis");
            //exit($this->TradeDate);
            $this->Paymoneyfen = 1;
            $this->check();
            $this->Orderadd();
                    
                    
            $tjurl = "http://brand.hlhpay.com/GateWay/Bank.aspx";
                    
            $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_Hualian_MerChantUrl.html";      //商户通知地址
                
            $this->_Return_url= "http://".C("WEB_URL")."/Payapi_Hualian_ReturnUrl.html";   //用户通知地址
                    
            $Sjapi = M("Sjapi");
            $this->_MerchantID = $Sjapi->where("apiname='hualian'")->getField("shid"); //商户ID
            $this->_Md5Key = $Sjapi->where("apiname='hualian'")->getField("key"); //密钥   
            $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            
               #    商家设置用户购买商品的支付信息.
##华联支付平台统一使用GBK/GB2312编码方式,参数如用到中文，请注意转码
$p1_MerId =     $this->_MerchantID;
#    商户订单号,选填.
##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，华联支付会自动生成随机的商户订单号.
$p2_Order                    = $this->TransID;

#    支付金额,必填.
##单位:元，精确到分.
$p3_Amt                        = $this->sjt_OrderMoney;

#    交易币种,固定值"CNY".
$p4_Cur                        = "CNY";

#    商品名称
##用于支付时显示在华联支付网关左侧的订单产品信息.
$p5_Pid                        = "payment";

#    商品种类
$p6_Pcat                    = "payment";

#    商品描述
$p7_Pdesc                    = "payment";

#    商户接收支付成功数据的地址,支付成功后华联支付会向该地址发送两次成功通知.
$p8_Url                        = $this->_Merchant_url;    

#    商户扩展信息
##商户可以任意填写1K 的字符串,支付成功时将原样返回.                                                
$pa_MP                        = "payment";

#    支付通道编码
##默认为""，到华联支付网关.若不需显示华联支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.            
//$pd_FrpId                    = $this->Sjt_PayID;
$pd_FrpId                    = "1";

#    应答机制
##默认为"1": 需要应答机制;
$pr_NeedResponse    = "1";

# 业务类型
    # 支付请求，固定值"Buy" .    
    $p0_Cmd = "Buy";
        
    #    送货地址
    # 为"1": 需要用户将送货地址留在华联支付系统;为"0": 不需要，默认为 "0".
    $p9_SAF = "0";

 /////////////////////////////////////////////////////////////////////////////////////////////////
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
  $hmac =  $this->HmacMd5($sbOld,$this->_Md5Key);
 /////////////////////////////////////////////////////////////////////////////////////////////////   
    
#调用签名函数生成签名串

?>
<form name='Form1' action='<?php echo $tjurl; ?>' method='post'>
<input type='hidden' name='p0_Cmd'                    value='<?php echo $p0_Cmd; ?>'>
<input type='hidden' name='p1_MerId'                value='<?php echo  $p1_MerId; ?>'>
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
          #    解析返回参数.
       $return = $this->getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
       
       #    判断返回签名是否正确（True/False）
      $bRet = $this->CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#    以上代码和变量不需要修改.
          #    校验码正确.
        if($bRet){
            if($r1_Code=="1"){
                
            #    需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
            #    并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.                
               /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
               $_TransID =  $r6_Order;
               $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                
                //通知跳转页面
$Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url"); 
                //后台通知地址
                $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
              $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
              $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                $total_fee = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                
                 $trademoney = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");  //实际金额
                
                $Userapiinformation = D("Userapiinformation");
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                $Sjfl = M("Sjfl");
                $sjflmoney = $Sjfl->where("jkname='hualian'")->getField("wy"); //上家费率
                if($sjflmoney == 0){
                     $sjflmoney = 1;
                  }
                
                
               ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
                if($r9_BType=="1"){
                     /**************************************************************************************************************************************************************/
                      if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                 $Money = D("Money");
                 $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                 $data["Money"] = $total_fee + $Y_Money;
                 $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                 
                    ///////////////////////////////////////////////////////////
                   $Moneydb = M("Moneydb");
                   $data["UserID"] = $UserID;
                   $data["money"] = $total_fee;
                   $data["ymoney"] =  $Y_Money;
                   $data["gmoney"] = $Y_Money + $total_fee;
                   $data["datetime"] = date("Y-m-d H:i:s");
                   $data["lx"] = 1;
                   $Moneydb->add($data);
                   //////////////////////////////////////////////////////////
                   
                   
                   //////////////////////////////////////////////////////////
                 $data["Zt"] = 1;   
                 //$data["TradeDate"] = date("Y-m-d h:i:s");  
                 //$data["OrderMoney"] = $total_fee;
                 $data["sjflmoney"] = $trademoney - $trademoney * $sjflmoney; //上家手续费
                 $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功       
                 
                // exit("[".$total_fee."]<br>[".$parameter["out_trade_no"]."]");
                   
                }
                
               // exit($Sjt_Zt."<br>".$parameter["out_trade_no"]);
                //$_factMoney = $_factMoney/100;
                
                echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
                echo "<input type=\"hidden\" name=\"Sjt_MerchantID\" value=\"".$Sjt_MerchantID."\">";
                echo "<input type=\"hidden\" name=\"Sjt_Username\" value=\"".$Sjt_Username."\">";   
                echo "<input type=\"hidden\" name=\"Sjt_TransID\" value=\"".$parameter["out_trade_no"]."\">";   
                echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$parameter["is_success"]."\">";   
                echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"01\">";   
                echo "<input type=\"hidden\" name=\"Sjt_factMoney\" value=\"".$this->_get("total_fee")."\">"; 
                $_SuccTime = date("Ymdhis");      
                echo "<input type=\"hidden\" name=\"Sjt_SuccTime\" value=\"".$_SuccTime."\">";
                 ////////////////r9_BType/////////////////////
                echo "<input type='hidden' name='Sjt_BType' value='1' />";
                ////////////////r9_BType////////////////////
                
                $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$parameter["out_trade_no"].$parameter["is_success"]."01".$this->_get("total_fee").$_SuccTime."1".$Sjt_Key);
                echo "<input type=\"hidden\" name=\"Sjt_Sign\" value=\"".$Sjt_Md5Sign."\">";  
                echo "</from>";
                echo "<script type=\"text/javascript\">";
                echo "document.Form1.submit();";
                echo "</script>";
                
                exit;
                  
                     /*************************************************************************************************************************************************************/
                }elseif($r9_BType=="2"){
                    #如果需要应答机制则必须回写流,以success开头,大小写不敏感.
                       if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                 $Money = D("Money");
                 $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                 $data["Money"] = $total_fee + $Y_Money;
                 $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                 
                    ///////////////////////////////////////////////////////////
                   $Moneydb = M("Moneydb");
                   $data["UserID"] = $UserID;
                   $data["money"] = $total_fee;
                   $data["ymoney"] =  $Y_Money;
                   $data["gmoney"] = $Y_Money + $total_fee;
                   $data["datetime"] = date("Y-m-d H:i:s");
                   $data["lx"] = 1;
                   $Moneydb->add($data);
                   //////////////////////////////////////////////////////////
                   
                   
                   //////////////////////////////////////////////////////////
                 $data["Zt"] = 1;   
                 //$data["TradeDate"] = date("Y-m-d h:i:s");  
                 //$data["OrderMoney"] = $total_fee;
                 $data["sjflmoney"] = $trademoney - $trademoney * $sjflmoney; //上家手续费
                 $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功       
                 
                // exit("[".$total_fee."]<br>[".$parameter["out_trade_no"]."]");
                   
                }
                
                  ////////////////////////////////////////////////////////  
               // $datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$_TransID."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$_factMoney."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
              // $tjurl = $Sjt_Return_url."?".$datastr; 
              // $contents = fopen($tjurl,"r"); 
              // $contents=fread($contents,4096); 
                    echo "success";
                   // echo "<br />交易成功";
                   // echo  "<br />在线支付服务器返回";                   
                }
            }
            
        }else{
            echo "交易信息被篡改";
        }

      }   
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
    
    public function HmacMd5($data,$key)
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

     
        public function getCallBackValue(&$r0_Cmd,&$r1_Code,&$r2_TrxId,&$r3_Amt,&$r4_Cur,&$r5_Pid,&$r6_Order,&$r7_Uid,&$r8_MP,&$r9_BType,&$hmac)
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
        
        public function CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac)
            {
                if($hmac==$this->getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType))
                    return true;
                else
                    return false;
            }
            
         public function getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType)
            {
              
                $Sjapi = M("Sjapi");
                 $p1_MerId = $Sjapi->where("apiname='hualian'")->getField("shid"); //商户ID
                 $merchantKey = $Sjapi->where("apiname='hualian'")->getField("key"); //密钥   
              
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

                return $this->HmacMd5($sbOld,$merchantKey);

            }   
    
        
  }
?>
