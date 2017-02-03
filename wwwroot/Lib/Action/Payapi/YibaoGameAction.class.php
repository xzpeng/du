<?php
  class YibaoGameAction extends PayAction{
      
      public function Post(){
           
           $this->PayName = "Yibao";
           $this->TradeDate = date("YmdHis");
           $this->Paymoneyfen = 1;
           $this->check();
           $this->Orderadd();
           
           //$tjurl = "https://www.yeepay.com/app-merchant-proxy/node";
           $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_YibaoGame_MerChantUrl.html";      //商户通知地址
           $this->_Return_url= "http://".C("WEB_URL")."/Payapi_YibaoGame_ReturnUrl.html";   //用户通知地址
            
           $Sjapi = M("Sjapi");
           $this->_MerchantID = $Sjapi->where("apiname='".$this->PayName."'")->getField("shid"); //商户ID
           $this->_Md5Key = $Sjapi->where("apiname='".$this->PayName."'")->getField("key"); //密钥   
           $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
          //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
           #商家设置用户购买商品的支付信息.
            #商户订单号.提交的订单号必须在自身账户交易中唯一.
            $p2_Order            = $this->TransID;
            #支付卡面额   （订单金额）
            $p3_Amt                = $this->sjt_OrderMoney;
            #是否较验订单金额
            $p4_verifyAmt        = true;
            #产品名称
            $p5_Pid                = "xyzf";
            #iconv("UTF-8","GBK//TRANSLIT",$_POST['p5_Pid']);
            #产品类型
            $p6_Pcat            = "xyzf";
            #iconv("UTF-8","GBK//TRANSLIT",$_POST['p6_Pcat']);
            #产品描述
            $p7_Pdesc            = "xyzf";
            #iconv("UTF-8","GBK//TRANSLIT",$_POST['p7_Pdesc']);
            #商户接收交易结果通知的地址,易宝支付主动发送支付结果(服务器点对点通讯).通知会通过HTTP协议以GET方式到该地址上.    
            $p8_Url                = $this->_Return_url;
            #临时信息
            $pa_MP                = "xyzf";
            #iconv("UTF-8","GB2312//TRANSLIT",$_POST['pa_MP']);
            #卡面额
            $pa7_cardAmt            = $this->sjt_OrderMoney;
            #支付卡序列号.
            $pa8_cardNo            = $this->CardNO;
            #支付卡密码.
            $pa9_cardPwd            = $this->CardPWD;
            #支付通道编码
            $pd_FrpId            = $this->Sjt_PayID;
            #应答机制
            $pr_NeedResponse        = "1";
            #用户唯一标识
            $pz_userId            = "xyzf";
            #用户的注册时间
            $pz1_userRegTime        = date("Y-m-d H:i:s",time());
            
    #非银行卡支付专业版测试时调用的方法，在测试环境下调试通过后，请调用正式方法annulCard
    #两个方法所需参数一样，所以只需要将方法名改为annulCard即可
    #测试通过，正式上线时请调用该方法
    //exit($this->TransID."--------------".$this->sjt_OrderMoney."--------".$this->CardNO."---------".$this->CardPWD."-----------".$this->_Return_url);
    $this->annulCard($p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pz_userId,$pz1_userRegTime);
            
      }
      
      
      public function ReturnUrl(){
          //echo "dsgsagsadg";
          ######################################################################################################################################
                      $return =$this->getCallBackValue($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,
            $p9_MP,$pb_BalanceAmt,$pc_BalanceAct,$hmac);
                #    判断返回签名是否正确（True/False）
                $bRet = $this->CheckHmac($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,
            $p9_MP,$pb_BalanceAmt,$pc_BalanceAct,$hmac);
                #    以上代码和变量不需要修改.
                         
                #    校验码正确.
                if($bRet){
                    echo "success";
                    //////////////////////////////////////////////////////////////////////////////////////////
                    $Order = D("Order");
                $UserID = $Order->where("TransID = '".$p2_Order."'")->getField("UserID");
                
                //通知跳转页面
                //$Sjt_Merchant_url = $Order->where("TransID = '".$p2_Order."'")->getField("Sjt_Merchant_url"); 
                //后台通知地址
                $Sjt_Return_url = $Order->where("TransID = '".$p2_Order."'")->getField("Sjt_Return_url");
                //盛捷通商户ID
                $Sjt_MerchantID = $Order->where("TransID = '".$p2_Order."'")->getField("UserID");
                 //在商户网站冲值的用户的用户名
                $Sjt_Username = $Order->where("TransID = '".$p2_Order."'")->getField("Username");
                
                $OrderMoney = $Order->where("TransID = '".$p2_Order."'")->getField("OrderMoney");
                
                $Sjt_Zt = $Order->where("TransID = '".$p2_Order."'")->getField("Zt");  
                
              $typepay = $Order->where("TransID = '".$p2_Order."'")->getField("typepay");
              
              $payname = $Order->where("TransID = '".$p2_Order."'")->getField("payname");
              //////////////////////////////////////////////////////////////////////
                    #在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理
                      if($r1_Code=="1"){
                          ///////////////////////////////////////////////////////////////////////////////////////////
                           //////////////////////////////////////////////////////////////////////
                     $Paycost = M("Paycost");
                      $Sjfl = M("Sjfl");
                      if($typepay == 0 || $typepay == 1){
                          $fv = $Paycost->where("UserID=".$UserID)->getField("wy");
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField("wy");
                          }
                          
                  $sjflmoney = $Sjfl->where("jkname='yibao'")->getField("wy"); //上家费率 
                      }else{
                          $ywm = $this->dkname($payname);
                           $fv = $Paycost->where("UserID=".$UserID)->getField($ywm);
                          if($fv == 0){
                              $fv = $Paycost->where("UserID=0")->getField($ywm);
                          }
                          
                  $sjflmoney = $Sjfl->where("jkname='yibao'")->getField($ywm); //上家费率       
                      }
                      
                  if($sjflmoney == 0){
                     $sjflmoney = 1;
                  }
                  //////////////////////////////////////////////////////////////////////
                $Userapiinformation = D("Userapiinformation");
                
                $Sjt_Key = $Userapiinformation->where("UserID=".$UserID)->getField("Key");
                
                 if($Sjt_Zt == 0){     //如果订单是未处理将金额加上并改为已处理订单
                   $Money = D("Money");
                   $Y_Money = $Money->where("UserID=".$UserID)->getField("Money");
                   //$OrderMoney = $money * $fv; //实际金额
                   $data["Money"] = $OrderMoney + $Y_Money;
                   $Money->where("UserID=".$UserID)->save($data); //更新盛捷通账户金额
                   
             ///////////////////////////////////////////////////////////
                   $Moneydb = M("Moneydb");
                   $data["UserID"] = $UserID;
                   $data["money"] = $OrderMoney;
                   $data["ymoney"] =  $Y_Money;
                   $data["gmoney"] = $Y_Money + $OrderMoney;
                   $data["datetime"] = date("Y-m-d H:i:s");
                   $data["lx"] = 2;
                   $Moneydb->add($data);
                   //////////////////////////////////////////////////////////
                   
                   
                   $data["Zt"] = 1;   
                   //$data["TradeDate"] = date("Y-m-d h:i:s");  
                  // $jiaoyijine = $money * $fv;
                  // $data["OrderMoney"] = $jiaoyijine;     //实际订单金额
                  //// $data["trademoney"] = $money;   //交易金额
                 //  $data["sxfmoney"] = $money - $jiaoyijine; //手续费
                 //  $data["sjflmoney"] = $money - $money * $sjflmoney; //上家手续费
                   $Order->where("TransID='".$p2_Order."'")->save($data); //将订单设置为成功     
                   
                }
                
                ////////////////////////////////////////////////////////////////////////////
                $Ordertz = M("Ordertz");
                $ordertzlist = $Ordertz->where("Sjt_TransID = '".$p2_Order."'")->select();
                $returncode = "00";
                $errtype = "00";
               if(!$ordertzlist){
                   $data["Sjt_MerchantID"] = $userid;
                   $data["Sjt_UserName"] = $Sjt_Username;
                   $data["Sjt_TransID"] = $p2_Order;
                   $data["Sjt_Return"] = $returncode;
                   $data["Sjt_Error"] = $errtype;
                   $_factMoney = number_format($OrderMoney,3);
                   $data["Sjt_factMoney"] = $_factMoney;
                   $_SuccTime = date("Ymdhis");
                   $data["Sjt_SuccTime"] = $_SuccTime;
                   $Sjt_Md5Sign = md5($userid.$Sjt_Username.$orderid.$returncode.$errtype.$_factMoney.$_SuccTime."2".$Sjt_Key);
                   $data["Sjt_Sign"] = $Sjt_Md5Sign;
                   $data["Sjt_urlname"] = $Sjt_Return_url;
                   $data["Sjt_BType"] = 2;
                   $Ordertz->add($data);
               }
               
                 $datastr = "Sjt_MerchantID=".$userid."&Sjt_UserName=".$Sjt_Username."&Sjt_TransID=".$p2_Order."&Sjt_Return=".$returncode."&Sjt_Error=".$errtype."&Sjt_factMoney=".$_factMoney."&Sjt_SuccTime=".$_SuccTime."&Sjt_BType=2&Sjt_Sign=".$Sjt_Md5Sign;
               $tjurl = $Sjt_Return_url."?".$datastr; 
               $contents = fopen($tjurl,"r"); 
               $contents=fread($contents,4096); 
               if($contents == "ok"){
                 $data["success"] = 1;
                 $Ordertz->where("Sjt_TransID = '".$p2_Order."'")->save($data);
               }else{
                  // $data["Sjt_UserName"] = $contents;
                  // $Ordertz->where("Sjt_TransID = '".$this->_post("out_trade_no")."'")->save($data);
               }
                
                          ///////////////////////////////////////////////////////////////////////////////////////////
                      } else if($r1_Code=="2"){
                         /////////////////////////////////////////////////////////////////////////////
                          $Order = D("Order");
                          $Sjt_MerchantID = $Order->where("TransID = '".$p2_Order."'")->getField("UserID");
                             
                          $Ordertz = M("Ordertz");
                          $ordertzlist = $Ordertz->where("Sjt_TransID = '".$p2_Order."'")->select();
                          if(!$ordertzlist){
                               $data["Sjt_MerchantID"] = $UserID;
                               //$data["Sjt_UserName"] = $Sjt_Username;
                               $data["Sjt_TransID"] = $p2_Order;
                               $data["Sjt_Return"] =  $p8_cardStatus;
                               $data["Sjt_Error"] = $p8_cardStatus;
                               $data["success "] = 2;
                               $Ordertz->add($data);
                          }
                         ////////////////////////////////////////////////////////////////////////////
                      }
                    } else{
                    
                $sNewString = $this->getCallbackHmacString($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,
                $p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct);
                        echo "<br>localhost:".$sNewString;    
                        echo "<br>YeePay:".$hmac;
                        echo "<br>交易签名无效!";
                        exit; 
                }
          ######################################################################################################################################
    
      }
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      function getReqHmacString($p0_Cmd,$p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pr_NeedResponse,$pz_userId,$pz1_userRegTime)
{
    
   //* 商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得*/
      $Sjapi = M("Sjapi");
      $p1_MerId = $Sjapi->where("apiname='yibao'")->getField("shid"); //商户ID
      $merchantKey = $Sjapi->where("apiname='yibao'")->getField("key"); //密钥   
      $logName    = "YeePay_CARD.log";
      $reqURL_SNDApro        = "https://www.yeepay.com/app-merchant-proxy/command.action";
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    
    #进行加密串处理，一定按照下列顺序进行
    $sbOld        =    "";
    #加入业务类型
    $sbOld        =    $sbOld.$p0_Cmd;
    #加入商户代码
    $sbOld        =    $sbOld.$p1_MerId;
    #加入商户订单号
    $sbOld        =    $sbOld.$p2_Order;
    #加入支付卡面额
    $sbOld        =    $sbOld.$p3_Amt;
    #是否较验订单金额
    $sbOld        =    $sbOld.$p4_verifyAmt;
    #产品名称
    $sbOld        =    $sbOld.$p5_Pid;
    #产品类型
    $sbOld        =    $sbOld.$p6_Pcat;
    #产品描述
    $sbOld        =    $sbOld.$p7_Pdesc;
    #加入商户接收交易结果通知的地址
    $sbOld        =    $sbOld.$p8_Url;
    #加入临时信息
    $sbOld         = $sbOld.$pa_MP;
    #加入卡面额组
    $sbOld         = $sbOld.$pa7_cardAmt;
    #加入卡号组
    $sbOld        =    $sbOld.$pa8_cardNo;
    #加入卡密组
    $sbOld        =    $sbOld.$pa9_cardPwd;
    #加入支付通道编码
    $sbOld        =    $sbOld.$pd_FrpId;
    #加入应答机制
    $sbOld        =    $sbOld.$pr_NeedResponse;
    #加入用户ID
    $sbOld        =    $sbOld.$pz_userId;
    #加入用户注册时间
    $sbOld        =    $sbOld.$pz1_userRegTime;
    #echo "localhost:".$sbOld;

    $this->logstr($p2_Order,$sbOld,$this->HmacMd5($sbOld,$merchantKey),$merchantKey);
    return $this->HmacMd5($sbOld,$merchantKey);
    
} 


function annulCard($p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pz_userId,$pz1_userRegTime)
{
    
    //* 商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得*/
      $Sjapi = M("Sjapi");
      $p1_MerId = $Sjapi->where("apiname='yibao'")->getField("shid"); //商户ID
      $merchantKey = $Sjapi->where("apiname='yibao'")->getField("key"); //密钥   
      $logName    = "YeePay_CARD.log";
      $reqURL_SNDApro        = "https://www.yeepay.com/app-merchant-proxy/command.action";
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
   // include_once 'HttpClient.class.php';
    import("@.Action.HttpClient");
    //$HttpClient = new HttpClient();
     
    # 非银行卡支付专业版支付请求，固定值 "ChargeCardDirect".        
    $p0_Cmd                    = "ChargeCardDirect";

    #应答机制.为"1": 需要应答机制;为"0": 不需要应答机制.            
    $pr_NeedResponse    = "1";
    
    #调用签名函数生成签名串
    $hmac    = $this->getReqHmacString($p0_Cmd,$p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pr_NeedResponse,$pz_userId,$pz1_userRegTime);
    
    #进行加密串处理，一定按照下列顺序进行
    $params = array(
        #加入业务类型
        'p0_Cmd'                        =>    $p0_Cmd,
        #加入商家ID
        'p1_MerId'                    =>    $p1_MerId,
        #加入商户订单号
        'p2_Order'                     =>    $p2_Order,
        #加入支付卡面额
        'p3_Amt'                        =>    $p3_Amt,
        #加入是否较验订单金额
        'p4_verifyAmt'                        =>    $p4_verifyAmt,
        #加入产品名称
        'p5_Pid'                        =>    $p5_Pid,
        #加入产品类型
        'p6_Pcat'                        =>    $p6_Pcat,
        #加入产品描述
        'p7_Pdesc'                        =>    $p7_Pdesc,
        #加入商户接收交易结果通知的地址
        'p8_Url'                        =>    $p8_Url,
        #加入临时信息
        'pa_MP'                          =>     $pa_MP,
        #加入卡面额组
        'pa7_cardAmt'                =>    $pa7_cardAmt,
        #加入卡号组
        'pa8_cardNo'                =>    $pa8_cardNo,
        #加入卡密组
        'pa9_cardPwd'                =>    $pa9_cardPwd,
        #加入支付通道编码
        'pd_FrpId'                    =>    $pd_FrpId,
        #加入应答机制
        'pr_NeedResponse'        =>    $pr_NeedResponse,
        #加入校验码
        'hmac'                             =>    $hmac,
        #用户唯一标识
        'pz_userId'            =>    $pz_userId,
        #用户的注册时间
        'pz1_userRegTime'         =>    $pz1_userRegTime
        );

    $pageContents    = HttpClient::quickPost($reqURL_SNDApro, $params);
   // echo "pageContents:".$this->TransCode($pageContents);
    $result                 = explode("\n",$pageContents);
    
    $r0_Cmd                =    "";                            #业务类型
    $r1_Code            =    "";                            #支付结果
    $r2_TrxId            =    "";                            #易宝支付交易流水号
    $r6_Order            =    "";                            #商户订单号
    $rq_ReturnMsg    =    "";                            #返回信息
    $hmac                    =    "";                           #签名数据
  $unkonw                = "";                            #未知错误      


    for($index=0;$index<count($result);$index++){        //数组循环
        $result[$index] = trim($result[$index]);
        if (strlen($result[$index]) == 0) {
            continue;
        }
        $aryReturn        = explode("=",$result[$index]);
        $sKey                    = $aryReturn[0];
        $sValue                = $aryReturn[1];
        if($sKey            =="r0_Cmd"){                #取得业务类型  
            $r0_Cmd                = $sValue;
        }elseif($sKey == "r1_Code"){                    #取得支付结果
            $r1_Code            = $sValue;
        }elseif($sKey == "r2_TrxId"){                    #取得易宝支付交易流水号
            $r2_TrxId            = $sValue;
        }elseif($sKey == "r6_Order"){                    #取得商户订单号
            $r6_Order            = $sValue;
        }elseif($sKey == "rq_ReturnMsg"){                #取得交易结果返回信息
            $rq_ReturnMsg    = $sValue;
        }elseif($sKey == "hmac"){                        #取得签名数据
            $hmac                 = $sValue;          
        } else{
            return $result[$index];
        }
    }
    

    #进行校验码检查 取得加密前的字符串
    $sbOld="";
    #加入业务类型
    $sbOld = $sbOld.$r0_Cmd;                
    #加入支付结果
    $sbOld = $sbOld.$r1_Code;
    #加入易宝支付交易流水号
    #$sbOld = $sbOld.$r2_TrxId;                
    #加入商户订单号
    $sbOld = $sbOld.$r6_Order;                
    #加入交易结果返回信息
    $sbOld = $sbOld.$rq_ReturnMsg;                   
    $sNewString = $this->HmacMd5($sbOld,$merchantKey);      
  $this->logstr($r6_Order,$sbOld,$this->HmacMd5($sbOld,$merchantKey),$merchantKey);
    
    #校验码正确
    if($sNewString==$hmac) {
        if($r1_Code=="1"){
               //echo "<br>提交成功!".$rq_ReturnMsg;
            //  echo "<br>商户订单号:".$r6_Order."<br>";
              #echo generationTestCallback($p2_Order,$p3_Amt,$p8_Url,$pa7_cardNo,$pa8_cardPwd,$pz_userId,$pz1_userRegTime);
              sleep(5);
              echo "ok&".$p2_Order;
              return; 
        } else if($r1_Code=="2"){
             // echo "<br>提交失败".$rq_ReturnMsg;
              echo $this->TransCode("提交失败，支付卡密无效!");
              return; 
        } else if($r1_Code=="7"){
             // echo "<br>提交失败".$rq_ReturnMsg;
              echo $this->TransCode("提交失败，支付卡密无效!");
              return; 
        } else if($r1_Code=="11"){
             // echo "<br>提交失败".$rq_ReturnMsg;
              echo $this->TransCode("提交失败，订单号重复!");
              return; 
        } else{
               // echo $this->TransCode("<br>提交失败".$rq_ReturnMsg."<br>");
              echo $this->TransCode("提交失败，请检查后重新支付".$p2_Order);    
              return;       
        }
    } else{
        echo "<br>localhost:".$sNewString;    
        echo "<br>YeePay:".$hmac;
        echo "<br>交易签名无效!";
        exit; 
    }
}

function generationTestCallback($p2_Order,$p3_Amt,$p8_Url,$pa7_cardNo,$pa8_cardPwd,$pa_MP,$pz_userId,$pz1_userRegTime)
{
    
   //* 商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得*/
      $Sjapi = M("Sjapi");
      $p1_MerId = $Sjapi->where("apiname='yibao'")->getField("shid"); //商户ID
      $merchantKey = $Sjapi->where("apiname='yibao'")->getField("key"); //密钥   
      $logName    = "YeePay_CARD.log";
      $reqURL_SNDApro        = "https://www.yeepay.com/app-merchant-proxy/command.action";
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    import("@.Action.HttpClient");
    //$HttpClient = new HttpClient();
     
    # 非银行卡支付专业版支付请求，固定值 "AnnulCard".        
    $p0_Cmd                    = "AnnulCard";

    #应答机制.为"1": 需要应答机制;为"0": 不需要应答机制.            
    $pr_NeedResponse    = "1";
    
    # 非银行卡支付专业版请求地址,无需更改.
    $reqURL_SNDApro        = "https://www.yeepay.com/app-merchant-proxy/command.action";
   // $reqURL_SNDApro        = "http://tech.yeepay.com:8080/robot/generationCallback.action";
    #调用签名函数生成签名串
    #$hmac    = getReqHmacString($p0_Cmd,$p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pr_NeedResponse,$pz_userId,$pz1_userRegTime);
    #进行加密串处理，一定按照下列顺序进行
    $params = array(
        #加入业务类型
        'p0_Cmd'                        =>    $p0_Cmd,
        #加入商家ID
        'p1_MerId'                    =>    $p1_MerId,
        #加入商户订单号
        'p2_Order'                     =>    $p2_Order,
        #加入支付卡面额
        'p3_Amt'                        =>    $p3_Amt,
        #加入商户接收交易结果通知的地址
        'p8_Url'                        =>    $p8_Url,
        #加入支付卡序列号
        'pa7_cardNo'                =>    $pa7_cardNo,
        #加入支付卡密码
        'pa8_cardPwd'                =>    $pa8_cardPwd,
        #加入支付通道编码
        'pd_FrpId'                    =>    $pd_FrpId,
        #加入应答机制
        'pr_NeedResponse'        =>    $pr_NeedResponse,
        #加入应答机制
        'pa_MP'                            =>    $pa_MP,
        #用户唯一标识
        'pz_userId'            =>    $pz_userId,
        #用户的注册时间
        'pz1_userRegTime'         =>    $pz1_userRegTime);
    
    $pageContents    = HttpClient::quickPost($reqURL_SNDApro, $params);
    return $pageContents;
}


#调用签名函数生成签名串.
function getCallbackHmacString($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,
$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct)
{

     //* 商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得*/
      $Sjapi = M("Sjapi");
      $p1_MerId = $Sjapi->where("apiname='yibao'")->getField("shid"); //商户ID
      $merchantKey = $Sjapi->where("apiname='yibao'")->getField("key"); //密钥   
      $logName    = "YeePay_CARD.log";
      $reqURL_SNDApro        = "https://www.yeepay.com/app-merchant-proxy/command.action";
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
 
  

    #进行校验码检查 取得加密前的字符串
    $sbOld="";
    #加入业务类型
    $sbOld = $sbOld.$r0_Cmd;
    $sbOld = $sbOld.$r1_Code;
    $sbOld = $sbOld.$p1_MerId;
    $sbOld = $sbOld.$p2_Order;
    $sbOld = $sbOld.$p3_Amt;
    $sbOld = $sbOld.$p4_FrpId;
    $sbOld = $sbOld.$p5_CardNo;
    $sbOld = $sbOld.$p6_confirmAmount;
    $sbOld = $sbOld.$p7_realAmount;
    $sbOld = $sbOld.$p8_cardStatus;
    $sbOld = $sbOld.$p9_MP;
    $sbOld = $sbOld.$pb_BalanceAmt;
    $sbOld = $sbOld.$pc_BalanceAct;              
                
    #echo "[".$sbOld."]";
  $this->logstr($p2_Order,$sbOld,$this->HmacMd5($sbOld,$merchantKey),$merchantKey);
    return $this->HmacMd5($sbOld,$merchantKey);

}


#取得返回串中的所有参数.
function getCallBackValue(&$r0_Cmd,&$r1_Code,&$p1_MerId,&$p2_Order,&$p3_Amt,&$p4_FrpId,&$p5_CardNo,&$p6_confirmAmount,&$p7_realAmount,
&$p8_cardStatus,&$p9_MP,&$pb_BalanceAmt,&$pc_BalanceAct,&$hmac)
{  

$r0_Cmd = $_REQUEST['r0_Cmd'];
$r1_Code = $_REQUEST['r1_Code'];
$p1_MerId = $_REQUEST['p1_MerId'];
$p2_Order = $_REQUEST['p2_Order'];
$p3_Amt = $_REQUEST['p3_Amt'];
$p4_FrpId = $_REQUEST['p4_FrpId'];
$p5_CardNo = $_REQUEST['p5_CardNo'];
$p6_confirmAmount = $_REQUEST['p6_confirmAmount'];
$p7_realAmount = $_REQUEST['p7_realAmount'];
$p8_cardStatus = $_REQUEST['p8_cardStatus'];
$p9_MP = $_REQUEST['p9_MP'];
$pb_BalanceAmt = $_REQUEST['pb_BalanceAmt'];
$pc_BalanceAct = $_REQUEST['pc_BalanceAct'];
$hmac = $_REQUEST['hmac'];
    
return null;
    
}


#验证返回参数中的hmac与商户端生成的hmac是否一致.
function CheckHmac($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,
$pc_BalanceAct,$hmac)
{
    if($hmac==$this->getCallbackHmacString($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,
    $p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct))
        return true;
    else
        return false;
        
}

  
function HmacMd5($data,$key)           
{                                      
    # RFC 2104 HMAC implementation for php.
    # Creates an md5 HMAC.                 
    # Eliminates the need to install mhash to compute a HMAC
    # Hacked by Lance Rushing(NOTE: Hacked means written)
                                           
    #需要配置环境支持iconv，否则中文参数不能正常处理
    $key = iconv("GBK","UTF-8",$key);  
    $data = iconv("GBK","UTF-8",$data);
                                           
    $b = 64; # byte length for md5         
    if (strlen($key) > $b) {               
    $key = pack("H*",md5($key));           
    }                                      
    $key = str_pad($key, $b, chr(0x00));   
    $ipad = str_pad('', $b, chr(0x36));    
    $opad = str_pad('', $b, chr(0x5c));    
    $k_ipad = $key ^ $ipad ;               
    $k_opad = $key ^ $opad;                
                                           
    return md5($k_opad . pack("H*",md5($k_ipad . $data)));
    
}
function logstr($orderid,$str,$hmac,$keyValue)
{
 //* 商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得*/
      $Sjapi = M("Sjapi");
      $p1_MerId = $Sjapi->where("apiname='yibao'")->getField("shid"); //商户ID
      $merchantKey = $Sjapi->where("apiname='yibao'")->getField("key"); //密钥   
      $logName    = "YeePay_CARD.log";
      $reqURL_SNDApro        = "https://www.yeepay.com/app-merchant-proxy/command.action";
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
$james=fopen($logName,"a+");
fwrite($james,"\r\n".date("Y-m-d H:i:s")."|orderid[".$orderid."]|str[".$str."]|hmac[".$hmac."]|keyValue[".$keyValue."]");
fclose($james);

}

function arrToString($arr,$Separators)
{
    $returnString = "";
    foreach ($arr as $value) {
            $returnString = $returnString.$value.$Separators;
    }
    return substr($returnString,0,strlen($returnString)-strlen($Separators));
}

function arrToStringDefault($arr)
{
    return $this->arrToString($arr,",");
}
  
  private function TransCode($Code){     //中文转码
           return iconv("GBK", "UTF-8", $Code);
      }     

  }
  
?>
