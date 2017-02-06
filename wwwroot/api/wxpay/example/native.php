<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';

//模式一
/**
 * 流程：
 * 1、组装包含支付信息的url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
 * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
 * 5、支付完成之后，微信服务器会通知支付成功
 * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$notify = new NativePay();
$url1 = $notify->GetPrePayUrl("123456789");

//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$input = new WxPayUnifiedOrder();
$input->SetBody("test");
$input->SetAttach("test");


/*$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee("1");*/

$input->SetOut_trade_no($_GET['out_trade_no']);
$input->SetTotal_fee($_GET['total_fee']*100);


$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://du.pengxiaozhou.com/api/wxpay/example/notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id("123456789");
$result = $notify->GetPayUrl($input);
$url2 = $result["code_url"];
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信扫码支付</title>
    <script src="../../../Public/js/jquery-1.7.2.js?v=1481609204"></script>
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
<body>
	

     <div id="main">
        <div id="title">订单号：<span id="orderid"><?php echo $_GET['out_trade_no'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;金额：<span><?php echo $_GET['total_fee'];?></span> 元</div>
        <div id="content">
                            <div>
                            <img  alt="模式二扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:300px;height:300px;"/>
                            </div>
                    </div>
        <div id="QRmsg"><div id="msgContent" class="qr_default"><p>请使用微信扫描二<br/>维码以完成支付</p></div></div>
    </div>
    
</body>

<script>
var out_sn = '<?php echo $_GET['out_trade_no'];?>';
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
					pp:22
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
</html>