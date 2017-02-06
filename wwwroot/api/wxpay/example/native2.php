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

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf8"/>
<meta id="viewport" name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1; user-scalable=no;" />


<style type="text/css">
/* 重置 [[*/
body,p,ul,li,h1,h2,form,input{margin:0;padding:0;}
h1,h2{font-size:100%;}
ul{list-style:none;}
body{-webkit-user-select:none;-webkit-text-size-adjust:none;font-family:Helvetica;background:#ECECEC;}
html,body{height:100%;}
a,button,input,img{-webkit-touch-callout:none;outline:none;}
a{text-decoration:none;}
/* 重置 ]]*/
/* 功能 [[*/
.hide{display:none!important;}
.cf:after{content:".";display:block;height:0;clear:both;visibility:hidden;}
/* 功能 ]]*/
/* 按钮 [[*/
a[class*="btn"]{display:block;height:42px;line-height:42px;color:#FFFFFF;text-align:center;border-radius:5px;}
.btn-blue{background:#3D87C3;border:1px solid #1C5E93;}
.btn-green{background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0, #43C750), color-stop(1, #31AB40));border:1px solid #2E993C;box-shadow:0 1px 0 0 #69D273 inset;}
/* 按钮 [[*/
/* 充值页 [[*/
.charge{font-family:Helvetica;padding-bottom:10px;-webkit-user-select:none;}
.charge h1{height:44px;line-height:44px;color:#FFFFFF;background:#3D87C3;text-align:center;font-size:20px;-webkit-box-sizing:border-box;box-sizing:border-box;}
.charge h2{font-size:14px;color:#777777;margin:5px 0;text-align:center;}
.charge .content{padding:10px 12px;}
.charge .select li{position:relative;display:block;float:left;width:100%;margin-right:2%;height:150px;line-height:150px;text-align:center;border:1px solid #BBBBBB;color:#666666;font-size:16px;margin-bottom:5px;border-radius:3px;background-color:#FFFFFF;-webkit-box-sizing:border-box;box-sizing:border-box;overflow:hidden;}
.charge .price{border-bottom:1px dashed #C9C9C9;padding:10px 10px 15px;margin-bottom:20px;color:#666666;font-size:12px;}
.charge .price strong{font-weight:normal;color:#EE6209;font-size:26px;font-family:Helvetica;}
.charge .showaddr{border:1px dashed #C9C9C9;padding:10px 10px 15px;margin-bottom:20px;color:#666666;font-size:12px;text-align:center;}
.charge .showaddr strong{font-weight:normal;color:#9900FF;font-size:26px;font-family:Helvetica;}
.charge .copy-right{margin:5px 0; font-size:12px;color:#848484;text-align:center;}
/* 充值页 ]]*/
</style>
</head>
<body>
	
    
	<article class="charge">
		<h1>充值</h1>
		<section class="content">
				<h2>&nbsp;</h2>		
		  <ul class="select cf">
					<li><img src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:150px;height:150px"></li>
				</ul>
                <p class="copy-right">亲，此商品不提供退款和发货服务哦</p>
				
				<p class="copy-right"></p>
				<div class="price">微信价：<strong>￥<?php echo $_GET['total_fee'];?>元</strong></div>
				<div class="operation" ><a class="btn-green" >扫码付款</a></div>
				<p class="copy-right">版权所有</p>
		</section>
	</article>
 
	 
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
							  alert('支付成功');
							  location.href='http://du.pengxiaozhou.com/User_Index_wyjyjl.html';
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