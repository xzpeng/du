<?php
  class KuaiqianAction extends Action{
      
      private $Sjt_Return = 1;   //返回状态 1为正常，0为失败 
      private $Sjt_Error = "01";   //错误编号
      private $Sjt_MerchantID;     //盛捷通商户号
      private $Sjt_PayID;      //盛捷通商户支付渠道
      private $Sjt_TransID;    //盛捷通商户流水号
      Private $Sjt_ProductName;  //商品名称
      private $Sjt_OrderMoney;   //盛捷通商户订单金额
      private $Sjt_Key;          //盛捷通商户密钥
      private $Sjt_Merchant_url = "";    //盛捷通商户通知地址
      private $Sjt_Return_url = "";      //盛捷通商户底层（后台）通知地址
      public function Post(){
         header("Content-Type:text/html; charset=utf-8");
        
       $this->Sjt_Merchant_url = $this->_post("Sjt_Merchant_url");   //盛捷通商户通知地址   
       if($this->Sjt_Merchant_url == NUll || $this->Sjt_Merchant_url == ""){
           $this->Sjt_Return = 0;
           $this->Sjt_Error = 7;
           $this->ReturnUrl();
       } 
        
        
        $this->Sjt_MerchantID = $this->_post("Sjt_MerchantID");   //获取盛捷通商户号
        if($this->Sjt_MerchantID == NULL || $this->Sjt_MerchantID == ""){  //判断盛捷通商户号是否存在
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 1;
            $this->RunError();
        }
        
        $User = D("User");
        $Sjt_Name = $User->where("id=".(intval($this->Sjt_MerchantID)-10000))->getField("UserName");   //获取用户名
        if(!$Sjt_Name){
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 2;
            $this->ReturnUrl();
        }
        
        
        $this->Sjt_PayID = $this->_post("Sjt_PayID");   //获取盛捷通商户提交的支付渠道
        if($this->Sjt_PayID == NUll || $this->Sjt_PayID == ""){  //判断盛捷通商户提交的支付渠道字段是否存在
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 3;
            $this->RunError();
        }
        
        $Bankpay = M("Bankpay");
        $this->Sjt_PayID = $Bankpay->where("id=".$this->Sjt_PayID)->getField("kuaiqian");
        if(!$this->Sjt_PayID){
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 4;
            $this->RunError();
        }
        
        $this->Sjt_OrderMoney = $this->_post("Sjt_OrderMoney");  //获取盛捷通商户提交的订单金额
        if($this->Sjt_OrderMoney == NUll || $this->Sjt_OrderMoney == ""){  //判断盛捷通商户提交的订单金额字段是否存在
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 5;
            $this->RunError();
        }
        
        $Userapiinformation = D("Userapiinformation");
        $WebsiteUrl = $Userapiinformation->where("UserID=".(intval($this->Sjt_MerchantID)-10000))->getField("WebsiteUrl");   //获取用户设置的网址
        $yuming = $_SERVER["HTTP_REFERER"];  //获取提交的网址的域名
        
       // exit($yuming);

       
       if(strstr($yuming,$WebsiteUrl) == false){    //判断提交的网址域名与用户设置的域名相同
             $this->Sjt_Return = 0;
             $this->Sjt_Error = 6;
             $this->RunError();
       } 
         
         
        $this->Sjt_Return_url = $this->_post("Sjt_Return_url");      //盛捷通商户底层（后台）通知地址
        if($this->Sjt_Return_url == Null || $this->Sjt_Return_url == ""){
           $this->Sjt_Return = 0;
           $this->Sjt_Error = 8;
           $this->ReturnUrl();
       }  
        
        $this->Sjt_Key = $Userapiinformation->where("UserID=".(intval($this->Sjt_MerchantID)-10000))->getField("Key");   //获取用户的密钥
        
        if($this->Sjt_Key != $this->_post("Sjt_Key")){    //判断盛捷通商户提交的密钥是否正确
            $this->Sjt_Return = 0;
            $this->Sjt_Error = 9;
            $this->RunError();
        }
        
        
        $Sjapi = M("Sjapi");
        $_MerchantID = $Sjapi->where("apiname='kuaiqian'")->getField("shid"); //商户ID
        $_Md5Key = $Sjapi->where("apiname='kuaiqian'")->getField("key"); //密钥   
        
        $Order = M("Order");
        $id_id = $Order->order("id desc")->limit(1)->getField("id");
        $this->Sjt_TransID = $this->Sjt_MerchantID.date("Ymd").(1000000000+$id_id);
        $_TransID=$this->Sjt_TransID;//流水号
        
        $_PayID=$this->Sjt_PayID;//支付方式
        $_TradeDate = date("Ymdhis");//交易时间  date("Ymdhis");
        $_OrderMoney=$this->Sjt_OrderMoney*100;//订单金额
        $_ProductName=iconv('GB2312', 'UTF-8', $this->_post("Sjt_ProductName"));//产品名称
        
        $_Username=$Sjt_Name;//盛捷通支付用户名
        
        $_AdditionalInfo=iconv('GB2312', 'UTF-8', $this->_post("Sjt_AdditionalInfo"));//订单附加消息
        
        $_Merchant_url= "http://".C("WEB_URL")."/Payapi_BaoFu_MerChantUrl.html";      //商户通知地址
        
        $_Return_url= "http://".C("WEB_URL")."/Payapi_BaoFu_ReturnUrl.html";   //用户通知地址
        
        $_NoticeType= 1;//通知方式  服务器通知和页面通知。支付成功后，自动重定向到“通知商户地址”
        
        $_Md5Sign=md5($_MerchantID.$_PayID.$_TradeDate.$_TransID.$_OrderMoney.$_Merchant_url.$_Return_url.$_NoticeType.$_Md5Key);
        
        $Order = D("Order");
        $data["UserID"] = intval($this->Sjt_MerchantID)-10000;      //商户编号
        $data["TransID"] = $this->Sjt_TransID;          //订单号
        $data["TradeDate"] = date("Y-m-d h:i:s");    //订单时间
        $data["OrderMoney"] = $this->Sjt_OrderMoney;     //订单金额
        $data["ProductName"] = $_ProductName;     //商品名称
        $data["Username"] = iconv('GB2312', 'UTF-8', $this->_post("Sjt_UserName"));     //支付用户名
        $data["AdditionalInfo"] = $_AdditionalInfo;   //订单附加信息
        $data["Sjt_Merchant_url"] = $this->Sjt_Merchant_url;     //跳转地址
        $data["Sjt_Return_url"] = $this->Sjt_Return_url;    //通知地址
        
        $Order->add($data); 
        
        
        ///////////////////////////////////////////////////////////////////////////
        //人民币网关账号，该账号为11位人民币网关商户编号+01,该参数必填。
        $merchantAcctId = $_MerchantID;
        //编码方式，1代表 UTF-8; 2 代表 GBK; 3代表 GB2312 默认为1,该参数必填。
        $inputCharset = "1";
        //接收支付结果的页面地址，该参数一般置为空即可。
        $pageUrl = $_Merchant_url;
        //服务器接收支付结果的后台地址，该参数务必填写，不能为空。
        $bgUrl = $_Return_url;
        //网关版本，固定值：v2.0,该参数必填。
        $version =  "v2.0";
        //语言种类，1代表中文显示，2代表英文显示。默认为1,该参数必填。
        $language =  "1";
        //签名类型,该值为4，代表PKI加密方式,该参数必填。
        $signType =  "4";
        //支付人姓名,可以为空。
        $payerName= ""; 
        //支付人联系类型，1 代表电子邮件方式；2 代表手机联系方式。可以为空。
        $payerContactType =  "1";
        //支付人联系方式，与payerContactType设置对应，payerContactType为1，则填写邮箱地址；payerContactType为2，则填写手机号码。可以为空。
        $payerContact =  "328662397@qq.com";
        //商户订单号，以下采用时间来定义订单号，商户可以根据自己订单号的定义规则来定义该值，不能为空。
        $orderId = $this->Sjt_TransID;
        //订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试。该参数必填。
        $orderAmount = $_OrderMoney;
        //订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101，不能为空。
        $orderTime = date("YmdHis");
        //商品名称，可以为空。
        $productName= $_ProductName; 
        //商品数量，可以为空。
        $productNum = "";
        //商品代码，可以为空。
        $productId = "55558888";
        //商品描述，可以为空。
        $productDesc = "";
        //扩展字段1，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空。
        $ext1 = "";
        //扩展自段2，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空。
        $ext2 = "";
        //支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10，必填。
        $payType = "10";
        //银行代码，如果payType为00，该值可以为空；如果payType为10，该值必须填写，具体请参考银行列表。
        $bankId = $this->Sjt_PayID;
        //同一订单禁止重复提交标志，实物购物车填1，虚拟产品用0。1代表只能提交一次，0代表在支付不成功情况下可以再提交。可为空。
        $redoFlag = "";
        //快钱合作伙伴的帐户号，即商户编号，可为空。
        $pid = "";
    // signMsg 签名字符串 不可空，生成加密签名串
        //////////////////////////////////////////////////////////////////////////
    $kq_all_para=$this->kq_ck_null($inputCharset,'inputCharset');
    $kq_all_para.=$this->kq_ck_null($pageUrl,"pageUrl");
    $kq_all_para.=$this->kq_ck_null($bgUrl,'bgUrl');
    $kq_all_para.=$this->kq_ck_null($version,'version');
    $kq_all_para.=$this->kq_ck_null($language,'language');
    $kq_all_para.=$this->kq_ck_null($signType,'signType');
    $kq_all_para.=$this->kq_ck_null($merchantAcctId,'merchantAcctId');
    $kq_all_para.=$this->kq_ck_null($payerName,'payerName');
    $kq_all_para.=$this->kq_ck_null($payerContactType,'payerContactType');
    $kq_all_para.=$this->kq_ck_null($payerContact,'payerContact');
    $kq_all_para.=$this->kq_ck_null($orderId,'orderId');
    $kq_all_para.=$this->kq_ck_null($orderAmount,'orderAmount');
    $kq_all_para.=$this->kq_ck_null($orderTime,'orderTime');
    $kq_all_para.=$this->kq_ck_null($productName,'productName');
    $kq_all_para.=$this->kq_ck_null($productNum,'productNum');
    $kq_all_para.=$this->kq_ck_null($productId,'productId');
    $kq_all_para.=$this->kq_ck_null($productDesc,'productDesc');
    $kq_all_para.=$this->kq_ck_null($ext1,'ext1');
    $kq_all_para.=$this->kq_ck_null($ext2,'ext2');
    $kq_all_para.=$this->kq_ck_null($payType,'payType');
    $kq_all_para.=$this->kq_ck_null($bankId,'bankId');
    $kq_all_para.=$this->kq_ck_null($redoFlag,'redoFlag');
    $kq_all_para.=$this->kq_ck_null($pid,'pid');
    
    $kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);
    
    /////////////  RSA 签名计算 ///////// 开始 //
    $fp = fopen("99bill-rsa.pem", "r");
    $priv_key = fread($fp, 8192);
    fclose($fp);
    $pkeyid = openssl_get_privatekey($priv_key);

    // compute signature
    openssl_sign($kq_all_para, $signMsg, $pkeyid,OPENSSL_ALGO_SHA1);

    // free the key from memory
    openssl_free_key($pkeyid);

     $signMsg = base64_encode($signMsg);
    /////////////  RSA 签名计算 ///////// 结束 //
        
       echo '<form name="Form1" action="https://sandbox2.99bill.com/gateway/recvMerchantInfoAction.htm" method="post">';
       echo '<input type="text" name="inputCharset" value="'.$inputCharset.'" />'; 
       echo '<input type="text" name="pageUrl" value="'.$pageUrl.'" />';
       echo '<input type="text" name="bgUrl" value="'.$bgUrl.'" />'; 
       echo '<input type="text" name="version" value="'.$version.'" />';
       echo '<input type="text" name="language" value="'.$language.'" />'; 
       echo '<input type="text" name="signType" value="'.$signType.'" />';
       echo '<input type="text" name="signMsg" value="'.$kq_all_para.'" />'; 
       echo '<input type="text" name="merchantAcctId" value="'.$merchantAcctId.'" />';
       echo '<input type="text" name="payerName" value="'.$payerName.'" />'; 
       echo '<input type="text" name="payerContactType" value="'.$payerContactType.'" />';
       echo '<input type="text" name="payerContact" value="'.$payerContact.'" />'; 
       echo '<input type="text" name="orderId" value="'.$orderId.'" />';
       echo '<input type="text" name="orderAmount" value="'.$orderAmount.'" />'; 
       echo '<input type="text" name="orderTime" value="'.$orderTime.'" />';
       echo '<input type="text" name="productName" value="'.$productName.'" />'; 
       echo '<input type="text" name="productNum" value="'.$productNum.'" />';
       echo '<input type="text" name="productId" value="'.$productId.'" />'; 
       echo '<input type="text" name="productDesc" value="'.$productDesc.'" />';
       echo '<input type="text" name="ext1" value="'.$ext1.'" />';
       echo '<input type="text" name="ext2" value="'.$ext2.'" />';
       echo '<input type="text" name="payType" value="'.$payType.'" />';
       echo '<input type="text" name="bankId" value="'.$bankId.'" />';
       echo '<input type="text" name="redoFlag" value="'.$redoFlag.'" />';
       echo '<input type="text" name="pid" value="'.$pid.'" />';
       echo '<input type="submit" name="pid" value="提 交" />';
       echo '</form>';
       echo '<script type="text/javascript">';
       echo '//document.Form1.submit();';
       echo '</script>';
        
      }
      
      public function MerChantUrl(){
          
          
    //人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
    $kq_check_all_para=$this->kq_ck_null($_REQUEST[merchantAcctId],'merchantAcctId');
    //网关版本，固定值：v2.0,该值与提交时相同。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[version],'version');
    //语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[language],'language');
    //签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[signType],'signType');
    //支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[payType],'payType');
    //银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[bankId],'bankId');
    //商户订单号，,该值与提交时相同。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[orderId],'orderId');
    //订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[orderTime],'orderTime');
    //订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[orderAmount],'orderAmount');
    // 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[dealId],'dealId');
    //银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[bankDealId],'bankDealId');
    //快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[dealTime],'dealTime');
    //商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[payAmount],'payAmount');
    //费用，快钱收取商户的手续费，单位为分。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[fee],'fee');
    //扩展字段1，该值与提交时相同
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[ext1],'ext1');
    //扩展字段2，该值与提交时相同。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[ext2],'ext2');
    //处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[payResult],'payResult');
    //错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
    $kq_check_all_para.=$this->kq_ck_null($_REQUEST[errCode],'errCode');
    
    
    $trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
    $MAC=base64_decode($_REQUEST[signMsg]);

    $fp = fopen("99bill-rsa.pem", "r"); 
    $cert = fread($fp, 8192); 
    fclose($fp); 
    $pubkeyid = openssl_get_publickey($cert); 
    $ok = openssl_verify($trans_body, $MAC, $pubkeyid); 
    
    
    if ($ok == 1) { 
        switch($_REQUEST[payResult]){
                case '10':
                        //此处做商户逻辑处理
                  $rtnOK=1;
                        //以下是我们快钱设置的show页面，商户需要自己定义该页面。
                  $rtnUrl="http://219.233.173.50:8802/futao/rmb_demo/show.php?msg=success";
                        break;
                default:
                  $rtnOK=1;
                        //以下是我们快钱设置的show页面，商户需要自己定义该页面。
                  $rtnUrl="http://219.233.173.50:8802/futao/rmb_demo/show.php?msg=false";
                        break;    
        
        }

    }else{
        $rtnOK=1;
                        //以下是我们快钱设置的show页面，商户需要自己定义该页面。
        $rtnUrl="http://219.233.173.50:8802/futao/rmb_demo/show.php?msg=error";
                            
    }
    
    
          
      }
      
      
      public function ReturnUrl(){
          
      }
      
      
       private function RunError(){
        if($this->Sjt_Merchant_url == "" || $this->Sjt_Merchant_url == null){
            echo $this->Sjt_Error;
        }else{
            echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$this->Sjt_Merchant_url."\">";
            echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$this->Sjt_Return."\">";
            echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"".$this->Sjt_Error."\">";
            echo "</from>";
            echo "<script type=\"text/javascript\">";
            echo "document.Form1.submit();";
            echo "</script>";
        }
       
        exit;
    }
    
    private function kq_ck_null($kq_va,$kq_na){
        if($kq_va == ""){
            $kq_va="";
        }else{
            return $kq_va=$kq_na.'='.$kq_va.'&';
        }
    }

       
  }
?>
