<?php
     class PayAction extends Action{
         
         protected $UserID;  //商户ID号
         protected $TransID;  //订单号
         protected $TradeDate;  //订单时间
         protected $OrderMoney;  //订单金额
         protected $ProductName;  //商品名称
         protected $Amount;  //数量
         protected $Username;  //支付用户名
         protected $Email;  //电子邮件
         protected $Mobile;  //手机
         protected $AdditionalInfo;  //备注
         protected $Zt;  //状态
         protected $Sjt_Merchant_url; //前台通知地址
         protected $Sjt_Return_url;  //后台通知地址
         protected $typepay;  //类型
         
         protected $CardNO;     //卡号
         protected $CardPWD;     //充值卡密码
         
         protected $Sjt_PayID; //支付渠道
         
         protected $Sjt_Return = 1;   //返回状态 1为正常，0为失败 
         protected $Sjt_Error = "01";   //错误编号
         
         protected $PayName;  //接口名称
         protected $_MerchantID;  //接口商户ID
         protected $_Md5Key;    //接口密钥
         protected $_Merchant_url;  //接口前台通知地址
         protected $_Return_url;   //接口后台通知地址
         protected $Paymoneyfen;  //接口金额是分还是元
         protected $sjt_OrderMoney;  //接口提交金额
         
         
         protected function check(){   //验证数据
             
             header("Content-Type:text/html; charset=utf-8");
             
             $this->Sjt_Merchant_url = $this->_request("Sjt_Merchant_url");
             if($this->Sjt_Merchant_url == NULL || $this->Sjt_Merchant_url == ""){
                 $this->Sjt_Merchant_url = $this->_request("p8_Url");
             }
             
             //$this->Sjt_Return_url = $this->_request("Sjt_Return_url");
             $this->Sjt_Return_url = $this->Sjt_Merchant_url;
             if($this->Sjt_Merchant_url == NULL || $this->Sjt_Merchant_url == ""){  //盛捷通商户通知地址
                 $this->Sjt_Return = 0;
                 $this->Sjt_Error = 1;  //商户前台通知地址不能为空
                 $this->RunError();
             }
             
             
             if($this->_request("fhlx") == "huaqi"){
                 $this->Sjt_Return_url = $this->_request("p8_Url");
                 $this->Sjt_Merchant_url = $this->_request("pr_NeedResponse");
             }
             
             $Sjt_MerchantID = $this->_request("Sjt_MerchantID");   //获取盛捷通商户号
             if($Sjt_MerchantID == NULL || $Sjt_MerchantID == ""){
                 $Sjt_MerchantID = $this->_request("p1_MerId");
             }
             if($Sjt_MerchantID == NULL || $Sjt_MerchantID == ""){  //判断盛捷通商户号是否存在
                 $this->Sjt_Return = 0;
                 $this->Sjt_Error = 2;  //商户号不能为空
                 $this->RunError();
             }else{
                 $this->UserID = intval($Sjt_MerchantID)-10000;  //赋值商户ID
                 
                 $User = M("User");
                 
                 if(!$User->where("id=".$this->UserID)->count()){
                     $this->Sjt_Return = 0;
                     $this->Sjt_Error = 3;  //商户号不存在
                     $this->RunError();
                 }
             }
             
             
             $this->Sjt_PayID = $this->_request("Sjt_PayID");   //获取盛捷通商户提交的支付渠道
              if($this->Sjt_PayID == NUll || $this->Sjt_PayID == ""){
                  $this->Sjt_PayID = $this->_request("pd_FrpId");
              }
             
             if($this->Sjt_PayID == NUll || $this->Sjt_PayID == ""){  //判断盛捷通商户提交的支付渠道字段是否存在
                    $this->Sjt_Return = 0;
                    $this->Sjt_Error = 4;  //支付渠道不能为空
                    $this->RunError();
              }else{
                  
                 $paytype = $this->_request("Sjt_Paytype");
                 if($paytype == "" || $paytype == NULL){
                      $this->Sjt_Return = 0;
                      $this->Sjt_Error = 11;   // 支付类型错误 
                      $this->RunError();
                 }
                 
                 if($paytype == "b"){
                     $Bankpay = M("Bankpay");

                     $this->Sjt_PayID = $Bankpay->where("Sjt='".$this->Sjt_PayID."'")->getField($this->PayName);    
                       
 
                     if(!$this->Sjt_PayID){
                        $this->Sjt_Return = 0;
                        $this->Sjt_Error = 5;   // 支付渠道不存在
                        $this->RunError();
                     } 
                 }else{
                     if($paytype == "g"){
                         
                        $Gamepay = M("Gamepay");
                        $this->Sjt_PayID = $Gamepay->where("id=".$this->Sjt_PayID)->getField($this->PayName);
                        if(!$this->Sjt_PayID){
                            $this->Sjt_Return = 0;
                            $this->Sjt_Error = 11;   // 支付类型错误
                            $this->RunError();
                        }  
                         
                     }else{
                         
                            $this->Sjt_Return = 0;
                            $this->Sjt_Error = 5;   // 支付渠道不存在
                            $this->RunError();
                         
                     }
                 }
                
                  
              }
              
              
              $this->OrderMoney = $this->_request("Sjt_OrderMoney");  //获取盛捷通商户提交的订单金额
              if($this->OrderMoney == NUll || $this->OrderMoney == ""){
                  $this->OrderMoney = $this->_request("p3_Amt");
              }
              
              if($this->OrderMoney == NUll || $this->OrderMoney == ""){//判断盛捷通商户提交的订单金额字段是否存在
                    $this->Sjt_Return = 0;
                    $this->Sjt_Error = 6; // 订单金额不能为空
                    $this->RunError();
              }else{
                  
                  if(!is_numeric($this->OrderMoney)){
                       $this->Sjt_Return = 0;
                       $this->Sjt_Error = 7; // 订单金额为不是数字
                       $this->RunError();
                  }
                  
              }
              
              
               $Userapiinformation = D("Userapiinformation");
               $WebsiteUrl = $Userapiinformation->where("UserID=".$this->UserID)->getField("WebsiteUrl");   //获取用户设置的网址
               $yuming = $_SERVER["HTTP_REFERER"];  //获取提交的网址的域名
 
               $Sjt_Key = $Userapiinformation->where("UserID=".$this->UserID)->getField("Key");   //获取用户的密钥
        
        ///////////////////////////////////////////////////////////////////
         $p0_Cmd = $_REQUEST["p0_Cmd"];
      
      $p1_MerId = $_REQUEST["p1_MerId"];
      
      $p2_Order = $_REQUEST["p2_Order"];
      
      $p3_Amt = $_REQUEST["p3_Amt"];
      
      $p4_Cur = $_REQUEST["p4_Cur"];
      
      $p5_Pid = $_REQUEST["p5_Pid"];
      
      $p6_Pcat = $_REQUEST["p6_Pcat"];
      
      $p7_Pdesc = $_REQUEST["p7_Pdesc"];
      
      $p8_Url = $_REQUEST["p8_Url"];
      
      $p9_SAF = $_REQUEST["p9_SAF"];
      
      $pa_MP = $_REQUEST["pa_MP"];
      
      $pd_FrpId = $_REQUEST["pd_FrpId"];
      
      $pr_NeedResponse = $_REQUEST["pr_NeedResponse"];
      
      $hmacstr = $p0_Cmd.$p1_MerId.$p2_Order.$p3_Amt.$p4_Cur.$p5_Pid.$p6_Pcat.$p7_Pdesc.$p8_Url.$p9_SAF.$pa_MP.$pd_FrpId.$pr_NeedResponse.$Sjt_Key;
      
      $hmac = MD5($hmacstr);
        ///////////////////////////////////////////////////////////////////
        
        
        
               if($hmac != strtolower($this->_request("hmac"))){    //商户提交的密钥是否正确
                  
                  if(strstr($yuming,C("WEB_URL_CZ")) == false){
                       $this->Sjt_Return = 0;
                       $this->Sjt_Error = "9";   //密钥错误 
                       $this->RunError();
                  }  
               
                   
               }
               
              // $this->Sjt_Return_url = $this->_request("Sjt_Return_url");      //商户底层（后台）通知地址
              $this->Sjt_Return_url = $this->Sjt_Merchant_url;
                if($this->Sjt_Return_url == Null || $this->Sjt_Return_url == ""){
                   $this->Sjt_Return = 0;
                   $this->Sjt_Error = 10;
                   $this->ReturnUrl();
               }  
               
               
               $paytype = $this->_request("Sjt_Paytype");
               if($paytype == "g"){
                   
                    $this->CardNO = $this->_request("Sjt_CardNumber");  //卡号
                    $this->CardPWD = $this->_request("Sjt_CardPassword");  //密码
              
              if($this->CardNO == Null || $this->CardNO == "" || $this->CardPWD == NULL || $this->CardPWD == ""){
                   $this->Sjt_Return = 0;
                   $this->Sjt_Error = 12;   //卡号或密码错误 
                   $this->ReturnUrl();
              }
               }
             
             
         }
         
         
         protected function Orderadd(){   //添加订单
             
              $p2_Order = $this->_request("p2_Order");
              $Order = M("Order");
             if($p2_Order != NULL && $p2_Order != ""){
                 
                 $OrderTransID = $Order->where("TransID = '".$p2_Order."'")->count();
                 if($OrderTransID > 0){
                     $this->Sjt_Return = 0;
                   $this->Sjt_Error = 13;   //商户号已存在 
                   $this->ReturnUrl();
                   
                 }else{
                    $this->TransID = $p2_Order; 
                 }
                 
             }else{
                 
                 $id_id = $Order->order("id desc")->limit(1)->getField("id");
                 
                 if($this->PayName == "Yinlian"){
                     $this->TransID = date('YmdHis') . strval(mt_rand(100, 999));//流水号
                 }else{
                    $this->TransID = $this->Sjt_MerchantID.date("Ymd").(1000000000+$id_id);//流水号 
                 }
                 
                 
           
             
             $Sjapi = M("Sjapi");
             $this->_MerchantID = $Sjapi->where("apiname='".$this->PayName."'")->getField("shid"); //商户ID
             $this->TransID = $this->_MerchantID.$this->TransID;
             
             }
             

             $this->ProductName=iconv('GB2312', 'UTF-8', $this->_request("p5_Pid"));//产品名称
             $this->AdditionalInfo=iconv('GB2312', 'UTF-8', $this->_request("p7_Pdesc"));//订单附加消息
            // $this->Username = iconv('GB2312', 'UTF-8', $this->_request("Sjt_UserName"));
             
           $this->Username = urldecode($this->_request("Sjt_UserName"));
             
        
              $Order = D("Order");
              $data["UserID"] = $this->UserID;      //商户编号
              $data["TransID"] = $this->TransID;          //订单号
              $data["TradeDate"] =$this->TradeDate;    //订单时间
              
              $Paycost = M("Paycost");
              $fv = $Paycost->where("UserID=".$this->UserID)->getField("wy");
              if($fv == 0){
                  $fv = $Paycost->where("UserID=0")->getField("wy");
              }
              
              $jiaoyijine = $this->OrderMoney * $fv;
			  
			  if($jiaoyijine > $this->OrderMoney){
				  exit("error money");
			  }
              
              $data["OrderMoney"] = $jiaoyijine;     //实际订单金额
              $data["trademoney"] = $this->OrderMoney;   //交易金额
              $data["sxfmoney"] = $this->OrderMoney - $jiaoyijine; //手续费
              $data["ProductName"] = $this->ProductName;     //商品名称
              $data["Username"] = $this->Username;     //支付用户名
              $data["AdditionalInfo"] = $this->TransCode($this->AdditionalInfo);   //订单附加信息
              $data["Sjt_Merchant_url"] = $this->Sjt_Merchant_url;     //跳转地址
              $data["Sjt_Return_url"] = $this->Sjt_Return_url;    //通知地址
              
              //$tjurl = $_SERVER["HTTP_REFERER"];
			  $tjurl = $this->_request("tjurl");
              if($tjurl == NULL || $tjurl == ""){
                  $data["tjurl"] = "http://".C("WEB_URL");
              }else{
                  $data["tjurl"] = $tjurl;
              }
                
              
              switch($this->PayName){
                  case "BaoFu":
                  $data["tongdao"] = "宝付";
                  break;
                  case "Alipay":
                  $data["tongdao"] = "支付宝";
                  break;
                  case "MoBao":
                  $data["tongdao"] = "摩宝支付";
                  break;
                  case "XinSheng":
                  $data["tongdao"] = "新生支付";
                  break;
                  case "Yinlian":
                  $data["tongdao"] = "银联在线";
                  break;
                  case "Kuaihuibao":
                  $data["tongdao"] = "快汇宝";
                  break;
                  case "Rongbao":
                  $data["tongdao"] = "融宝";
                  break;
                  case "Huanxunips":
                  $data["tongdao"] = "环迅IPS";
                  break;
                  case "Yipiaoliang":
                  $data["tongdao"] = "易票联";
                  break;
				  case "Yibao":
                  $data["tongdao"] = "易宝";
                  break;
                  case "Guofubao":
                  $data["tongdao"] = "国付宝";
                  break;
                  case "WftWx":
                  $data["tongdao"] = "威富通-微信";
                  break;
                   case "WftWxWap":
                  $data["tongdao"] = "微信wap";
                  break;
                  case "Tengfutong":
                  $data["tongdao"] = "腾付通";
                  $data["TransID"] = substr($this->TransID,6,20);          //订单号
                  $this->TransID = substr($this->TransID,6,20);          //订单号
                  break;
              }
              
             $paytype = $this->_request("Sjt_Paytype");
             if($paytype == "b"){
               $bankid =  $this->_request("pd_FrpId");
               $Bankpay = M("Bankpay");
               $BankName = $Bankpay->where("Sjt='".$bankid."'")->getField("BankName");
               $data["bankname"] = $BankName;
             }
             
              if(strstr($yuming,C("WEB_URL")) == true){
                  $data["typepay"] = 1;
              }
              
              if($_REQUEST["p6_Pcat"] == "703AC229E8E18062F3B474654E9D476C"){
                  $data["typepay"] = 3;
              }
              
              
               if($paytype == "g"){
                   
                  $data["CardNO"] = $this->CardNO;
                  $data["CardPWD"] = $this->CardPWD;
                  if($_REQUEST["p6_Pcat"] == "703AC229E8E18062F3B474654E9D476C"){
                        $data["typepay"] = 4;
                  }else{
                       $data["typepay"] = 2;
                  }
                  
                  
                  $payid =  $this->_request("pd_FrpId");
                  $Gamepay = M("Gamepay");
                  $GameName  = $Gamepay->where("id=".$payid)->getField("GameName");
                  $data["payname"] = $GameName;
               }
             
              
            $data["fhlx"] = $this->_request("fhlx");  
              
            if (!$Order->add($data)){
                exit("写入失败！".$_SERVER["HTTP_REFERER"]);
            }
             
         }
         
         protected function RunError(){    //返回错误
             
             if($this->Sjt_Merchant_url == "" || $this->Sjt_Merchant_url == null){
                echo $this->Sjt_Error;
             }else{
                echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$this->Sjt_Merchant_url."\">";
                echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$this->Sjt_Return."\">";
                echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"".$this->Sjt_Error."\">";
                echo "<input type='hidden' name='Sjt_BType' value='1'>";
                echo "</from>";
                echo "<script type=\"text/javascript\">";
                echo "document.Form1.submit();";
                echo "</script>";
             }
           
             exit;
             
        }
        
        protected function Echots(){
            
         
            echo "<script type='text/javascript'>";
            echo "var i = 0;";
            echo "dsjid = window.setInterval('djs()',1000);";
            echo "function djs(){";
            echo "    if(i <= 0){";
            echo "        window.clearInterval(dsjid);";
            echo "        document.Form1.submit();";
            echo "    }else{";
            echo "        i = i - 1;";
         
            echo "    }";
            echo "}";
            echo "</script>";
            exit;
        }
        
        protected function dkname($zwm){
            
            $ywm = "";
            
            switch($zwm){
                case "天宏一卡通":
                $ywm = "thykt";
                break;
                
                case "完美一卡通":
                $ywm = "wmykt";
                break;
                
                case "网易一卡通":
                $ywm = "wyykt";
                break;
                
                case "联通充值卡":
                $ywm = "ltczk";
                break;
                
                case "久游一卡通":
                $ywm = "jyykt";
                break;
                
                case "QQ币充值卡":
                $ywm = "qqczk";
                break;
                
                case "搜狐一卡通":
                $ywm = "shykt";
                break;
                
                case "征途游戏卡":
                $ywm = "ztyxk";
                break;
                
                case "骏网一卡通":
                $ywm = "jwykt";
                break;
                
                case "盛大一卡通":
                $ywm = "sdykt";
                break;
                
                case "全国神州行":
                $ywm = "qgszx";
                break;
                
                case "天下一卡通":
                $ywm = "txykt";
                break;
                
                case "电信充值":
                $ywm = "dxczk";
                break;
                
                case "纵游一卡通":
                $ywm = "zyykt";
                break;
                
            }
            
            return $ywm;
        }
        
        
         public function SelectOK(){
           
           $Sjt_TransID = $this->_request("Sjt_TransID");
           
           if($Sjt_TransID == NULL || $Sjt_TransID == ""){
               exit("no1");
           }
           
           $Sign = $this->_request("Sign");
           
           $Order = D("Order");
                
           $UserID = $Order->where("TransID = '".$Sjt_TransID."'")->getField("UserID");
           
           $Userapiinformation = D("Userapiinformation");
                
           $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
           
           $Signs = md5($Sjt_TransID.$Sjt_Key);
           
           if($Sing == $Sings){
               
           $Sjt_Zt = $Order->where("TransID = '".$Sjt_TransID."'")->getField("Zt");
               
               if($Sjt_Zt == 1){
                   echo "ok";
               }else{
                   exit("no2");
               }
               
           }else{
               exit("no3");
           }
       }
       
        private function TransCode($Code){     //中文转码
           return iconv("GBK", "UTF-8", $Code);
      }
      
      /*
       * 返回成功后的处理
       * */
      public function TongdaoManage($TransID,$type=1){
		      	$Order = D("Order");
		      	$UserID = $Order->where("TransID = '".$TransID."'")->getField("UserID");
		      	//后台通知地址
		      	$Sjt_Return_url = $Order->where("TransID = '".$TransID."'")->getField("Sjt_Return_url");
		      	//页面跳转通知地址
		      	$Sjt_Merchant_url = $Order->where("TransID = '".$TransID."'")->getField("Sjt_Merchant_url");
		      	//商户ID
		      	$Sjt_MerchantID = $Order->where("TransID = '".$TransID."'")->getField("UserID");
		      	//在商户网站冲值的用户的用户名
		      	$Sjt_Username = $Order->where("TransID = '".$TransID."'")->getField("Username");
		      	//实际到账金额
		      	$OrderMoney = $Order->where("TransID = '".$TransID."'")->getField("OrderMoney");
		      	//实际交易金额
		      	$trademoney = $Order->where("TransID = '".$TransID."'")->getField("trademoney");
		      	//实际交易金额
		      	$tranAmt = $Order->where("TransID = '".$TransID."'")->getField("trademoney");
		      	//支付类型
		      	$typepay = $Order->where("TransID = '".$TransID."'")->getField("typepay");
		      	//
		      	$payname = $Order->where("TransID = '".$TransID."'")->getField("payname");
		      	//订单状态 0未处理 1已处理
		      	$Sjt_Zt = $Order->where("TransID = '".$TransID."'")->getField("Zt");
		      	
		      	//如果订单没有处理，进行处理操作
		      	if($Sjt_Zt == 0){
		      		 $Order->where("TransID='".$TransID."'")->setField("Zt",1);   //把订单的状态修改为已处理
		      		 
		      		 /*
		      		  * 修改账户金额
		      		  * */
		      		 $Money = D("Money");
		      		 $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
		      		// $Tongdao_Money = $Money->where("UserID=".$UserID)->getField($TongdaoName);
		      		 $data["Money"] = $OrderMoney + $Y_Money;
		      		// $data[$TongdaoName] = $Tongdao_Money + $OrderMoney;
		      		 $Money->where("UserID=".$UserID)->save($data); 
		      		 
		      		 /*
		      		  * 新增资金变动记录
		      		  * */
		      		 $Moneybd = M("Moneybd");
		      		 $data["UserID"] = $UserID;
		      		 $data["money"] = $OrderMoney;
		      		 $data["ymoney"] =  $Y_Money;
		      		 $data["gmoney"] = $Y_Money + $OrderMoney;
		      		 $datatime_datetime = date("Y-m-d H:i:s");
		      		 $data["datetime"] = $datatime_datetime;
		      		// $data["tongdao"] = $TongdaoName;
		      		 $data["TransID"] = $TransID;
		      		 $data["lx"] = 1;
		      		 $result = $Moneybd->add($data);
		      		 
		      		 $this->bianliticheng($UserID, $tranAmt,$TransID);    //遍历提成
		      		
		      	}
		      	$Userapiinformation = D("Userapiinformation");
		      	$keystr = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
		      	$_Result = 1;
		      	$_resultDesc = "";
		      	$_SuccTime = date("YmdHis");
		      	if($type ==0){
		      		$Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$TransID.$_Result.$_resultDesc.$tranAmt.$_SuccTime."1".$keystr);
		      		echo "<form id=\"Form1\" name=\"Form1\" method=\"post\" action=\"".$Sjt_Merchant_url."\">";
		      		echo "<input type=\"hidden\" name=\"Sjt_MerchantID\" value=\"".$Sjt_MerchantID."\">";
		      		echo "<input type=\"hidden\" name=\"Sjt_Username\" value=\"".$Sjt_Username."\">";
		      		echo "<input type=\"hidden\" name=\"Sjt_TransID\" value=\"".$TransID."\">";
		      		echo "<input type=\"hidden\" name=\"Sjt_Return\" value=\"".$_Result."\">";
		      		echo "<input type=\"hidden\" name=\"Sjt_Error\" value=\"".$_resultDesc."\">";
		      		echo "<input type=\"hidden\" name=\"Sjt_factMoney\" value=\"".$tranAmt."\">";
		      		echo "<input type=\"hidden\" name=\"Sjt_SuccTime\" value=\"".$_SuccTime."\">";
		      		echo "<input type='hidden' name='Sjt_BType' value='1' />";
		      		echo "<input type=\"hidden\" name=\"Sjt_Sign\" value=\"".$Sjt_Md5Sign."\">";
		      		echo "</from>";
		      		echo "<script type=\"text/javascript\">";
		      		echo "document.Form1.submit();";
		      		echo "</script>";
		      	}else{
		      		$Sjt_Md5Sign = md5($Sjt_MerchantID.$Sjt_Username.$TransID.$_Result.$_resultDesc.$tranAmt.$_SuccTime."2".$keystr);
		      		$datastr = "Sjt_MerchantID=".$Sjt_MerchantID."&Sjt_Username=".$Sjt_Username."&Sjt_TransID=".$TransID."&Sjt_Return=".$_Result."&Sjt_Error=".$_resultDesc."&Sjt_factMoney=".$tranAmt."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
		      	
		      		$tjurl = $Sjt_Return_url."?".$datastr;
		      		//file_put_contents("zyzyzzy.txt",$tjurl."\n", FILE_APPEND);
		      		//////////////////////////////////////////////////////////////
		      		$Order = M("Order");
		      		$data = array();
		      		$data["budan"] = $tjurl;
		      		$Order->where("TransID = '".$TransID."'")->save($data);
		      		////////////////////////////////////////////////////////////
		      		$contents = fopen($tjurl,"r");
		      		$contents=fread($contents,4096);
		      		if(strtolower($contents) == "ok"){
		      			$Ordertz = M("Ordertz");
		      			$data["success"] = 1;
		      			$Ordertz->where("Sjt_TransID = '".$TransID."'")->save($data);
		      		}
		      	}
      }
      
      protected function bianliticheng($UserID,$tranAmt=0,$TransID,$num=1){    //遍历提成
			      	 
			      	$User = M("User");
			      	$sjUserID = $User->where("id=".$UserID)->getField("SjUserID");
			      	if($sjUserID){
					      		$Paycost = M("Paycost"); 
					      		$sjfl = $Paycost->where("UserID=".$sjUserID)->getField("wy");
					      		if($sjfl == 0){
					      			$sjfl = $Paycost->where("UserID=0")->getField("wy");
					      		}
					      		$fl = $Paycost->where("UserID=".$UserID)->getField("wy");
					      		if($fl == 0){
					      			$fl = $Paycost->where("UserID=0")->getField("wy");
					      		}
					      		$tcfl = (1-$fl)-(1-$sjfl);
					      		if($tcfl <= 0 || $tcfl >= 1){
					      			$tcfl = 0;
					      		}
					      		if($tcfl > 0){
							      			$tcmoney = $tcfl*$tranAmt;
							  
							      			$Money = D("Money");
							      			$sjY_Money = $Money->where("UserID=".$sjUserID)->getField("Money");
							      			//$sjtongdaoY_Money = $Money->where("UserID=".$sjUserID)->getField($tongdaoname);
							      
							      			$data["Money"] = $tcmoney + $sjY_Money;
							      			//$data[$tongdaoname] = $sjtongdaoY_Money + $tcmoney;
							      			$Money->where("UserID=".$sjUserID)->save($data); //更新上级账户金额

							      			##############################################################################################
							      			$Moneybd = M("Moneybd");
							      			$data["UserID"] = $sjUserID;
							      			$data["money"] = $tcmoney;
							      			$data["ymoney"] =  $sjY_Money;
							      			$data["gmoney"] = $tcmoney + $sjY_Money;
							      			$data["datetime"] = date("Y-m-d H:i:s");
							      			//$data["tongdao"] = $tongdaoname;
							      			$data["TransID"] = $TransID;
							      			$data["tcjb"] = $num;
							      			$data["lx"] = 7;
							      			$Moneybd->add($data);
							      			##############################################################################################
					      		}
			      		 
					      		$num = $num + 1;
					      
					      		$this->bianliticheng($sjUserID, $tranAmt,$TransID,$num);
      		 
			      		}
			      		 
			      		return "";
			      		 
      		}
        
     }
?>
