<?php
     class GameAction extends Action{
         
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
         
         protected $Sjt_PayID; //支付渠道
         
         protected $CardNO;     //卡号
         protected $CardPWD;     //充值卡密码
         
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
             
             $this->Sjt_Merchant_url = $this->_post("Sjt_Merchant_url");
             $this->Sjt_Return_url = $this->_post("Sjt_Return_url");
             if($this->Sjt_Merchant_url == NULL || $this->Sjt_Merchant_url == ""){  //盛捷通商户通知地址
                 $this->Sjt_Return = 0;
                 $this->Sjt_Error = 1;  //商户前台通知地址不能为空
                 $this->RunError();
             }
             
             
             $Sjt_MerchantID = $this->_post("Sjt_MerchantID");   //获取盛捷通商户号
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
             
             
             $this->Sjt_PayID = $this->_post("Sjt_PayID");   //获取盛捷通商户提交的支付渠道
             if($this->Sjt_PayID == NUll || $this->Sjt_PayID == ""){  //判断盛捷通商户提交的支付渠道字段是否存在
                    $this->Sjt_Return = 0;
                    $this->Sjt_Error = 4;  //支付渠道不能为空
                    $this->RunError();
              }else{
                  
                 $paytype = $this->_post("Sjt_Paytype");
                 if($paytype == "" || $paytype == NULL){
                      $this->Sjt_Return = 0;
                      $this->Sjt_Error = 11;   // 支付类型错误 
                      $this->RunError();
                 }
                 
                 if($paytype == "b"){
                     $Bankpay = M("Bankpay");
                     if($this->PayName == "yidong"){
                        $this->Sjt_PayID = $Bankpay->where("id=".$this->Sjt_PayID)->getField("10086"); 
                     }else{
                        $this->Sjt_PayID = $Bankpay->where("id=".$this->Sjt_PayID)->getField($this->PayName);    
                     }
                     
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
              
              
              $this->OrderMoney = $this->_post("Sjt_OrderMoney");  //获取盛捷通商户提交的订单金额
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
               
                if(strstr($yuming,$WebsiteUrl) == false && strstr($yuming,C("WEB_URL")) == false){    //判断提交的网址域名与用户设置的域名相同
                     $this->Sjt_Return = 0;
                     $this->Sjt_Error = 8; //提交的域名非法
                     $this->RunError();
                } 
                
                
               $Sjt_Key = $Userapiinformation->where("UserID=".$this->UserID)->getField("Key");   //获取用户的密钥
        
               if($Sjt_Key != $this->_post("Sjt_Key")){    //判断盛捷通商户提交的密钥是否正确
                    $this->Sjt_Return = 0;
                    $this->Sjt_Error = 9;   //密钥错误 
                    $this->RunError();
               }
               
               $this->Sjt_Return_url = $this->_post("Sjt_Return_url");      //盛捷通商户底层（后台）通知地址
                if($this->Sjt_Return_url == Null || $this->Sjt_Return_url == ""){
                   $this->Sjt_Return = 0;
                   $this->Sjt_Error = 10;
                   $this->ReturnUrl();
               }  
               
              $this->CardNO = $this->_post("CardNO");  //卡号
              $this->CardPWD = $this->_post("CardPWD");  //密码
              
              if($this->CardNO == Null || $this->CardNO == "" || $this->CardPWD == NULL || $this->CardPWD == ""){
                   $this->Sjt_Return = 0;
                   $this->Sjt_Error = 12;   //卡号或密码错误 
                   $this->ReturnUrl();
              }
             
             
         }
         
         
         protected function Orderadd(){   //添加订单
             
             
             $Order = M("Order");
             $id_id = $Order->order("id desc")->limit(1)->getField("id");
             $this->TransID = $this->Sjt_MerchantID.date("Ymd").(1000000000+$id_id);//流水号
             $Sjapi = M("Sjapi");
             $this->_MerchantID = $Sjapi->where("apiname='".$this->PayName."'")->getField("shid"); //商户ID
             $this->TransID = $this->_MerchantID.$this->TransID;
             
             //$this->sjt_OrderMoney=$this->Sjt_OrderMoney* $this->Paymoneyfen;//订单金额
             $this->ProductName=iconv('GB2312', 'UTF-8', $this->_post("Sjt_ProductName"));//产品名称
             $this->AdditionalInfo=iconv('GB2312', 'UTF-8', $this->_post("Sjt_AdditionalInfo"));//订单附加消息
             $this->Username = iconv('GB2312', 'UTF-8', $this->_post("Sjt_UserName"));
             
             $yuming = $_SERVER["HTTP_REFERER"];  //获取提交的网址的域名
             if(strstr($yuming,C("WEB_URL")) == true){
                 $this->Sjt_Merchant_url= "http://".C("WEB_URL")."/Payapi_".$this->PayName."_MerChantUrl.html";
                 $this->Sjt_Return_url= "http://".C("WEB_URL")."/Payapi_".$this->PayName."_ReturnUrl.html"; 
              }
             
        
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
              
              $data["OrderMoney"] = $jiaoyijine;     //实际订单金额
              $data["trademoney"] = $this->OrderMoney;   //交易金额
              $data["sxfmoney"] = $this->OrderMoney - $jiaoyijine; //手续费
              $data["ProductName"] = $this->ProductName;     //商品名称
              $data["Username"] = $this->Username;     //支付用户名
              $data["AdditionalInfo"] = $this->AdditionalInfo;   //订单附加信息
              $data["Sjt_Merchant_url"] = $this->Sjt_Merchant_url;     //跳转地址
              $data["Sjt_Return_url"] = $this->Sjt_Return_url;    //通知地址
              $data["tjurl"] = $_SERVER["HTTP_REFERER"];  
              $data["typepay"] = 2;
              $data["CardNO"] = $this->CardNO;
              $data["CardPWD"] = $this->CardPWD;
            
              $Order->add($data);
             
         }
         
         protected function RunError(){    //返回错误
             
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
        
        
        
     }
?>
