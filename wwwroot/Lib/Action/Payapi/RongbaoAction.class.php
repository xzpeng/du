<?php
    
    class RongbaoAction extends PayAction{
        
        public function Post(){
            
            $this->PayName = "Rongbao";
            $this->TradeDate = date("YmdHis");
            $this->Paymoneyfen = 1;
            $this->check();
            $this->Orderadd();
                    
                    
            $tjurl = "http://epay.reapal.com/portal";
                    
            $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_Rongbao_MerChantUrl.html";      //商户通知地址
                
            $this->_Return_url= "http://".C("WEB_URL")."/Payapi_Rongbao_ReturnUrl.html";   //用户通知地址
                    
            $Sjapi = M("Sjapi");
            $this->_MerchantID = $Sjapi->where("apiname='Rongbao'")->getField("shid"); //商户ID
            $this->_Md5Key = $Sjapi->where("apiname='Rongbao'")->getField("key"); //密钥   
            $zhanghu = $Sjapi->where("apiname='".$this->PayName."'")->getField("zhanghu");
            $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            
           
            $parameter = array(
                "service"            => "online_pay",    //接口名称，不需要修改
                "payment_type"        => "1",                           //交易类型，不需要修改

                //获取配置文件(rongpay_config.php)中的值
                "merchant_ID"            => $this->_MerchantID,
                "seller_email"        => $zhanghu,
                "return_url"        => $this->_Merchant_url,
                "notify_url"        => $this->_Return_url,
                "charset"    => "GBK",
              

                //从订单数据中动态获取到的必填参数
                "order_no"        => $this->TransID,
                "title"            => "sjt",
                "body"                => "sjt",
                "total_fee"            => $this->sjt_OrderMoney,

                //扩展功能参数——银行直连
                "paymethod"            => "directPay",
                "defaultbank"        => $this->Sjt_PayID
            );
            
             $parameter    = $this->para_filter($parameter);
             
              $sort_array   = $this->arg_sort($parameter);    //得到从字母a到z排序后的签名参数数组
              $sign = $this->build_mysign($sort_array,$this->_Md5Key,"MD5");
              
               $sHtml = "<form id='rongpaysubmit' name='rongpaysubmit' action='".$tjurl."' method='get'>";
                //POST方式传递（GET与POST二必选一）
                //$sHtml = "<form id='rongpaysubmit' name='rongpaysubmit' action='".$this->gateway."charset=".$this->parameter['charset']."' method='post'>";

                while (list ($key, $val) = each ($parameter)) 
                {
                    $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
                }

                $sHtml = $sHtml."<input type='hidden' name='sign' value='".$sign."'/>";
                $sHtml = $sHtml."<input type='hidden' name='sign_type' value='MD5'/>";

                //submit按钮控件请不要含有name属性
                $sHtml = $sHtml."<input type='submit' value='Ok'></form>";
                
                $sHtml = $sHtml."<script>document.forms['rongpaysubmit'].submit();</script>";
                
                echo $sHtml;
            
                       
        }
        
        public function MerChantUrl(){
            
            $MerchantID = $Sjapi->where("apiname='Rongbao'")->getField("shid"); //商户ID
            $Md5Key = $Sjapi->where("apiname='Rongbao'")->getField("key"); //密钥     
            $veryfy_url = "http://interface.reapal.com/verify/notify?merchant_ID=".$MerchantID."&notify_id=".$_POST["notify_id"];
            
            $veryfy_result = file_get_contents($veryfy_url);
            
            $post          = $this->para_filter($_POST);    //对所有POST返回的参数去空
            $sort_post     = $this->arg_sort($post);        //对所有POST反馈回来的数据排序
            $mysign  = $this->build_mysign($sort_post,$Md5Key,"MD5");   //生成签名结果
            
            
            //=========================================================================================
            $trade_no = $_REQUEST['trade_no'];                //融宝支付交易号
           // $order_no = $_REQUEST['order_no'];            //获取订单号
          //  $total_fee = $_REQUEST['total_fee'];                //获取总金额
         //   $title = $_REQUEST['title'];                //商品名称、订单名称
           // $body = $_REQUEST['body'];                      //商品描述、订单备注、描述
           // $buyer_email = $_REQUEST['buyer_email'];        //买家融宝支付账号
           // $trade_status = $_REQUEST['trade_status'];        //交易状态
            //=========================================================================================
            
             $_MerchantID = $this->_request("merId");   //商户号
             $_TransID =  $_REQUEST['order_no'];     //商户流水号
             $_Result = 1;    //支付结果(1:成功,0:失败)
             $_resultDesc = $_REQUEST['body'];    //支付结果描述
             $_factMoney = $_REQUEST['total_fee'];    //实际成交金额
             $_additionalInfo = $_REQUEST['body'];    //订单附加消息
             $_SuccTime = $this->_request("respTime");    //交易成功时间
             //$_Md5Sign = $this->_request("signature");    //Md5签名字段
            //=========================================================================================
            
            if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_POST["sign"]) 
            {
                    if($trade_status=="TRADE_FINISHED"){
                        //***********************************************************************************************************
                            $Order = D("Order");
                            $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                            
                            //通知跳转页面
                            $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url"); 
                            //后台通知地址
                           // $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                            //盛捷通商户ID
                            $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                             //在商户网站冲值的用户的用户名
                            $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
                            
                            $OrderMoney = $Order->where("TransID = '".$_TransID."'")->getField("OrderMoney");
                            
                            $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");  
                            
                            $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
                          
                            $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
                               
                            $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
                          //////////////////////////////////////////////////////////////////////
                           $Paycost = M("Paycost");
                              $Sjfl = M("Sjfl");
                              if($typepay == 0 || $typepay == 1){
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
                              
                              /////////////////////////////////////////////////////////////////////
                               $Userapiinformation = D("Userapiinformation");
                                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                                
                                $_factMoney = $_factMoney / 100;
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
                   //////////////////////////////////////////////////////////
                   
                   
                   $data["Zt"] = 1;   
                   $data["TcMoney"] = $tcmoney;
                   $data["sjflmoney"] = $_factMoney - $_factMoney * $sjflmoney; //上家手续费
                   $Order->where("TransID = '".$_TransID."'")->save($data); //将订单设置为成功
                   
                }
        }else{
            $Order->where("TransID = '".$_TransID."'")->delete();
        }
                
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
                             
                        //***********************************************************************************************************
                    }else{
                        
                       $Order = D("Order"); 
                       $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
                       $this->Sjt_Return = 0;
                       $this->Sjt_Error =  "9";  //密钥错误
                       $this->Sjt_Merchant_url = $Sjt_Merchant_url;
                       $this->RunError(); 
                       
                    }
            } 
            else 
            {
                    $Order = D("Order"); 
                    $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
                    $this->Sjt_Return = 0;
                    $this->Sjt_Error =  "9";  //密钥错误
                    $this->Sjt_Merchant_url = $Sjt_Merchant_url;
                    $this->RunError();
            }
        }
         
        public function ReturnUrl(){
//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
                    $MerchantID = $Sjapi->where("apiname='Rongbao'")->getField("shid"); //商户ID
            $Md5Key = $Sjapi->where("apiname='Rongbao'")->getField("key"); //密钥     
            $veryfy_url = "http://interface.reapal.com/verify/notify?merchant_ID=".$MerchantID."&notify_id=".$_POST["notify_id"];
            
            $veryfy_result = file_get_contents($veryfy_url);
            
            $post          = $this->para_filter($_POST);    //对所有POST返回的参数去空
            $sort_post     = $this->arg_sort($post);        //对所有POST反馈回来的数据排序
            $mysign  = $this->build_mysign($sort_post,$Md5Key,"MD5");   //生成签名结果
            
            
            //=========================================================================================
            $trade_no = $_REQUEST['trade_no'];                //融宝支付交易号
           // $order_no = $_REQUEST['order_no'];            //获取订单号
          //  $total_fee = $_REQUEST['total_fee'];                //获取总金额
         //   $title = $_REQUEST['title'];                //商品名称、订单名称
           // $body = $_REQUEST['body'];                      //商品描述、订单备注、描述
           // $buyer_email = $_REQUEST['buyer_email'];        //买家融宝支付账号
           // $trade_status = $_REQUEST['trade_status'];        //交易状态
            //=========================================================================================
            
             $_MerchantID = $this->_request("merId");   //商户号
             $_TransID =  $_REQUEST['order_no'];     //商户流水号
             $_Result = 1;    //支付结果(1:成功,0:失败)
             $_resultDesc = $_REQUEST['body'];    //支付结果描述
             $_factMoney = $_REQUEST['total_fee'];    //实际成交金额
             $_additionalInfo = $_REQUEST['body'];    //订单附加消息
             $_SuccTime = $this->_request("respTime");    //交易成功时间
             //$_Md5Sign = $this->_request("signature");    //Md5签名字段
            //=========================================================================================
        
         if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_POST["sign"]) 
            {
                    if($trade_status=="TRADE_FINISHED"){
                //echo("没有支付成功！");
                //exit($_resultDesc);
                ///////////////////////////////////////////////////////////////////
                $Order = D("Order");
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 
               $Ordertz = M("Ordertz");
               $ordertzlist = $Ordertz->where("Sjt_TransID = '".$_TransID."'")->select();
               if(!$ordertzlist){
                   $data["Sjt_MerchantID"] = $Sjt_MerchantID;
                   //$data["Sjt_UserName"] = $Sjt_Username;
                   $data["Sjt_TransID"] = $_TransID;
                   $data["Sjt_Return"] = $_Result;
                   $data["Sjt_Error"] = $_resultDesc;
                   $data["success "] = 2;
                   $Ordertz->add($data);
               }
                ///////////////////////////////////////////////////////////////////
            }else{
                $Order = D("Order");
                $UserID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
              
                //后台通知地址
                $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
              
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
              
               $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
              
              //////////////////////////////////////////////////////////////////////
              $Paycost = M("Paycost");
              $Sjfl = M("Sjfl");
              if($typepay == 0 || $typepay == 1){
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
              
              /////////////////////////////////////////////////////////////////////
                $Sjt_Zt = $Order->where("TransID = '".$_TransID."'")->getField("Zt");
                
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
          
          if($Diaodangkg == 0){   //如果不掉单执行下面的操作   
                
                if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                   $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   $OrderMoney = $_factMoney * $fv; //实际金额
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
                   
                   //////////////////////////////////////////////////////////
                   
                   $data["Zt"] = 1;   
                   $data["TcMoney"] = $tcmoney;
                   //$data["TradeDate"] = date("Y-m-d h:i:s");  
                   $jiaoyijine = $_factMoney * $fv;
                   $data["OrderMoney"] = $jiaoyijine;     //实际订单金额
                   $data["trademoney"] = $_factMoney;   //交易金额
                   $data["sxfmoney"] = $_factMoney - $jiaoyijine; //手续费
                   $data["sjflmoney"] = $_factMoney - $_factMoney * $sjflmoney; //上家手续费
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
            }
            //处理想处理的事情，验证通过，根据提交的参数判断支付结果
        } 
        else {
             
            exit("no_no");
        } 
//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&              
        }
        
        
        
        
        //========================================================================================================================
                    /**
             *功能：融宝支付接口公用函数
             *详细：该页面是请求、通知返回两个文件所调用的公用函数核心处理文件，不需要修改
             *修改日期：2012-01-04
             '说明：
             '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
             '该代码仅供学习和研究融宝支付接口使用，只是提供一个参考。

            */
            function build_mysign($sort_array,$key,$sign_type = "MD5") 
            {
                $prestr = $this->create_linkstring($sort_array);         //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
                $prestr = $prestr.$key;                            //把拼接后的字符串再与安全校验码直接连接起来
                $mysgin = $this->sign($prestr,$sign_type);                //把最终的字符串签名，获得签名结果
                return $mysgin;
            }    

            /********************************************************************************/

            /**
                *把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
                *$array 需要拼接的数组
                *return 拼接完成以后的字符串
            */
            function create_linkstring($array) 
            {
                $arg  = "";
                while (list ($key, $val) = each ($array)) 
                {
                    $arg.=$key."=".$val."&";
                }
                $arg = substr($arg,0,count($arg)-2);             //去掉最后一个&字符
                return $arg;
            }

            /********************************************************************************/

            /**
                *除去数组中的空值和签名参数
                *$parameter 签名参数组
                *return 去掉空值与签名参数后的新签名参数组
             */
            function para_filter($parameter) 
            {
                $para = array();
                while (list ($key, $val) = each ($parameter)) 
                {
                    if($key == "sign" || $key == "sign_type" || $val == "")
                    {
                        continue;
                    }
                    else
                    {
                        $para[$key] = $parameter[$key];
                    }
                }
                return $para;
            }
            /********************************************************************************/

            /**对数组排序
                *$array 排序前的数组
                *return 排序后的数组
             */
            function arg_sort($array) 
            {
                ksort($array);
                reset($array);
                return $array;
            }

            /********************************************************************************/

            /**签名字符串
                *$prestr 需要签名的字符串
                *return 签名结果
             */
            function sign($prestr,$sign_type) 
            {
                $sign='';
                if($sign_type == 'MD5') 
                {
                    $sign = md5($prestr);
                }
                else 
                {
                    die("融宝支付暂不支持".$sign_type."类型的签名方式");
                }
                return $sign;
            }

            /********************************************************************************/

    }
?>
