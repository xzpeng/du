<?php
//---------------------------------------------------------
//财付通即时到帐支付后台回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------

header('Content-Type:text/html;charset=gbk');
$str = json_encode($_REQUEST);

//$url = 'http://qb.com/Payapi_QQbao_BaoKoYiBuUrl.html?out_trade_no='.$_REQUEST['out_trade_no'];

$url = 'http://du.pengxiaozhou.com/Payapi_QQbao_BaoKoYiBuUrl.html?out_trade_no='.$_REQUEST['out_trade_no'];


file_get_contents($url);

?>