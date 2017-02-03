<?php
    class WftWxAction extends PayAction{
        
        public function Post(){
            
            $this->PayName = "WftWx";
            $this->TradeDate = date("YmdHis");
            $this->Paymoneyfen = 100;
            $this->check();
            $this->Orderadd();
                    
                    
            $tjurl = "https://pay.swiftpass.cn/pay/gateway";
                    
            $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_WftWx_MerChantUrl.html";      //商户通知地址
                
            $this->_Return_url= "http://".C("WEB_URL")."/Payapi_WftWx_ReturnUrl.html";   //用户通知地址
                    
            $Sjapi = M("Sjapi");
            $this->_MerchantID = $Sjapi->where("apiname='WftWx'")->getField("shid"); //商户ID
            $this->_Md5Key = $Sjapi->where("apiname='WftWx'")->getField("key"); //密钥   
            $zhanghu = $Sjapi->where("apiname='".$this->PayName."'")->getField("zhanghu");
            $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            
                
                //////////////////////////////////////////////////////////////////////
                
                    $user_ip = "";
                    if(isset($_SERVER['HTTP_CLIENT_IP']))
                    {
                        $user_ip = $_SERVER['HTTP_CLIENT_IP'];
                    }
                    elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                    {
                        $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    }
                    else
                    {
                        $user_ip = $_SERVER['REMOTE_ADDR'];
                    }
                    
                    $arraystr = array(
                     
                        "service" => "pay.weixin.native",
                        "mch_id" => $this->_MerchantID,
                        "out_trade_no" => $this->TransID,
                        "body" => "支付",  
                        "total_fee" => $this->sjt_OrderMoney, 
                        "mch_create_ip" => $user_ip,
                        "notify_url" =>  $this->_Return_url,         
                        "nonce_str" => $this->randpw(32,'NUMBER'),
                    );
                    
                        ksort($arraystr);
                        $buff = "";
                        foreach ($arraystr as $k => $v)
                        {
                            if($k != "sign" && $v != "" && !is_array($v)){
                                $buff .= $k . "=" . $v . "&";
                            }
                        }
                        
                        $buff = trim($buff, "&");
                       // echo($buff."<br>");
                        //////////////////////////////////////////
                        //签名步骤二：在string后加入KEY
                        $string = $buff . "&key=".$this->_Md5Key;
                        //签名步骤三：MD5加密
                        $string = md5($string);
                        //签名步骤四：所有字符转为大写
                        $sign = strtoupper($string);
                        $arraystr["sign"] = $sign;
                        
                        $xml = "<xml>";
                        foreach ($arraystr as $key=>$val)
                        {
                            if (is_numeric($val)){
                                $xml.="<".$key.">".$val."</".$key.">";
                            }else{
                                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
                            }
                            //echo($key."<br>");
                        }
                        $xml.="</xml>";
                       
                        /*
                        $ch = curl_init();
                        //设置超时
                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                        
                       
                        curl_setopt($ch,CURLOPT_URL, $tjurl);
                        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
                        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
                        //设置header
                        curl_setopt($ch, CURLOPT_HEADER, FALSE);
                        //要求结果为字符串且输出到屏幕上
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    
                       
                        //post提交方式
                        curl_setopt($ch, CURLOPT_POST, TRUE);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                        //运行curl
                        $data = curl_exec($ch);
                        */
                        /////////////////////////////////////////////////////////////
                        $ch = curl_init();

                        // 设置curl允许执行的最长秒数
                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
                        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
                        // 获取的信息以文件流的形式返回，而不是直接输出。
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                        
                        //发送一个常规的POST请求。
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_URL, $tjurl);
                        //要传送的所有数据
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                        
                        // 执行操作
                         $data = curl_exec($ch);
                        ////////////////////////////////////////////////////////////
                      
                        
                        if($data){
                            curl_close($ch);
                            libxml_disable_entity_loader(true);
                            $dataxml = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
                           
                            if($dataxml['status'] == 0 and $dataxml['result_code'] == 0){
                                Vendor("phpqrcode.phpqrcode",'',".php");
                                $url = urldecode($dataxml['code_url']);
                               $QR = "Public/codepay/".date("YmdHis").".png";//已经生成的原始二维码图   
                               QRcode::png($url,$QR,"L",20);
                               $Order = M("Order");
                               $data = array();
                               $data["ewmimg"] = $QR;
                               $Order->where("TransID = '".$this->TransID."'")->save($data);
                               $this->assign("orderid",$this->TransID);
                               $this->assign("imgurl",$QR);
                               $this->assign("money",($this->sjt_OrderMoney)/100);
                               $this->display("Paywx");
                            }else{
								 exit($dataxml['message']."------".$dataxml['status']);
							}
                        }else{
                        
                         echo($dataxml['message']);
                         echo($dataxml['result_code']);
                     }
                /////////////////////////////////////////////////////////////////////
            
                       
        }
        
      
        public function ReturnUrl(){
            /////////////////////////////////////////////////////////////////////////////////////
               $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
               libxml_disable_entity_loader(true);
               $arraystr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true); 
               $Sjapi = M("Sjapi");
               $pkey = $Sjapi->where("apiname='WftWx'")->getField("key"); //密钥      
                //签名步骤一：按字典序排序参数
                ksort($arraystr);
                ///////////////////////////////////////////
                $buff = "";
                foreach ($arraystr as $k => $v)
                {
                    if($k != "sign" && $v != "" && !is_array($v)){
                        $buff .= $k . "=" . $v . "&";
                    }
                }
                
                $buff = trim($buff, "&");
                //////////////////////////////////////////
                //签名步骤二：在string后加入KEY
                $string = $buff . "&key=".$pkey;
                //签名步骤三：MD5加密
                $string = md5($string);
                //签名步骤四：所有字符转为大写
                $sign = strtoupper($string);
            ////////////////////////////////////////////////////////////////////////////////////
        
         if ($sign == $arraystr["sign"]) 
            {
                $_TransID = $arraystr["out_trade_no"];
                if($arraystr["status"] <> 0  or $arraystr["result_code"] <> 0){
                //exit($_resultDesc);
                ///////////////////////////////////////////////////////////////////
                $Order = D("Order");
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 
               $Ordertz = M("Ordertz");
               $ordertzlist = $Ordertz->where("Sjt_TransID = '".$_TransID."'")->select();
               if(!$ordertzlist){
                   $data["Sjt_MerchantID"] = $Sjt_MerchantID;
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
              $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url"); 
                //后台通知地址
                $Sjt_Return_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$_TransID."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$_TransID."'")->getField("Username");
              
              $typepay = $Order->where("TransID = '".$_TransID."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$_TransID."'")->getField("payname");
              
               $tranAmt = $Order->where("TransID = '".$_TransID."'")->getField("trademoney");
               $fhlx = $Order->where("TransID = '".$_TransID."'")->getField("fhlx");
               $ewmimg = $Order->where("TransID = '".$_TransID."'")->getField("ewmimg");
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
                
                $_factMoney = $tranAmt;
                
                
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
                  // $_factMoney = number_format($_factMoney,3);
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
               /*
               $datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$_TransID."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$_factMoney."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
               $tjurl = $Sjt_Return_url."?".$datastr; 
               $contents = fopen($tjurl,"r"); 
               $contents=fread($contents,4096); 
               if($contents == "ok"){
                 $data["success"] = 1;
                 $Ordertz->where("Sjt_TransID = '".$_TransID."'")->save($data);
               }else{
                  
               }
			   */
               /*****************************花旗返回**********************************/
               switch($fhlx){
                   case "huaqi":
                   /////////////////////////////////////////////////////////////////////////////
                    $P_UserId = ($Sjt_MerchantID+10000);
                    $P_OrderId = $_TransID;
                    $P_CardId = "";
                    $P_CardPass = "";
                    $P_FaceValue = $_factMoney;
                    $P_ChannelId = 1 ;

                    $P_Subject = "";
                    $P_Description = ""; 
                    $P_Price = $_factMoney;
                    $P_Quantity = 1;
                    $P_Notic = "";
                    $P_ErrCode = 0;
                    //$P_PostKey = $_REQUEST["P_PostKey"];
                    $P_PayMoney = $_factMoney;
                    
                    
          
                    $preEncodeStr=$P_UserId."|".$P_OrderId."|".$P_CardId."|".$P_CardPass."|".$P_FaceValue."|".$P_ChannelId."|".$Sjt_Key;

                    $P_PostKey=md5($preEncodeStr);
                    
                    $datastr = "P_UserId=$P_UserId&P_OrderId=$P_OrderId&P_CardId=$P_CardId&P_CardPass=$P_CardPass&P_FaceValue=$P_FaceValue&P_ChannelId=$P_ChannelId&P_Subject=$P_Subject&P_Description=$P_Description&P_Price=$P_Price&P_Quantity=$P_Quantity&P_Notic=$P_Notic&P_ErrCode=$P_ErrCode&P_PayMoney=$P_PayMoney&P_PostKey=$P_PostKey";
                    
                    $Order = M("Order");
                    $data = array();
                    $data["tbhdurl"] = $Sjt_Merchant_url."?".$datastr;
                    $Order->where("TransID = '".$_TransID."'")->save($data);
                    unlink($ewmimg);  
                    
                     $tjurl = $Sjt_Return_url."?".$datastr; 
                   //file_put_contents("zyzyzzy.txt",$tjurl."-----[".date("Y-m-d H:i:s")."]\n", FILE_APPEND);
                   $contents = fopen($tjurl,"r"); 
                   $contents=fread($contents,4096); 
                   if($contents == "ok"){
                     $data["success"] = 1;
                     $Ordertz->where("Sjt_TransID = '".$_TransID."'")->save($data);
                   }
                   ////////////////////////////////////////////////////////////////////////////
                   break;
                   default:
                   ///////////////////////////////////////////////////////////
                    $datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$_TransID."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$_factMoney."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
                   $tjurl = $Sjt_Return_url."?".$datastr; 
                   $contents = fopen($tjurl,"r"); 
                   $contents=fread($contents,4096); 
                   if($contents == "ok"){
                     $data["success"] = 1;
                     $Ordertz->where("Sjt_TransID = '".$_TransID."'")->save($data);
                   }
                   //////////////////////////////////////////////////////////
               }
               /*****************************花旗返回**********************************/
               
          }
               ///////////////////////////////////////////////////////
              
                exit("success");
            }
            //处理想处理的事情，验证通过，根据提交的参数判断支付结果
        } 
        else {
             
            exit("no_no");
        } 
//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&              
        }
        
        
        
        
       
            
 function randpw($len=8,$format='ALL'){
    $is_abc = $is_numer = 0;
    $password = $tmp ='';
    switch($format){
        case 'ALL':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
        case 'CHAR':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
        case 'NUMBER':
            $chars='0123456789';
            break;
        default :
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
    }
    mt_srand((double)microtime()*1000000*getmypid());

    while(strlen($password)<$len){

        $tmp =substr($chars,(mt_rand()%strlen($chars)),1);
        if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
            $is_numer = 1;
        }
        if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
            $is_abc = 1;
        }
        $password.= $tmp;
    }

    if($is_numer <> 1 || $is_abc <> 1 || empty($password) ){
        $password = randpw($len,$format);
    }
    return $password;
}

public function checkstatus(){
    $orderid = $this->_request("orderid");

    $Order = M("Order");
    $find = $Order->where("TransID = '".$orderid."'")->find();
    if(!$find){
        $this->error('0','',true);
//         $json = array(
//             "status" => "error",
//         );
			
    }else{
        if($find["Zt"] <> 0){
		    $url=U('WftWx/hrefReturn');
            $this->success($url,'',true);
//             $json = array(
//                 "status" => "ok",
//                 "url"    => $this->hqurl($find["id"])
//             );
			
        }else{
            $this->error('0','',true);
//            $json = array(
//                 "status" => "error",
//             ); 
			
        }
    }
    $this->error('0','',true);
   // exit(json_encode($json));
}

public function hrefReturn(){
    $orderid=$this->_request('orderid');
    $this->TongdaoManage($orderid,0);
}
private function hqurl($id){
    $Order = M("Order");
    $tbhdurl = $Order->where("id=".$id)->getField("tbhdurl");
    //if(!$tbhdurl){
        //$this->hqurl($id);
    //}else{
        return $tbhdurl;
    //}
}

    }
?>
