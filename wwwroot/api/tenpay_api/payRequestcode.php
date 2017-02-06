<?php
//---------------------------------------------------------
//财付通即时到帐支付请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once ("classes/RequestHandler.class.php");
/* 商户号，上线时务必将测试商户号替换为正式商户号 */
$partner = $_REQUEST['partner'];

/* 密钥 */
$key = $_REQUEST['key'];



//4位随机数
$randNum = rand(1000, 9999);

//订单号，此处用时间加随机数生成，商户根据自己情况调整，只要保持全局唯一就行
//$out_trade_no = date("YmdHis") . $randNum;

$out_trade_no = $_GET['out_trade_no'];

/* 创建支付请求对象 */
$reqHandler = new RequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setGateUrl("https://myun.tenpay.com/cgi-bin/wappayv2.0/wappay_init.cgi");

//----------------------------------------
//设置支付参数 
//----------------------------------------
$reqHandler->setParameter("ver", "2.0"); 
$reqHandler->setParameter("charset", "2");   	  //字符集
$reqHandler->setParameter("bank_type","0");  	  //银行类型，默认为财付通
$reqHandler->setParameter("desc", "QQ_pay");      //商品名称
$reqHandler->setParameter("pay_channel","1");  	          //银行类型，默认为财付通
$reqHandler->setParameter("bargainor_id",$partner);  //商户号

$reqHandler->setParameter("sp_billno",$out_trade_no);  //商户订单号

//echo '应付金额.'. $_GET['total_fee']*100;
$reqHandler->setParameter("total_fee", $_GET['total_fee']*100);  //总金额

//$reqHandler->setParameter("total_fee",0.01*100);  //总金额
$reqHandler->setParameter("fee_type", 1);               //币种
$reqHandler->setParameter("notify_url", "http://du.pengxiaozhou.com/Payapi_QQbao_BaoKoUrl.html");

$reqHandler->setParameter("return_url", "http://du.pengxiaozhou.com/Payapi_QQbao_BaoKoUrlbibi.html");//支付成功后返回
//业务可选参数
//$reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
//$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
//$reqHandler->setParameter("time_expire", "");             //订单失效时间
//请求的URL
$reqUrl = $reqHandler->getRequestURL();
//获取debug信息,建议把请求和debug信息写入日志，方便定位问题
/*
$debugInfo = $reqHandler->getDebugInfo();
echo "<br/>" . $reqUrl . "<br/>";
echo "<br/>" . $debugInfo . "<br/>";
*/

//curl post
$url = $reqHandler->getGateUrl();
//echo $reqUrl;


$token_id_data = file_get_contents( $reqUrl);
$xml = simplexml_load_string($token_id_data);
$nihao = json_decode(json_encode($xml),TRUE);
$token_id = $nihao['token_id'];

if($token_id=='')
{ 
       echo 'fail';
 	   exit;
}


$ma  = "https://myun.tenpay.com/mqq/pay/qrcode.html?_wv=1027&_bid=2183&t=".$token_id;


 /*echo "<script>location.href='".$ma."'</script>"; 
	*/
if (isMobile())  
{
	 
   echo "<script>location.href='".$ma."'</script>"; 
	   exit;	
}

//error_reporting(0);
require_once '../../ThinkPHP/Extend/Vendor/phpqrcode/phpqrcode.php';

$value =$ma;
$errorCorrectionLevel = 'L';//容错级别   
$matrixPointSize      = 5;//生成图片大小  
 
 
 $QR = 'qrcode.png';//已经生成的原始二维码图   

if(file_exists($QR)){
    unlink($QR);
   
}
 
//生成二维码图片   
$ming ="koko/".time(). '_qrcode.png';
QRcode::png($value,$ming, $errorCorrectionLevel, $matrixPointSize, 2);   
//输出图片   
//@imagepng($QR, 'helloweixin.png');   
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>QQ钱包扫码支付</title>
</head>
<style>
*{margin:0;padding:0;}
body{background:url(image/bg.jpg) repeat;}
#main{background-color:#fff;padding:1px;width:500px;margin:100px auto;text-align:center;border-radius:3px;box-shadow:5px 5px 30px #333;}
#content{padding:30px;}
#title{color:#333;font-size:14px;background-color:#e8e8e8;border-bottom:1px solid #ccc;line-height:60px;}
#title span{color:#fb180a;font-size:16px;font-weight:bold;}
#QRmsg{color:#149696;background-color:#e8e8e8;border-top:1px solid #ccc;line-height:28px;padding:20px 0;font-size:16px;}
.qr_default{background:url(image/icon_pay.png) no-repeat 150px -63px;}
.qr_succ, .pay_succ{background:url(image/icon_pay.png) no-repeat 150px -3px;}
.pay_error{background:url(image/icon_pay.png) no-repeat 150px -120px;}
#msgContent p{text-align:left;padding-left:220px;}
#msgContent p a{color:#149696;font-weight:bold;}
</style>

<script src="../../Public/js/jquery-1.7.2.js?v=1481609204"></script>
<body>





 <div id="main">
        <div id="title">订单号：<span id="orderid"><?php echo $_GET['out_trade_no'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;金额：<span><?php echo $_GET['total_fee'];?></span> 元</div>
        <div id="content">
                            <div>
                            <img  alt="模式二扫码支付" src="<?php echo $ming;?>" style="width:300px;height:300px;"/>
                            </div>
                    </div>
        <div id="QRmsg"><div id="msgContent" class="qr_default"><p>请使用手机QQ扫描<br/>二维码以完成支付</p></div></div>
 </div>
    
</body>


<?php
$params = $reqHandler->getAllParameters();
foreach($params as $k => $v) {
	echo "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
}


function getXmlEncode_show($xml) {
		$ret = preg_match ("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
		if($ret) {
			return strtoupper ( $arr[1] );
		} else {
			return "";
		}
	}
	
	
function isMobile(){
	
$theusagt = $_SERVER["HTTP_USER_AGENT"];
//echo $theusagt.'<br>';
$is_mobile = false;
if(stripos($theusagt , "iPhone") !== false || stripos($theusagt , "iPod") !== false){
    //$thetargetsite = $siteurl_mobile;
    $is_mobile = true;
}
else if(stripos($theusagt , "Mac OS") !== false){
    //$thetargetsite = $siteurl_pc;
    $is_mobile = false;
}
else if(stripos($theusagt , "Mobile") !== false){
    //$thetargetsite = $siteurl_mobile;
    $is_mobile = true;
}
else if(stripos($theusagt , "Android") !== false){
    //$thetargetsite = $siteurl_pc;
    $is_mobile = false;
}
else if(stripos($theusagt , "Windows Phone") !== false){
    //$thetargetsite = $siteurl_mobile;
    $is_mobile = true;
}
else {
    //$thetargetsite = $siteurl_pc;
    $is_mobile = false;
}

return $is_mobile;
	
}  	
?>
<script>
var out_sn = '<?php echo $out_trade_no;?>';
 var f= setInterval("myInterval()",5000);//1000为1秒钟
       var i =5;
       function myInterval()
       {    
             //ajax php
			 
			 $.ajax({
			    url:'http://du.pengxiaozhou.com/Payapi_QQbao_BaoKoYiBuUrl.html',//百度接口api 鹰眼
			    type:'POST', //GET
			    async:true,    //或false,是否异步
			    data:{
			        out_trade_no:out_sn,
			     },
			    timeout:10000,    //超时时间
			    dataType:'html',    //返回的数据格式：json/xml/html/script/jsonp/text
			    beforeSend:function(xhr){
			        console.log(xhr)
			        console.log('发送前')
			    },
			    success:function(res,textStatus,jqXHR){
						  
						  if(res==1)
						  {
							  clearInterval(f); //清楚定时器
							  location.href='http://du.pengxiaozhou.com/payapi/success.php?out_trade_no=<?php echo $_GET['out_trade_no'];?>&total_fee=<?php echo $_GET['total_fee'];?>';
							  return false;
						  }else
						  {
							   console.log('输出'+res)
						  }
			    },
			    error:function(xhr,textStatus){
			        console.log('错误')
			        console.log(xhr)
			        console.log(textStatus)
			    },
			    complete:function(){
			        console.log('结束')
			    }
		});
			 
			  
       }

</script>

</body>
</html>
