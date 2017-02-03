<?php
        class KuaihuibaoAction extends PayAction{
            
             public function Post(){
                
                      $this->PayName = "Kuaihuibao";
                      $this->TradeDate = date("YmdHis");
                      $this->Paymoneyfen = 1;
                      $this->check();
                      $this->Orderadd();
                    
                    
                     $tjurl = "https://payment.dinpay.com/PHPReceiveMerchantAction.do";
                    
                     $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_Kuaihuibao_MerChantUrl.html";      //商户通知地址
                
                     $this->_Return_url= "http://".C("WEB_URL")."/Payapi_Kuaihuibao_ReturnUrl.html";   //用户通知地址
                    
                     $Sjapi = M("Sjapi");
                     $this->_MerchantID = $Sjapi->where("apiname='kuaihuibao'")->getField("shid"); //商户ID
                     $this->_Md5Key = $Sjapi->where("apiname='kuaihuibao'")->getField("key"); //密钥   
                     $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
                     ///////////////////////////////////////////////////////////////////////////////////////////////////////
                  
                            //参数编码字符集(必选)
                            $input_charset = "UTF-8";

                            //接口版本(必选)固定值:V3.0
                            $interface_version = "V3.0";

                            //商家号（必填）
                            $merchant_code = $this->_MerchantID;

                            //后台通知地址(必填)
                            $notify_url = $this->_Return_url;

                            //定单金额（必填）
                            $order_amount = $this->sjt_OrderMoney;

                            //商家定单号(必填)
                            $order_no = $this->TransID;

                            //商家定单时间(必填)
                            $order_time = date("Y-m-d H:i:s");

                            //签名方式(必填)
                            $sign_type = "MD5";

                            //商品编号(选填)
                            $product_code = "";

                            //商品描述（选填）
                            $product_desc = "";

                            //商品名称（必填）
                            $product_name = "其它";

                            //商品数量(选填)
                            $product_num = "";

                            //页面跳转同步通知地址(选填)
                            $return_url = $this->_Merchant_url;

                            //业务类型(必填)
                            $service_type = "direct_pay";

                            //商品展示地址(选填)
                            $show_url = "";

                            //公用业务扩展参数（选填）
                            $extend_param = "";

                            //公用业务回传参数（选填）
                            $extra_return_param = "";

                            // 直联通道代码（选填）
                            $bank_code = $this->Sjt_PayID;

                            //客户端IP（选填）
                            $client_ip = "";

                        /* 注  new String(参数.getBytes("UTF-8"),"此页面编码格式"); 若为GBK编码 则替换UTF-8 为GBK*/
                        if($product_name != "") {
                          $product_name = mb_convert_encoding($product_name, "UTF-8", "UTF-8");
                        }
                        if($product_desc != "") {
                          $product_desc = mb_convert_encoding($product_desc, "UTF-8", "UTF-8");
                        }
                        if($extend_param != "") {
                          $extend_param = mb_convert_encoding($extend_param, "UTF-8", "UTF-8");
                        }
                        if($extra_return_param != "") {
                          $extra_return_param = mb_convert_encoding($extra_return_param, "UTF-8", "UTF-8");
                        }
                        if($product_code != "") {
                          $product_code = mb_convert_encoding($product_code, "UTF-8", "UTF-8");
                        }
                        if($return_url != "") {
                          $return_url = mb_convert_encoding($return_url, "UTF-8", "UTF-8");
                        }
                        if($show_url != "") {
                          $show_url = mb_convert_encoding($show_url, "UTF-8", "UTF-8");
                        }


                        /*
                        **签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，
                          则看第二个字母，以此类推，同时将商家支付密钥key放在最后参与签名，
                         ** 组成规则如下：
                         ** 参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
                         **/
                        $signSrc= "";

                        //组织订单信息
                        if($bank_code != "") {
                            $signSrc = $signSrc."bank_code=".$bank_code."&";
                        }
                        if($client_ip != "") {
                                    $signSrc = $signSrc."client_ip=".$client_ip."&";
                        }
                        if($extend_param != "") {
                            $signSrc = $signSrc."extend_param=".$extend_param."&";
                        }
                        if($extra_return_param != "") {
                            $signSrc = $signSrc."extra_return_param=".$extra_return_param."&";
                        }
                        if($input_charset != "") {
                            $signSrc = $signSrc."input_charset=".$input_charset."&";
                        }
                        if($interface_version != "") {
                            $signSrc = $signSrc."interface_version=".$interface_version."&";
                        }
                        if($merchant_code != "") {
                            $signSrc = $signSrc."merchant_code=".$merchant_code."&";
                        }
                        if($notify_url != "") {
                            $signSrc = $signSrc."notify_url=".$notify_url."&";
                        }
                        if($order_amount != "") {
                            $signSrc = $signSrc."order_amount=".$order_amount."&";
                        }
                        if($order_no != "") {
                            $signSrc = $signSrc."order_no=".$order_no."&";
                        }
                        if($order_time != "") {
                            $signSrc = $signSrc."order_time=".$order_time."&";
                        }
                        if($product_code != "") {
                            $signSrc = $signSrc."product_code=".$product_code."&";
                        }
                        if($product_desc != "") {
                            $signSrc = $signSrc."product_desc=".$product_desc."&";
                        }
                        if($product_name != "") {
                            $signSrc = $signSrc."product_name=".$product_name."&";
                        }
                        if($product_num != "") {
                            $signSrc = $signSrc."product_num=".$product_num."&";
                        }
                        if($return_url != "") {
                            $signSrc = $signSrc."return_url=".$return_url."&";
                        }
                        if($service_type != "") {
                            $signSrc = $signSrc."service_type=".$service_type."&";
                        }
                        if($show_url != "") {
                            $signSrc = $signSrc."show_url=".$show_url."&";
                        }
                            //设置密钥
                        $key = $this->_Md5Key; // <支付密钥> 注:此处密钥必须与商家后台里的密钥一致
                        $signSrc = $signSrc."key=".$key;

                        $singInfo = $signSrc;
                        //echo "singInfo=".$singInfo."<br>";

                        //签名
                        $sign = md5($singInfo);
                        //echo "sign=".$sign."<br>";
                   
?>            
                                                                 <form name="Form1" id="Form1" method="post" action="https://pay.dinpay.com//gateway?input_charset=UTF-8">
    <input type="hidden" name="sign" value="<? echo $sign?>" />
    <input type="hidden" name="merchant_code" value="<? echo $merchant_code?>" />
    <input type="hidden" name="bank_code" value="<? echo $bank_code?>"/>
    <input type="hidden" name="order_no" value="<? echo $order_no?>"/>
    <input type="hidden" name="order_amount" value="<? echo $order_amount?>"/>
    <input type="hidden" name="service_type" value="<? echo $service_type?>"/>
    <input type="hidden" name="input_charset" value="<? echo $input_charset?>"/>
    <input type="hidden" name="notify_url" value="<? echo $notify_url?>">
    <input type="hidden" name="interface_version" value="<? echo $interface_version?>"/>
    <input type="hidden" name="sign_type" value="<? echo $sign_type?>"/>
    <input type="hidden" name="order_time" value="<? echo $order_time?>"/>
    <input type="hidden" name="product_name" value="<? echo $product_name?>"/>
    <input Type="hidden" Name="client_ip" value="<? echo $client_ip?>"/>
    <input Type="hidden" Name="extend_param" value="<? echo $extend_param?>"/>
    <input Type="hidden" Name="extra_return_param" value="<? echo $extra_return_param?>"/>
    <input Type="hidden" Name="product_code" value="<? echo $product_code?>"/>
    <input Type="hidden" Name="product_desc" value="<? echo $product_desc?>"/>
    <input Type="hidden" Name="product_num" value="<? echo $product_num?>"/>
    <input Type="hidden" Name="return_url" value="<? echo $return_url?>"/>
    <input Type="hidden" Name="show_url" value="<? echo $show_url?>"/>
    </form>
   
<?php           
        echo("正在加载......");  
           $this->Echots();  
                     
                     /////////////////////////////////////////////////////////////////////////////////////////////////////
                     
                     
             }
            
             public function MerChantUrl(){
                 
             }
             
             public function ReturnUrl(){      //返回地址
                  //=========================== 把商家的相关信息返回去 =======================
                                    
                    $m_id        =     '';             //商家号    
                    $m_orderid    =     '';            //商家订单号
                    $m_oamount    =     '';            //支付金额
                    $m_ocurrency=     '';            //币种        
                    $m_language    =     '';            //语言选择
                    $s_name        =     '';            //消费者姓名
                    $s_addr        =     '';            //消费者住址
                    $s_postcode    =     '';         //邮政编码
                    $s_tel        =     '';            //消费者联系电话
                    $s_eml        =     '';            //消费者邮件地址
                    $r_name        =     '';            //消费者姓名
                    $r_addr        =     '';            //收货人住址
                    $r_postcode    =     '';         //收货人邮政编码
                    $r_tel        =     '';            //收货人联系电话
                    $r_eml        =     '';            //收货人电子地址
                    $m_ocomment    =     '';         //备注
                    $modate        =    '';            //返回日期
                    $State        =    '';            //支付状态2成功,3失败
                    
                    //接收组件的加密
                    $OrderInfo    =    $_POST['OrderMessage'];            //订单加密信息

                    $signMsg     =    $_POST['Digest'];                //密匙
                    //接收新的md5加密认证

                    //检查签名
                     $Sjapi = M("Sjapi");
                     $key = $Sjapi->where("apiname='kuaihuibao'")->getField("key"); //密钥   
                    //$key = "%^&%-*^&";   //<--支付密钥--> 注:此处密钥必须与商家后台里的密钥一致
                    //$digest = $MD5Digest->encrypt($OrderInfo.$key);
                    $digest = strtoupper(md5($OrderInfo.$key));
                //==========================================================================================    
                if ($digest == $signMsg){   //
                    //=============================================================================
                      //解密
                        //$decode = $DES->Descrypt($OrderInfo, $key);
                        $OrderInfo = $this->HexToStr($OrderInfo);
                        //=========================== 分解字符串 ====================================
                        $parm=explode("|", $OrderInfo);

                        $m_id        =     $parm[0];                
                        $m_orderid    =     $parm[1];        
                        $m_oamount    =     $parm[2];            
                        $m_ocurrency=     $parm[3];                
                        $m_language    =     $parm[4];            
                        $s_name        =     $parm[5];                
                        $s_addr        =     $parm[6];                
                        $s_postcode    =     $parm[7];        
                        $s_tel        =     $parm[8];            
                        $s_eml        =     $parm[9];            
                        $r_name        =     $parm[10];            
                        $r_addr        =     $parm[11];                
                        $r_postcode    =     $parm[12];            
                        $r_tel        =     $parm[13];            
                        $r_eml        =     $parm[14];            
                        $m_ocomment    =     $parm[15];
                        $modate        =    $parm[16];
                        $State        =    $parm[17];
                        
                        if ($State == 2)
                        {
                           //=====================================支付成功处理数据=====================================================
                            $Order = D("Order");
                            $UserID = $Order->where("TransID = '".$m_orderid."'")->getField("UserID");
                            
                            //通知跳转页面
                            $Sjt_Merchant_url = $Order->where("TransID = '".$m_orderid."'")->getField("Sjt_Merchant_url"); 
                            //后台通知地址
                           // $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                            //盛捷通商户ID
                            $Sjt_MerchantID = $Order->where("TransID = '".$m_orderid."'")->getField("UserID");
                             //在商户网站冲值的用户的用户名
                            $Sjt_Username = $Order->where("TransID = '".$m_orderid."'")->getField("Username");
                            
                            $OrderMoney = $Order->where("TransID = '".$m_orderid."'")->getField("OrderMoney");
                            
                            $Sjt_Zt = $Order->where("TransID = '".$m_orderid."'")->getField("Zt");  
                            
                          $typepay = $Order->where("TransID = '".$m_orderid."'")->getField("typepay");
                          
                          $payname = $Order->where("TransID = '".$m_orderid."'")->getField("payname");
                          
                          $tranAmt = $Order->where("TransID = '".$m_orderid."'")->getField("trademoney");
                          
                          /////////////////////////////////////////////////////////////////////////////////////////////
                          
                            $Paycost = M("Paycost");
                              $Sjfl = M("Sjfl");
                              if($typepay == 0 || $typepay == 1){
                                  $fv = $Paycost->where("UserID=".$UserID)->getField("wy");
                                  if($fv == 0){
                                      $fv = $Paycost->where("UserID=0")->getField("wy");
                                  } 
                                  
                                  $sjflmoney = $Sjfl->where("jkname='kuaihuibao'")->getField("wy"); //上家费率
                                  
                              }else{
                                  $ywm = $this->dkname($payname);
                                   $fv = $Paycost->where("UserID=".$UserID)->getField($ywm);
                                  if($fv == 0){
                                      $fv = $Paycost->where("UserID=0")->getField($ywm);
                                  } 
                                  
                                  $sjflmoney = $Sjfl->where("jkname='kuaihuibao'")->getField($ywm); //上家费率
                              }
                              
                              if($sjflmoney == 0){
                                     $sjflmoney = 1;
                                  }
                               ////////////////////////////////////////////////////////////////////////////////////
                                $Userapiinformation = D("Userapiinformation");
                                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                                
                                $_factMoney = $m_oamount;
                                
                                //////////////////////////////////////////////////////////////////////////////////////////////////////
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
                                            $count = $Order->where("TransID = '".$m_orderid."'")->count();
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
                                           /////////////////////////////////////////////////////////////////////////
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
                                           $Order->where("TransID = '".$m_orderid."'")->save($data); //将订单设置为成功
                                           
                                        }
                                }else{
                                    $Order->where("TransID = '".$m_orderid."'")->delete();
                                }                 
                          
                             ///////////////////////////////////////////////////////////////////////////////////////////////////////
                             //////////////////////////////////////////////////////////////////////////////////////////////////////
                             $_Result = 1;
                             $_resultDesc = "";
                             $_SuccTime = $modate;
                              if($Diaodangkg == 0 || ($Diaodangkg == 1 && $Diaodan_Type == 3)){  
                                  
                                  //=============================================================================================
                                  $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$m_orderid.$_Result.$_resultDesc.$_factMoney.$_SuccTime."2".$Sjt_Key);
                                  $datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$m_orderid."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$_factMoney."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
                                  
                                   $tjurl = $Sjt_Merchant_url."?".$datastr; 
                                   $contents = fopen($tjurl,"r"); 
                                   $contents=fread($contents,4096); 
                                   if($contents == "ok"){
                                     $data["success"] = 1;
                                     $Ordertz->where("Sjt_TransID = '".$m_orderid."'")->save($data);
                                   }else{
                                      // $data["Sjt_UserName"] = $contents;
                                      // $Ordertz->where("Sjt_TransID = '".$this->_post("out_trade_no")."'")->save($data);
                                   }
                                  //=============================================================================================
                                echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
                                echo "<input type=\"hidden\" name=\"Sjt_MerchantID\" value=\"".$Sjt_MerchantID."\">";
                                echo "<input type=\"hidden\" name=\"Sjt_Username\" value=\"".$Sjt_Username."\">";   
                                echo "<input type=\"hidden\" name=\"Sjt_TransID\" value=\"".$m_orderid."\">";   
                                echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$_Result."\">";   
                                echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"".$_resultDesc."\">";   
                                echo "<input type=\"hidden\" name=\"Sjt_factMoney\" value=\"".$_factMoney."\">";       
                                echo "<input type=\"hidden\" name=\"Sjt_SuccTime\" value=\"".$_SuccTime."\">";
                                ////////////////r9_BType/////////////////////
                                echo "<input type='hidden' name='Sjt_BType' value='1' />";
                                ////////////////r9_BType////////////////////
                                
                                $Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$m_orderid.$_Result.$_resultDesc.$_factMoney.$_SuccTime."1".$Sjt_Key);
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
                             /////////////////////////////////////////////////////////////////////////////////////////////////////
                             ////////////////////////////////////////////////////////////////////////////////////////////////////
                          
                          
                          
                           //=====================================支付成功处理数据=====================================================
                        }
                    else 
                        {
                            echo "支付失败";
                        }
            
                    //=============================================================================
                }else{
                    
                    $this->Sjt_Return = 0;
                    $this->Sjt_Error =  "9";  //密钥错误
                    $this->Sjt_Merchant_url = "";
                    $this->RunError();
                }

             }
             
             public function StrToHex($string)    //加密函数
             {
                 $hex="";
                 for ($i=0;$i<strlen($string);$i++)
                     $hex.=dechex(ord($string[$i]));
                 $hex=strtoupper($hex);
                 return $hex;
              }
              
              public function HexToStr($hex)    //解密函数
              {
                 $string="";
                 for ($i=0;$i<strlen($hex)-1;$i+=2)
                     $string.=chr(hexdec($hex[$i].$hex[$i+1]));
                 return $string;
              } 
                         
        }
?>
