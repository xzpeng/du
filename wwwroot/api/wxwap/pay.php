<?php 
    require_once 'Config.php';
    require_once 'services/Services.php';
    /**
     * 
     * @author Jupiter
     *
     * 消费接口类:
     * 用于对支付信息进行重组和签名，并将请求发往现在支付
     * 
     */
            $req=array();
            $req["mhtOrderName"]=$_GET["mhtOrderName"];
            $req["mhtOrderAmt"]=$_GET["mhtOrderAmt"];
            $req["mhtOrderDetail"]=$_GET["mhtOrderName"];
            $req["funcode"]=Config::TRADE_FUNCODE;
            $req["appId"]=Config::$appId;//应用ID
            $req["mhtOrderNo"]=$_GET["mhtOrderName"];
            $req["mhtOrderType"]=Config::TRADE_TYPE;
            $req["mhtCurrencyType"]=Config::TRADE_CURRENCYTYPE;
            $req["mhtOrderStartTime"]=date("YmdHis");
            $req["notifyUrl"]=Config::$back_notify_url;
            $req["frontNotifyUrl"]=Config::$front_notify_url;
            $req["mhtCharset"]=Config::TRADE_CHARSET;
            $req["deviceType"]=Config::TRADE_DEVICE_TYPE;
			$req["payChannelType"]=Config::TRADE_PAYCHANNELTYPE;
            $req["mhtReserved"]="test";
            $req["mhtSignature"]=Services::buildSignature($req);
            $req["mhtSignType"]=Config::TRADE_SIGN_TYPE;
			
            $req_str=Services::trade($req);
            //header("Location:".Config::TRADE_URL."?".$req_str);
            $url="https://pay.ipaynow.cn/?".$req_str;
            $jin=file_get_contents($url);
            if (strstr($jin,"weixin")){
			preg_match_all("#tn\=(.*)#i",$jin,$regs);
			$wx=urldecode($regs[1][0]);
            header("location: $wx");
			exit;
			}else{
			echo 'err';	
			}