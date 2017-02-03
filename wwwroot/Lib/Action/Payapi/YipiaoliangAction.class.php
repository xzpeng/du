<?php
  class YipiaoliangAction extends PayAction{
      
      public function post(){
          
           $this->PayName = "Yipiaoliang";
            $this->TradeDate = date("YmdHis");
            //exit($this->TradeDate);
            $this->Paymoneyfen = 1;
            $this->check();
            $this->Orderadd();
            
            $tjurl = "https://www.epaylinks.cn/paycenter/v2.0/getoi.do";     //正式提交地址
                    
            $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_Yipiaoliang_MerChantUrl.html";      //商户通知地址
                
            $this->_Return_url= "http://".C("WEB_URL")."/Payapi_Yipiaoliang_ReturnUrl.html";   //用户通知地址
            
            $Sjapi = M("Sjapi");
            $this->_MerchantID = $Sjapi->where("apiname='yipiaoliang'")->getField("shid"); //商户ID
            $this->_Md5Key = $Sjapi->where("apiname='yipiaoliang'")->getField("key"); //密钥   
            $Zhanghu = $Sjapi->where("apiname='yipiaoliang'")->getField("zhanghu"); //商家管理账号
            $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            /* 商户号 */
$partner = $this->_MerchantID;  //130测试商户号只能在219.136.207.190 测试服务器上使用

/* 商户密钥KEY */
$key = $this->_Md5Key; //这是130测试商户的密钥，仅限于用作接入219.136.207.190测试服务器调试使用

//商家订单号，这里用当前时间毫秒数作为订单号，商户应当保持订单号在商户系统的唯一性
date_default_timezone_set('UTC');
$out_trade_no = date("YmdHis");

/* 商品金额,以元为单位   */
$total_fee = $this->sjt_OrderMoney;

/* 交易完成后页面即时通知跳转的URL  */
$return_url = $this->_Merchant_url;

/* 接收后台通知的URL */
$notify_url =  $this->_Return_url;

/* 货币种类（暂只支持RMB-人民币）    */
$currency_type = 'RMB';

/*创建订单的客户端IP（消费者电脑公网IP）   */
//$order_create_ip = $_SERVER['REMOTE_ADDR'];
$order_create_ip = $_SERVER['REMOTE_ADDR'];

/* 接口版本   */
$version = '3.0';

/* 签名算法（暂时只支持MD5）   */
$sign_type = 'SHA256';

//直连银行参数
//$pay_id = "zhaohang";  //直连招商银行参数值
$pay_id =  $this->Sjt_PayID;

//订单备注，该信息使用64位编码提交服务器，并将在支付完成后随支付结果原样返回
$memo = "lzf";
$base64_memo = base64_encode($memo);

/* 支付请求对象 */
$reqHandler = new EpaylinkssubmitAction();
$reqHandler->setKey($key);
//$reqHandler->setGateUrl("https://www.epaylinks.cn/paycenter/v2.0/getoi.do");  //生产服务器
$reqHandler->setGateUrl($tjurl);   //测试服务器

//设置支付参数 
$reqHandler->setParameter("partner", $partner);                   //商户号
$reqHandler->setParameter("out_trade_no", $out_trade_no);       //商家订单号
$reqHandler->setParameter("total_fee", $total_fee);               //商品金额,以元为单位
$reqHandler->setParameter("return_url", $return_url);           //交易完成后页面即时通知跳转的URL
$reqHandler->setParameter("notify_url", $notify_url);           //接收后台通知的URL
$reqHandler->setParameter("currency_type", $currency_type);       //货币种类
$reqHandler->setParameter("order_create_ip",$order_create_ip); //创建订单的客户端IP（消费者电脑公网IP）
$reqHandler->setParameter("version", $version);                   //接口版本
$reqHandler->setParameter("sign_type", $sign_type);               //签名算法（暂时只支持MD5）

//业务可选参数
$reqHandler->setParameter("pay_id", $pay_id);                   //直连银行参数，例子是直接转跳到招商银行时的参数
$reqHandler->setParameter("base64_memo", $base64_memo);           //订单备注的BASE64编码

//请求的URL
$requestUrl = $reqHandler->getRequestURL();

//echo $requestUrl;

//header("location:".$requestUrl);

?>
<script type="text/javascript">
window.location.href= '<?php echo $requestUrl; ?>';
</script>
<?php 
      }
      
      public function MerChantUrl(){
            $Sjapi = M("Sjapi");
            $key = $Sjapi->where("apiname='yipiaoliang'")->getField("key"); //密钥   
              
                $notify = new EpaylinksnotifyAction();
                $notify->setKey($key);

                //验证签名
                if($notify->verifySign()) {
                    
                    $partner = $notify->getParameter("partner");
                    $out_trade_no = $notify->getParameter("out_trade_no");
                    $pay_no = $notify->getParameter("pay_no");
                    $amount = $notify->getParameter("amount");
                    $pay_result = $notify->getParameter("pay_result");
                    $sett_date = $notify->getParameter("sett_date");
                    $base64_memo = $notify->getParameter("base64_memo");
                    $version = $notify->getParameter("version");
                    $sign_type = $notify->getParameter("sign_type");
                    $sign = $notify->getParameter("sign");
                    $memo = base64_decode($base64_memo);

                    if( "1" == $pay_result ) {

                        //处理业务开始
                      //  echo "</br>支付成功!</br></br>";    
                       // echo "商家号：".$partner."</br>";
                      //  echo "商家系统订单号：".$out_trade_no."</br>";
                       // echo "网关系统支付号：".$pay_no."</br>";
                       // echo "订单金额：".$amount."</br>";
                      //  echo "支付结果（1表示成功）：".$pay_result."</br>";
                      //  echo "清算日期：".$sett_date."</br>";
                      //  echo "订单备注：".$memo."</br>";
                       // echo "接口版本：".$version."</br>";
                      //  echo "签名类型：".$sign_type."</br>";
                      //  echo "签名：".$sign."</br>";

                        //注意订单不要重复处理
                        //注意判断返回金额是否与本系统金额相符
                        //处理业务完毕
                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                         /*************************************************************************************************************/
                     $_TransID = $out_trade_no;
                     $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                
                //通知跳转页面
                $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url"); 
                //商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                
                $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
                    
                    $Paycost = M("Paycost");
                      $Sjfl = M("Sjfl");
                      if($typepay == 0 || $typepay == 1 || $typepay == 3){
                          $fv = $Paycost->where("UserID=".$UserID)->getField("wy");
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField("wy");
                          } 
                          
                          $sjflmoney = $Sjfl->where("jkname='yinlian'")->getField("wy"); //上家费率
                          
                      }else{
                          $ywm = $this->dkname($payname);
                           $fv = $Paycost->where("UserID=".$UserID)->getField($ywm);
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField($ywm);
                          } 
                          
                          $sjflmoney = $Sjfl->where("jkname='yinlian'")->getField($ywm); //上家费率
                      }
                      
                      if($sjflmoney == 0){
                             $sjflmoney = 1;
                          }
                           
                     $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");
                
                     $Userapiinformation = D("Userapiinformation");
                     $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                     
                     
                  if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                       $Money = D("Money");
                       $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                       
                       $data["Money"] = $OrderMoney + $Y_Money;
                       $Money->where("UserID=".$UserID)->save($data); //更新账户金额
                       
                            ///////////////////////////////////////////////////////////
                       $Moneydb = M("Moneydb");
                       $data["UserID"] = $UserID;
                       $data["money"] = $OrderMoney;
                       $data["ymoney"] =  $Y_Money;
                       $data["gmoney"] = $Y_Money + $OrderMoney;
                       $data["datetime"] = date("Y-m-d H:i:s");
                       $data["lx"] = 1;
                       $Moneydb->add($data);
                       //////////////////////////////////////////////////////////
                       
                       
                       $data["Zt"] = 1;   
                       
                       $data["sjflmoney"] = $amount - $amount * $sjflmoney; //上家手续费
                       $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                   
                   }
                        $_SuccTime = date("YmdHis"); 
                        $_Result = "1";
                        $_resultDesc = "00";
                        echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
                        echo "<input type=\"hidden\" name=\"Sjt_MerchantID\" value=\"".$Sjt_MerchantID."\">";
                        echo "<input type=\"hidden\" name=\"Sjt_Username\" value=\"".$Sjt_Username."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_TransID\" value=\"".$_TransID."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$_Result."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"".$_resultDesc."\">";   
                        echo "<input type=\"hidden\" name=\"Sjt_factMoney\" value=\"".$amount."\">";       
                        echo "<input type=\"hidden\" name=\"Sjt_SuccTime\" value=\"".$_SuccTime."\">";
                        ////////////////r9_BType/////////////////////
                        echo "<input type='hidden' name='Sjt_BType' value='1' />";
                        ////////////////r9_BType////////////////////
                        
                        $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$amount.$_SuccTime."1".$Md5Key);
                        echo "<input type=\"hidden\" name=\"Sjt_Sign\" value=\"".$Sjt_Md5Sign."\">";  
                        echo "</from>";
                        echo "<script type=\"text/javascript\">";
                        echo "document.Form1.submit();";
                        echo "</script>";
              
                  /*************************************************************************************************************/
                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    
                    } else {
                        //返回通知处理不成功
                        echo "支付失败！</br></br>";
                        echo "商家系统订单号：".$out_trade_no."</br>";
                        echo "网关系统支付号：".$pay_no."</br>";
                        echo "支付结果（0表示未支付，2表示支付失败）：".$pay_result."</br>";
                    }
                    
                } else {
                    echo "<br/>" . "验证签名失败" ;
                }
      }
      
      public function ReturnUrl(){
      
          /* 商家密钥 */
            $Sjapi = M("Sjapi");
            $key = $Sjapi->where("apiname='yipiaoliang'")->getField("key"); //密钥   

            $notify = new EpaylinksnotifyAction();
            $notify->setKey($key);

            //验证签名
            if($notify->verifySign()) {
                
                $partner = $notify->getParameter("partner");
                $out_trade_no = $notify->getParameter("out_trade_no");
                $pay_no = $notify->getParameter("pay_no");
                $amount = $notify->getParameter("amount");
                $pay_result = $notify->getParameter("pay_result");
                $sett_date = $notify->getParameter("sett_date");
                $base64_memo = $notify->getParameter("base64_memo");
                $version = $notify->getParameter("version");
                $sign_type = $notify->getParameter("sign_type");
                $sign = $notify->getParameter("sign");
                $memo = base64_decode($base64_memo);

                if( "1" == $pay_result ) {

                    //处理业务开始
                    echo "</br>获取异步通知信息成功!</br></br>";    
                    echo " success "."</br></br>";
                    echo "商家号：".$partner."</br>";
                    echo "商家系统订单号：".$out_trade_no."</br>";
                    echo "网关系统支付号：".$pay_no."</br>";
                    echo "订单金额：".$amount."</br>";
                    echo "支付结果（1表示成功）：".$pay_result."</br>";
                    echo "清算日期：".$sett_date."</br>";
                    echo "订单备注：".$memo."</br>";
                    echo "接口版本：".$version."</br>";
                    echo "签名类型：".$sign_type."</br>";
                    echo "签名：".$sign."</br>";

                    //注意订单不要重复处理
                    //注意判断返回金额是否与本系统金额相符
                    //处理业务完毕
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                 /*************************************************************************************************************/
                     $_TransID = $out_trade_no;
                     $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                
                //通知跳转页面
                $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                
                $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
                    
                    $Paycost = M("Paycost");
                      $Sjfl = M("Sjfl");
                      if($typepay == 0 || $typepay == 1 || $typepay == 3){
                          $fv = $Paycost->where("UserID=".$UserID)->getField("wy");
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField("wy");
                          } 
                          
                          $sjflmoney = $Sjfl->where("jkname='yinlian'")->getField("wy"); //上家费率
                          
                      }else{
                          $ywm = $this->dkname($payname);
                           $fv = $Paycost->where("UserID=".$UserID)->getField($ywm);
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField($ywm);
                          } 
                          
                          $sjflmoney = $Sjfl->where("jkname='yinlian'")->getField($ywm); //上家费率
                      }
                      
                      if($sjflmoney == 0){
                             $sjflmoney = 1;
                          }
                           
                     $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");
                
                     $Userapiinformation = D("Userapiinformation");
                     $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                     
                     
                  if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                       $Money = D("Money");
                       $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                       
                       $data["Money"] = $OrderMoney + $Y_Money;
                       $Money->where("UserID=".$UserID)->save($data); //更新账户金额
                       
                            ///////////////////////////////////////////////////////////
                       $Moneydb = M("Moneydb");
                       $data["UserID"] = $UserID;
                       $data["money"] = $OrderMoney;
                       $data["ymoney"] =  $Y_Money;
                       $data["gmoney"] = $Y_Money + $OrderMoney;
                       $data["datetime"] = date("Y-m-d H:i:s");
                       $data["lx"] = 1;
                       $Moneydb->add($data);
                       //////////////////////////////////////////////////////////
                       
                       
                       $data["Zt"] = 1;   
                       
                       $data["sjflmoney"] = $amount - $amount * $sjflmoney; //上家手续费
                       $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                   
                   }
                   
                $_SuccTime = date("YmdHis"); 
                $_Result = "1";
                $_resultDesc = "00";    
               $Ordertz = M("Ordertz");
               $ordertzlist = $Ordertz->where("Sjt_TransID = '".$_TransID."'")->select();
               if(!$ordertzlist){
                   $data["Sjt_MerchantID"] = $Sjt_MerchantID;
                   $data["Sjt_UserName"] = $Sjt_Username;
                   $data["Sjt_TransID"] = $_TransID;
                   $data["Sjt_Return"] = $_Result;
                   $data["Sjt_Error"] = $_resultDesc;
                  //$_factMoney = $_factMoney/100;
                   $amount = number_format($amount,3);
                   $data["Sjt_factMoney"] = $amount;
                   $data["Sjt_SuccTime"] = $_SuccTime;
                   $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$_TransID.$_Result.$_resultDesc.$amount.$_SuccTime."2".$Md5Key);
                   $data["Sjt_Sign"] = $Sjt_Md5Sign;
                   $data["Sjt_urlname"] = $Sjt_Return_url;
                   $data["Sjt_BType"] = 2;
                   $Ordertz->add($data);
               } 
               $datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$_TransID."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$amount."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
               $tjurl = $Sjt_Return_url."?".$datastr; 
               $contents = fopen($tjurl,"r"); 
               $contents=fread($contents,4096); 
               if($contents == "ok"){
                 $data["success"] = 1;
                 $Ordertz->where("Sjt_TransID = '".$_TransID."'")->save($data);
               }
              
                  /*************************************************************************************************************/
                /////////////////////////////////////////////////////////////////////////////////////////////
                  echo 'success';
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
                } else {
                    //返回通知处理不成功
                    echo "nonono";
                  
                }
                
            } else {
                echo "<br/>" . "验证签名失败" ;
            }
          
    }
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////         
      
  }
?>
