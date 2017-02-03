<?php
     class XinShengAction extends PayAction{
         
         
         public function Post(){
             
            $this->PayName = "XinSheng";
            $this->TradeDate = date("Y-m-d H:i:s");
            $this->Paymoneyfen = 100;
            $this->check();
            $this->Orderadd();
            
            $tjurl = "https://www.hnapay.com/website/pay.htm";
            
           // $tjurl = "http://qaapp.hnapay.com/website/pay.htm";
            
             $this->_Merchant_url = "http://".C("WEB_URL")."/Payapi_XinSheng_MerChantUrl.html";      //商户通知地址
        
             $this->_Return_url = "http://".C("WEB_URL")."/Payapi_XinSheng_ReturnUrl.html";   //用户通知地址
            ////////////////////////////////////////////////
             $Sjapi = M("Sjapi");
             $this->_MerchantID = $Sjapi->where("apiname='".$this->PayName."'")->getField("shid"); //商户ID
             $this->_Md5Key = $Sjapi->where("apiname='".$this->PayName."'")->getField("key"); //密钥   
             $zhanghu = $Sjapi->where("apiname='".$this->PayName."'")->getField("zhanghu");
             $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            ///////////////////////////////////////////////
            $version = "2.6";    //版本
            
            $serialID = "qdsgpay".date("YmdHis");   //订请求序列号号
            
            $submitTime = date("YmdHis");//订单提交时间
            
            $failureTime = "";//失效时间，可空
            
            $customerIP = "";//IP地址，可空
            
            $orderDetails = $this->TransID.",".$this->sjt_OrderMoney.",sxipay,phone,1";//订单明细信息
            
            
            $totalAmount = $this->sjt_OrderMoney;//订单总金额
            
            $type = "1000";//交易类型1000: 即时支付（默认）
            
            $buyerMarked = "18159507976";//付款方新生账户号
            
            //$payType = "ACCT_RMB,BANK_B2C,LARGE_CREDIT_CARD";//付款方支付方式
            
            $payType = "BANK_B2C";//付款方支付方式
            
            $orgCode = $this->Sjt_PayID;//目标资金机构代码(网银编码)
            
            $currencyCode = "1";//交易币种(默认 1  人民币)可空
            
            $directFlag = "1";//是否直连0非直连 1直连 可空
            
            $borrowingMarked = "0";//资金来源借贷标识0无特殊要求，默认
            
            $couponFlag = "";//优惠券标识 可空
            
            $platformID = "";//平台商ID可空
            
            $returnUrl = $this->_Merchant_url;//跳转返回地址
            
            $noticeUrl = $this->_Return_url;//异步返回地址
            
            $partnerID = $this->_MerchantID;//商户ID
            
            $remark = "sxipay";//扩展字段
            
            $charset = "1";//编码方式
            
            $signType = "2";//签名类型
            
            $pkey  = $this->_Md5Key;
            
            $signMsg = "version=".$version."&serialID=".$serialID."&submitTime=".$submitTime."&failureTime=".$failureTime."&customerIP=".$customerIP."&orderDetails=".$orderDetails."&totalAmount=".$totalAmount."&type=".$type."&buyerMarked=".$buyerMarked."&payType=".$payType."&orgCode=".$orgCode."&currencyCode=".$currencyCode."&directFlag=".$directFlag."&borrowingMarked=".$borrowingMarked."&couponFlag=".$couponFlag."&platformID=".$platformID."&returnUrl=".$returnUrl."&noticeUrl=".$noticeUrl."&partnerID=".$partnerID."&remark=".$remark."&charset=".$charset."&signType=".$signType;
            
            $signMsg = $signMsg."&pkey=".$pkey;
            
            $signMsg =  md5($signMsg);

?>
<form action="<?php echo $tjurl ?>" method="post" name="Form1" id="Form1">
	<input type="hidden" name="version"  value="<?php echo $version; ?>">	
	<input type="hidden" name="serialID"  value="<?php echo $serialID; ?>">	
	<input type="hidden" name="submitTime"  value="<?php echo $submitTime; ?>">
	<input type="hidden" name="failureTime"  value="<?php echo $failureTime; ?>"> 
	<input type="hidden" name="customerIP"  value="<?php echo $customerIP; ?>">
	<input type="hidden" name="orderDetails"  value="<?php echo $orderDetails; ?>">
	<input type="hidden" name="totalAmount"  value="<?php echo $totalAmount; ?>">
	<input type="hidden" name="type"  value="<?php echo $type; ?>">
	<input type="hidden" name="buyerMarked"  value="<?php echo $buyerMarked; ?>">
	<input type="hidden" name="payType"  value="<?php echo $payType; ?>">
	<input type="hidden" name="orgCode"  value="<?php echo $orgCode; ?>">
	<input type="hidden" name="currencyCode"  value="<?php echo $currencyCode; ?>">
	<input type="hidden" name="directFlag"  value="<?php echo $directFlag; ?>">
	<input type="hidden" name="borrowingMarked"  value="<?php echo $borrowingMarked; ?>">
	<input type="hidden" name="couponFlag"  value="<?php echo $couponFlag; ?>">
	<input type="hidden" name="platformID"  value="<?php echo $platformID; ?>">
	<input type="hidden" name="returnUrl"  value="<?php echo $returnUrl; ?>">
	<input type="hidden" name="noticeUrl"  value="<?php echo $noticeUrl; ?>">
	<input type="hidden" name="partnerID"  value="<?php echo $partnerID; ?>">
	<input type="hidden" name="remark"  value="<?php echo $remark; ?>">
	<input type="hidden" name="charset"  value="<?php echo $charset; ?>">
	<input type="hidden" name="signType"  value="<?php echo $signType; ?>">
	<input type="hidden" name="signMsg"   value="<?php echo $signMsg; ?>">
	<input type="submit" value="loading......">
</form>
<?php            
$this->Echots();
         }
         
       
       public function MerChantUrl(){
       	
	       	$orderID = $_REQUEST["orderID"];
	       	$resultCode = $_REQUEST["resultCode"];
	       	$stateCode = $_REQUEST["stateCode"];
	       	$orderAmount = $_REQUEST["orderAmount"];
	       	$payAmount = $_REQUEST["payAmount"];
	       	$acquiringTime = $_REQUEST["acquiringTime"];
	       	$completeTime = $_REQUEST["completeTime"];
	       	$orderNo = $_REQUEST["orderNo"];
	       	$partnerID = $_REQUEST["partnerID"];
	       	$remark = $_REQUEST["remark"];
	       	$charset = $_REQUEST["charset"];
	       	$signType = $_REQUEST["signType"];
	       	$signMsg = $_REQUEST["signMsg"];
	       		
	       	$src = "orderID=".$orderID
	       	."&resultCode=".$resultCode
	       	."&stateCode=".$stateCode
	       	."&orderAmount=".$orderAmount
	       	."&payAmount=".$payAmount
	       	."&acquiringTime=".$acquiringTime
	       	."&completeTime=".$completeTime
	       	."&orderNo=".$orderNo
	       	."&partnerID=".$partnerID
	       	."&remark=".$remark
	       	."&charset=".$charset
	       	."&signType=".$signType;
	       	
	      
       		$Sjapi = M("Sjapi");
       		$pkey = $Sjapi->where("apiname='XinSheng'")->getField("key"); //密钥
       		$src = $src."&pkey=".$pkey;
       		$ret2 = md5($src);
       		
       		if($ret2 == $signMsg){
       			if($stateCode == 2){
       				$this->TongdaoManage($orderID,0);
       			}
       			
       		}
	       	 
       	
       	
       }
      
   
      public function ReturnUrl(){
	      	
      	$orderID = $_REQUEST["orderID"];
      	$resultCode = $_REQUEST["resultCode"];
      	$stateCode = $_REQUEST["stateCode"];
      	$orderAmount = $_REQUEST["orderAmount"];
      	$payAmount = $_REQUEST["payAmount"];
      	$acquiringTime = $_REQUEST["acquiringTime"];
      	$completeTime = $_REQUEST["completeTime"];
      	$orderNo = $_REQUEST["orderNo"];
      	$partnerID = $_REQUEST["partnerID"];
      	$remark = $_REQUEST["remark"];
      	$charset = $_REQUEST["charset"];
      	$signType = $_REQUEST["signType"];
      	$signMsg = $_REQUEST["signMsg"];
      	
      	$src = "orderID=".$orderID
      	."&resultCode=".$resultCode
      	."&stateCode=".$stateCode
      	."&orderAmount=".$orderAmount
      	."&payAmount=".$payAmount
      	."&acquiringTime=".$acquiringTime
      	."&completeTime=".$completeTime
      	."&orderNo=".$orderNo
      	."&partnerID=".$partnerID
      	."&remark=".$remark
      	."&charset=".$charset
      	."&signType=".$signType;
      	 
      	 
      	$Sjapi = M("Sjapi");
      	$pkey = $Sjapi->where("apiname='XinSheng'")->getField("key"); //密钥
      	$src = $src."&pkey=".$pkey;
      	$ret2 = md5($src);
      	 
      	//file_put_contents("zyzyzzy.txt",$ret2."---------".$signMsg."\n", FILE_APPEND);
      	
      	if($ret2 == $signMsg){
      		if($stateCode == 2){
      			$this->TongdaoManage($orderID);
      			exit("200");
      		}
      	
      	}
      }

      public function sqlexecute(){
      
      	$Model = M();
      	 
      	$Model->execute("insert into pay_sjapi(apiname,myname,payname) values('xinsheng','新生支付','XinSheng');");
      	 
      	$Model->execute("ALTER TABLE `pay_bankpay`  ADD COLUMN `xinsheng` varchar(100);");
      	 
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