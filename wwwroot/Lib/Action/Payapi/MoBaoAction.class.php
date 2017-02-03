<?php
     class MoBaoAction extends PayAction{
         
         
         public function Post(){
             
            $this->PayName = "MoBao";
            $this->TradeDate = date("Y-m-d H:i:s");
            $this->Paymoneyfen = 1;
            $this->check();
            $this->Orderadd();
            
          //  $tjurl = "https://www.hnapay.com/website/pay.htm";
            
           // $tjurl = "http://qaapp.hnapay.com/website/pay.htm";
            
             $this->_Merchant_url = "http://".C("WEB_URL")."/Payapi_MoBao_MerChantUrl.html";      //商户通知地址
        
             $this->_Return_url = "http://".C("WEB_URL")."/Payapi_XinSheng_ReturnUrl.html";   //用户通知地址
            ////////////////////////////////////////////////
             $Sjapi = M("Sjapi");
             $this->_MerchantID = $Sjapi->where("apiname='".$this->PayName."'")->getField("shid"); //商户ID
             $this->_Md5Key = $Sjapi->where("apiname='".$this->PayName."'")->getField("key"); //密钥   
             $zhanghu = $Sjapi->where("apiname='".$this->PayName."'")->getField("zhanghu");
             $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
             $zhanghu = "210001310004067";
            ///////////////////////////////////////////////
            Vendor('MoBao.MobaoPay','','.class.php');
            #======================================================================================
            // 请求数据赋值
            $data = "";
            // 商户APINMAE，WEB渠道一般支付
            $data['apiName'] = "WEB_PAY_B2C";
            // 商户API版本
            $data['apiVersion'] = "1.0.0.0";
            // 商户在Mo宝支付的平台号
            $data['platformID'] = $zhanghu;
            // Mo宝支付分配给商户的账号
            $data['merchNo'] = $zhanghu;
            // 商户通知地址
            $data['merchUrl'] = $this->_Merchant_url;
            // 银行代码，不传输此参数则跳转Mo宝收银台
            $data['bankCode'] = $this->Sjt_PayID;
            
            //商户订单号
            $data['orderNo'] =  $this->TransID;
            // 商户订单日期
            $data['tradeDate'] = date("Ymd");
            // 商户交易金额
            $amt = sprintf("%.2f", $this->sjt_OrderMoney);
            $data['amt'] = $amt;
            // 商户参数
            $data['merchParam'] = "xxx";
            // 商户交易摘要
            $data['tradeSummary'] = "xxx";
            
            $pfxFile = "./MoBaoCert/xurui.pfx";
            // 私钥密码
            $passwd = "xurui";
            // Mo宝支付系统公钥
            $pubFile = "./MoBaoCert/epay.crt";
            // Mo宝支付系统网关地址（正式环境）
            $mobaopay_gateway = "https://trade.mobaopay.com/cgi-bin/netpayment/pay_gate.cgi";
            // Mo宝支付系统网关地址（测试环境）
            //$mobaopay_gateway = "https://trade.mobaopay.uat/cgi-bin/netpayment/pay_gate.cgi";
            
            // 对含有中文的参数进行UTF-8编码
            // 将中文转换为UTF-8
            if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['merchUrl']))
            {
              $data['merchUrl'] = iconv("GBK","UTF-8", $data['merchUrl']);
            }
            
            if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['merchParam']))
            {

              $data['merchParam'] = iconv("GBK","UTF-8", $data['merchParam']);
            }

            if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['tradeSummary']))
            {
              $data['tradeSummary'] = iconv("GBK","UTF-8", $data['tradeSummary']);
            }
            
            // 初始化
            $cMbPay = new MbPay($pfxFile, $pubFile, $passwd, $mobaopay_gateway);
            // 准备待签名数据
            $str_to_sign = $cMbPay->prepareSign($data);
            // 数据签名
            $sign = $cMbPay->sign($str_to_sign);
            $data['signMsg'] = $sign;
            // 生成表单数据
            echo $cMbPay->buildForm($data, $mobaopay_gateway);
            #======================================================================================
           
         }
         
       
       public function MerChantUrl(){
       	
	       // 请求数据赋值
    $data = "";
    $data['apiName'] = $_REQUEST["apiName"];
    // 通知时间
    $data['notifyTime'] = $_REQUEST["notifyTime"];
    // 支付金额(单位元，显示用)
    $data['tradeAmt'] = $_REQUEST["tradeAmt"];
    // 商户号
    $data['merchNo'] = $_REQUEST["merchNo"];
    // 商户参数，支付平台返回商户上传的参数，可以为空
    $data['merchParam'] = $_REQUEST["merchParam"];
    // 商户订单号
    $data['orderNo'] = $_REQUEST["orderNo"];
    // 商户订单日期
    $data['tradeDate'] = $_REQUEST["tradeDate"];
    // Mo宝支付订单号
    $data['accNo'] = $_REQUEST["accNo"];
    // Mo宝支付账务日期
    $data['accDate'] = $_REQUEST["accDate"];
    // 订单状态，0-未支付，1-支付成功，2-失败，4-部分退款，5-退款，9-退款处理中
    $data['orderStatus'] = $_REQUEST["orderStatus"];
    // 签名数据
    $data['signMsg'] = $_REQUEST["signMsg"];

     $pfxFile = "./MoBaoCert/xurui.pfx";
            // 私钥密码
     $passwd = "xurui";
            // Mo宝支付系统公钥
     $pubFile = "./MoBaoCert/epay.crt";
            // Mo宝支付系统网关地址（正式环境）
    //print_r( $data);
    // 初始化
    $cMbPay = new MbPay($pfxFile, $pubFile, $passwd);
    // 准备准备验签数据
    $str_to_sign = $cMbPay->prepareSign($data);
    // 验证签名
    $resultVerify = $cMbPay->verify($str_to_sign, $data['signMsg']);
    //var_dump($data);
    if ($resultVerify) 
    {
        if ('1' == $_REQUEST["notifyType"]) {
            $this->TongdaoManage($data['orderNo']);
            echo "SUCCESS";
        }else{
            $this->TongdaoManage($data['orderNo'],0);
        }
        
    }
    else
    {
        // 签名验证失败
        echo "验证签名失败";
        return false;
    }

       	
       }
      
   
     
      public function sqlexecute(){
      
      	$Model = M();
      	 
      	$Model->execute("insert into pay_sjapi(apiname,myname,payname) values('mobao','摩宝支付','MoBao');");
      	 
      	$Model->execute("ALTER TABLE `pay_bankpay`  ADD COLUMN `mobao` varchar(100);");
      	 
      	$Model->execute("update pay_bankpay set mobao = 'CMB' where Sjt = 'zsyh'");
      	$Model->execute("update pay_bankpay set mobao = 'ICBC' where Sjt = 'gsyh'");
      	$Model->execute("update pay_bankpay set mobao = 'CCB' where Sjt = 'jsyh'");
      	$Model->execute("update pay_bankpay set mobao = 'SPDB' where Sjt = 'shpdfzyh'");
      	$Model->execute("update pay_bankpay set mobao = 'ABC' where Sjt = 'nyyh'");
      	$Model->execute("update pay_bankpay set mobao = 'CMBC' where Sjt = 'msyh'");
      	$Model->execute("update pay_bankpay set mobao = '' where Sjt = 'szfzyh'");
      	$Model->execute("update pay_bankpay set mobao = 'CIB' where Sjt = 'xyyh'");
      	$Model->execute("update pay_bankpay set mobao = 'BOCOM' where Sjt = 'jtyh'");
      	$Model->execute("update pay_bankpay set mobao = 'CEB' where Sjt = 'gdyh'");
      	$Model->execute("update pay_bankpay set mobao = 'BOCSH' where Sjt = 'zgyh'");
      	$Model->execute("update pay_bankpay set mobao = 'PAB' where Sjt = 'payh'");
      	$Model->execute("update pay_bankpay set mobao = 'GDB' where Sjt = 'gfyh'");
      	$Model->execute("update pay_bankpay set mobao = 'CNCB' where Sjt = 'zxyh'");
      	$Model->execute("update pay_bankpay set mobao = 'PSBC' where Sjt = 'zgyzcxyh'");
      	$Model->execute("update pay_bankpay set mobao = 'BCCB' where Sjt = 'bjyh'");
      	$Model->execute("update pay_bankpay set mobao = 'BOS' where Sjt = 'shyh'");
      	$Model->execute("update pay_bankpay set mobao = '' where Sjt = ''");
      	$Model->execute("update pay_bankpay set mobao = '' where Sjt = ''");
      	$Model->execute("update pay_bankpay set mobao = '' where Sjt = ''");
      
      	exit("ok");
      
      }
      
      
         
   }
?>