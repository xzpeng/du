<?php

  class WyzxAction extends PayAction{
      
      public function Post(){
             $this->PayName = "Wyzx";
            $this->TradeDate = date("YmdHis");
            $this->Paymoneyfen = 1;
            $this->check();
            $this->Orderadd();
            
            
            $tjurl = "https://pay.lefu8.com/gateway/trade.htm";
            $NoticeType = "";
            
            $this->_Merchant_url= "http://".C("WEB_URL")."/Payapi_Wyzx_MerChantUrl.html";      //商户通知地址
        
            $this->_Return_url= "http://".C("WEB_URL")."/Payapi_Wyzx_ReturnUrl.html";   //用户通知地址
            
             $Sjapi = M("Sjapi");
             $this->_MerchantID = $Sjapi->where("apiname='wyzx'")->getField("shid"); //商户ID
             $this->_Md5Key = $Sjapi->where("apiname='wyzx'")->getField("key"); //密钥   
             $this->sjt_OrderMoney=floatval($this->OrderMoney)*floatval($this->Paymoneyfen);//订单金额
             ////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //****************************************
                $v_mid = $this->_MerchantID; // 商户号，这里为测试商户号1001，替换为自己的商户号(老版商户号为4位或5位,新版为8位)即可
                $v_url = $this->_Merchant_url;    // 请填写返回url,地址应为绝对路径,带有http协议
                $key   = $this->_Md5Key;   
                
                 $v_oid = $this->TransID;
                 $v_amount = $this->sjt_OrderMoney;                   //支付金额                 
                 $v_moneytype = "CNY";                                            //币种

                 $text = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;        //md5加密拼凑串,注意顺序不能变
                 $v_md5info = strtoupper(md5($text));                             //md5函数加密并转化成大写字母

                 $remark1 = '';                     //备注字段1
                 $remark2 = '';                    //备注字段2                                
//****************************************
  ?>
<form method="post" id="Form1" name="Form1" action="https://pay3.chinabank.com.cn/PayGate">
    <input type="hidden" name="v_mid"         value="<?php echo $v_mid;?>">
    <input type="hidden" name="v_oid"         value="<?php echo $v_oid;?>">
    <input type="hidden" name="v_amount"      value="<?php echo $v_amount;?>">
    <input type="hidden" name="v_moneytype"   value="<?php echo $v_moneytype;?>">
    <input type="hidden" name="v_url"         value="<?php echo $v_url;?>">
    <input type="hidden" name="v_md5info"     value="<?php echo $v_md5info;?>">
 
 <!--以下几项项为网上支付完成后，随支付反馈信息一同传给信息接收页 -->    
    
    <input type="hidden" name="remark1"       value="<?php echo $remark1;?>">
    <input type="hidden" name="remark2"       value="<?php echo $remark2;?>">
</form>
  <?php     
         echo "正在处理中......";
         $this->Echots();             
      }
      
      
      public function MerChantUrl(){
          ##############################################################################################################
                   $Sjapi = M("Sjapi");
        $_Md5Key = $Sjapi->where("apiname='wyzx'")->getField("key"); //密钥   
           //MD5私钥
    $key = $_Md5Key;

  
    
    #######################################################################################
    $v_oid     =trim($_POST['v_oid']);       // 商户发送的v_oid定单编号   
    $v_pmode   =trim($_POST['v_pmode']);    // 支付方式（字符串）   
    $v_pstatus =trim($_POST['v_pstatus']);   //  支付状态 ：20（支付成功）；30（支付失败）
    $v_pstring =trim($_POST['v_pstring']);   // 支付结果信息 ： 支付完成（当v_pstatus=20时）；失败原因（当v_pstatus=30时,字符串）； 
    $v_amount  =trim($_POST['v_amount']);     // 订单实际支付金额
    $v_moneytype  =trim($_POST['v_moneytype']); //订单实际支付币种    
    $remark1   =trim($_POST['remark1' ]);      //备注字段1
    $remark2   =trim($_POST['remark2' ]);     //备注字段2
    $v_md5str  =trim($_POST['v_md5str' ]);   //拼凑后的MD5校验值  

    /**
     * 重新计算md5的值
     */
                               
    $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));

    /**
     * 判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理
     */

    #######################################################################################
    
    
     if ($v_md5str==$md5string){  
           if ($v_pstatus != "20"){
                 $this->Sjt_Return = 0;
                $this->Sjt_Error = "";
                $Order = D("Order");    
                $this->Sjt_Merchant_url = $Order->where("TransID = '".$v_oid."'")->getField("Sjt_Merchant_url");
                $this->RunError();
           }else{
               ////////////////////////////////////////////////////////////////////////////////////////////////
              $this->Tongdao($v_oid,'wyzx',0);
               ///////////////////////////////////////////////////////////////////////////////////////////////
           }
     }else{
            $Order = D("Order"); 
            $Sjt_Merchant_url = $Order->where("TransID = '".$_TransID."'")->getField("Sjt_Merchant_url");
            $this->Sjt_Return = 0;
            $this->Sjt_Error =  "9======".$SignMD5info."====".$md5sign;  //密钥错误
            $this->Sjt_Merchant_url = $Sjt_Merchant_url;
            $this->RunError();
     }
    
          #############################################################################################################
      }   
      
      public function ReturnUrl(){
     
    
    
      } 
      
       public function sqlexecute(){
            
                  $Model = M(); 
                  $Model->execute("ALTER TABLE `pay_money`  ADD COLUMN `wyzx` decimal(15,3) NULL DEFAULT '0';");  
                  $Model->execute("ALTER TABLE `pay_paycost`  ADD COLUMN `wyzx` decimal(10,4) NULL DEFAULT '0';");          
                  $Model->execute("insert into pay_sjapi(apiname,myname,payname) values('wyzx','网银在线','Wyzx');");
                  $Model->execute("insert into pay_tkfl(tongdao,UserID) values('wyzx',0);");
                  $Model->execute("ALTER TABLE `pay_bankpay`  ADD COLUMN `wyzx` varchar(100);"); 
                 
                  $Model->execute("update pay_bankpay set huicao = 'CMB' where Sjt = ''");
                  $Model->execute("update pay_bankpay set huicao = 'ICBC' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'CCB' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'SPDB' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'ABC' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'CMBC' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = '' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'CIB' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'BOCOM' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'CEB' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'BOCSH' where Sjt = ''");
                  $Model->execute("update pay_bankpay set huicao = 'PAB' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'GDB' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'CNCB' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'PSBC' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'BCCB' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = 'BOS' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = '' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = '' where Sjt = ''"); 
                  $Model->execute("update pay_bankpay set huicao = '' where Sjt = ''"); 
                  
                  exit("ok");
                    
              }
      
      
  }
?>

