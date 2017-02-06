<?php
//---------------------------------------------------------
//财付通即时到帐支付请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once ("classes/RequestHandler.class.php");

/* 商户号，上线时务必将测试商户号替换为正式商户号 */
$partner = "1419111801";

//$partner = $_GET['partner'];


/* 密钥 */
$key = "63251254263254215236545875421523";


//$key = $_GET['key'];





//订单号，此处用时间加随机数生成，商户根据自己情况调整，只要保持全局唯一就行
$out_trade_no = $_GET['out_trade_no'];



/* 创建支付请求对象 */
$reqHandler = new RequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);
//$reqHandler->setGateUrl("http://myun.tenpay.com/cgi-bin/wappayv2.0/wappay_init.cgi");

//----------------------------------------
//设置支付参数 
//----------------------------------------

//系统可选参数

$reqHandler->setParameter("ver", "2.0"); 
$reqHandler->setParameter("charset", "2");   	  //字符集
$reqHandler->setParameter("bank_type","0");  	  //银行类型，默认为财付通
$reqHandler->setParameter("desc", "在线支付");  
$reqHandler->setParameter("pay_channel","1");  	          //银行类型，默认为财付通
$reqHandler->setParameter("bargainor_id","1419111801");  //商户号
$reqHandler->setParameter("sp_billno",$out_trade_no);  //商户订单号
$reqHandler->setParameter("total_fee", $_GET['total_fee']*100);  //总金额
$reqHandler->setParameter("fee_type", 1);               //币种
$reqHandler->setParameter("notify_url", "http://du.pengxiaozhou.com/tenpay_api/payNotifyUrl.php");
//业务可选参数
//$reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
//$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
//$reqHandler->setParameter("time_expire", "");             //订单失效时间


$strtoting = "ver=2.0&charset=2&bank_type=0&desc=在线支付&pay_channel=1&bargainor_id=1419111801&sp_billno=".$out_trade_no.
               "&total_fee=".($_GET['total_fee']*100)."fee_type=1&notify_url=http://du.pengxiaozhou.com/tenpay_api/payNotifyUrl.php";
              //商品标记
//请求的URL

$sign=strtoupper(md5($strtoting."&key=63251254263254215236545875421523"));
//$reqUrl = $reqHandler->getRequestURL();

//获取debug信息,建议把请求和debug信息写入日志，方便定位问题
/*
$debugInfo = $reqHandler->getDebugInfo();
echo "<br/>" . $reqUrl . "<br/>";
echo "<br/>" . $debugInfo . "<br/>";
*/

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>QQ钱包支付</title>
</head>
<body>
<br/><a >支付跳转中...</a>
<form action="http://myun.tenpay.com/cgi-bin/wappayv2.0/wappay_init.cgi" method="post"  name="Form1" id="Form1">
<?php
$params = $reqHandler->getAllParameters();
foreach($params as $k => $v) {
	echo "<input type=\"text\" name=\"{$k}\" value=\"{$v}\" />\r\n";
}
?>

<input name="sign" value="<?php echo $sign;?>"  type="hidden"/>


<input type="submit" value="确定"  type="text"/>

 </form>
<script type="text/javascript">
function validate(){
  //document.getElementById('Form1').submit();
  
 
}
window.load=validate();
</script>
</body>
</html>
