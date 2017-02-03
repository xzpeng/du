<?php
    include_once("system/config.php");
    require_once '../utils/Log.php';
    require_once '../services/Services.php';
    /**
    * @author Jupiter
    *
    * 前台通知接口
    *
    * 用于被动接收中小开发者支付系统发过来的通知信息，并对通知进行验证签名，
    * 签名验证通过后，商户可对数据进行处理。(交易状态以后台通知为准)
    *
    * 说明:以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己的需要，按照技术文档编写，并非一定要使用该代码。该代码仅供参考
    */
    $response="";
    foreach($_GET as $key=>$value){
    $response.=$key."=".$value."&";
    }
    Log::outLog("网银前台通知接口", $response);
    if (Services::verifySignature($_GET)){
        $tradeStatus=$_GET['tradeStatus'];
        if($tradeStatus!=""&&$tradeStatus=="A001"){
     //处理订单开始
	$how=gcs("pay_Order"," where TransID='$_GET[mhtOrderNo]'");
	$TransID=$how['mhtOrderNo'];
	$UserID=$how['UserID'];
	$Sjt_Return_url=$how['Sjt_Return_url'];
	$Sjt_Merchant_url=$how['Sjt_Merchant_url'];
	$Sjt_MerchantID=$how['UserID'];
	$Sjt_Username=$how['Username'];
	$OrderMoney=$how['OrderMoney'];
	$trademoney=$how['trademoney'];
	$tranAmt=$how['trademoney'];
	$typepay=$how['typepay'];
	$payname=$how['payname'];
	$Sjt_Zt=$how['Zt'];
	$datatime_datetime = date("Y-m-d H:i:s");
	$gmoney = $Y_Money + $OrderMoney;
	if($Sjt_Zt == 0){
	$db->query("update pay_Order set Zt=1 where TransID='$TransID'");
	//资金变动记录
	$db->query("update pay_money set money=money+'$OrderMoney' where UserID='$UserID'");
	//资金记录变动日志
	$db->query("insert into pay_Moneybd(UserID,money,ymoney,gmoney,datetime,TransID,lx) values ('$UserID','$OrderMoney','$Y_Money','$gmoney','$datatime_datetime','$TransID',1)");
	}	file_put_contents("Lib/Action/Payapi/jij.txt",$Sjt_Merchant_url."-$TransID",FILE_APPEND);
	$url="$Sjt_Merchant_url"."?&Sjt_TransID=$TransID";
	file_put_contents("Lib/Action/Payapi/jij.txt",$url."-$TransID",FILE_APPEND);
	file_get_contents($url);
     //处理订单结束
            /**
            * 在这里对数据进行处理
            */
        }else{
            //支付失败
            echo "支付失败";
        }
    }else{
        //验证签名失败
    }