<?php
    
    class WftWxWapAction extends PayAction{
        
        public function Post(){
            
            $this->PayName = "WftWxWap";
            $this->TradeDate = date("YmdHis");
            $this->Paymoneyfen = 100;
            $this->check();
            $this->Orderadd();
            $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            $tjurl = "https://pay.swiftpass.cn/pay/gateway";
                    
            $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_WftWxWap_MerChantUrl.html";      //商户通知地址
                
            $this->_Return_url= "http://".C("WEB_URL")."/Payapi_WftWxWap_ReturnUrl.html";   //用户通知地址
            header("Location:http://".C("WEB_URL")."/wxwap/api/pay.php?mhtOrderName=".$this->TransID."&mhtOrderAmt=".$this->sjt_OrderMoney."&mhtOrderDetail=test");   
                       
        }
  

       public function MerChantUrl(){
           $request=file_get_contents('php://input');
           parse_str($request,$request_form);
		   $out_trade_no=$request_form['mhtOrderNo'];
		   $this->TongdaoManage($out_trade_no,0);
		   file_put_contents('Lib/Action/Payapi/1.txt', $request_form .$xml."\r\n",FILE_APPEND);
           require_once 'services/Services.php';
           if (Services::verifySignature($request_form)){
             $tradeStatus=$request_form['tradeStatus'];
             echo "success=Y";
           if($tradeStatus!=""&&$tradeStatus=="A001"){      
		      $this->TongdaoManage($out_trade_no,0);
              }
		       		echo "验证失效";
            }
					echo "验证失败";
            //验证签名失败		       	
       }

    public function ReturnUrl(){
	file_put_contents('Lib/Action/Payapi/2.txt', $_GET ."\r\n",FILE_APPEND);
        require_once 'services/Services.php';
    $response="";
    foreach($_GET as $key=>$value){
    $response.=$key."=".$value."&";
    }
    if (Services::verifySignature($_GET)){
        $tradeStatus=$_GET['tradeStatus'];
		$out_trade_no=$_GET['mhtOrderNo'];
        if($tradeStatus!=""&&$tradeStatus=="A001"){
            //支付成功
			$this->TongdaoManage($out_trade_no,0);
            /**
            * 在这里对数据进行处理
            */
        }else{
            //支付失败
            echo "支付失败";
        }
    }else{
        //验证签名失败
							echo "验证失败";
    }

	  }
         

    }
?>
