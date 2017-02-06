<?php
     class QQbaoAction extends PayAction{
         
         
         public function Post($typego){
			 
			 
             
            $this->PayName = "qqbao";
            $this->TradeDate = date("Y-m-d H:i:s");
            $this->Paymoneyfen = 1;
           
		    $typego =  $_POST['typego'];
		  
		    if($typego=='pc')
			{
				$this->bankname = "QQ钱包扫码";
				
			}else
			{
				 $this->bankname = "QQ钱包wap";
			}
            $this->check();
           
            $this->Orderadd();
           
            
              $this->_Merchant_url = "http://".C("WEB_URL")."/Payapi_QQbao_MerChantUrl.html";      //商户通知地址
           // $this->_Merchant_url = "";
        
             $this->_Return_url = "http://".C("WEB_URL")."/Payapi_QQbao_ReturnUrl.html";   //用户通知地址
            ////////////////////////////////////////////////qqbao
             $Sjapi = M("Sjapi");
             $this->_MerchantID = $Sjapi->where("apiname='".$this->PayName."'")->getField("shid"); //商户ID
             $this->_Md5Key = $Sjapi->where("apiname='".$this->PayName."'")->getField("key"); //密钥   
             $zhanghu = $Sjapi->where("apiname='".$this->PayName."'")->getField("zhanghu");
             $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            
             
             
             $string = "out_trade_no=".$this->TransID.'&total_fee='.$this->sjt_OrderMoney.''.
                       "&key=".$this->_Md5Key."&partner=".$this->_MerchantID;
             
             $url ="http://du.pengxiaozhou.com/api/tenpay_api/payRequestcode.php?".$string;
             echo "<script>location.href='".$url."'</script>";
             
             exit;
             
              

         }
         
		 public  function BaoKoUrlbibi()
		 {
			 echo "订单支付成功"; 
		 }
       
      
   
   
   
     public function BaoKoUrl(){
		    
			 $str = json_encode($_REQUEST);
             file_put_contents('Testko.txt',$str ."\r\n",FILE_APPEND);
			 //echo 'fail';exit;
			 
			 //微信支付异步回调
			 
			 if($_REQUEST['weixin']==1)
			 {
				 
				 echo $str;
				  file_put_contents('Lib/Action/Payapi/1.txt',$str ."\r\n",FILE_APPEND);
			      exit;
			 }
			 
			 			 
		  //接受消息 qqq 钱包 
		 if($_REQUEST['sp_billno']) 
		 {
		       		//商户订单号
		       		$out_trade_no = $_REQUEST['sp_billno'];
		       		//QQqianbao交易号
		       		$trade_no     = $_REQUEST['transaction_id'];
		       		//交易状态
		       		$trade_status = $_REQUEST['pay_result'];
		       	
			     	file_put_contents('jjjjjopo.txt', $str ."\r\n",FILE_APPEND);
						
		       		if($_REQUEST['pay_result'] == 0) {
		       			//判断该笔订单是否在商户网站中已经做过处理
		       			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		       			//如果有做过处理，不执行商户的业务程序
						file_put_contents('jjjjj.txt', $str ."\r\n",FILE_APPEND);
						$this->TongdaoManage($out_trade_no,0);
                        
						echo 'succcess';
						exit;
		       		}else
					{
						echo "fail";exit;
					}
		       		
		  }else{
		       		//验证失败
		       		//如要调试，请看alipay_notify.php页面的verifyReturn函数
		       		echo "fail";exit;
		  }
		  
		  echo 'fail';exit;
		       	
       }
	   
	   
	   
	   public function BaoKoYiBuUrl(){

				$TransID = $_REQUEST['out_trade_no'];
				
				$Order = M("Order");
				$Sjt_Zt = $Order->where("TransID = '".$TransID."'")->getField("Zt");
				
				
				 
				
				if($Sjt_Zt==1)
				{
				     echo 1;  
				}else
				{
					 echo 2; 
				}
				die;
			
       }
      
         
     }
?>