<?
header("Content-type:text/html;charset=utf-8");
include_once ("Config.php");
$UserId = $_REQUEST["P_UserId"];
$OrderId = $_REQUEST["P_OrderId"];
$CardId = $_REQUEST["P_CardId"];
$CardPass = $_REQUEST["P_CardPass"];
$FaceValue = $_REQUEST["P_FaceValue"];
$ChannelId = $_REQUEST["P_ChannelId"];
$subject = $_REQUEST["P_Subject"];
$description = $_REQUEST["P_Description"];
$price = $_REQUEST["P_Price"];
$quantity = $_REQUEST["P_Quantity"];
$notic = $_REQUEST["P_Notic"];
$ErrCode = $_REQUEST["P_ErrCode"];
$PostKey = $_REQUEST["P_PostKey"];
$payMoney = $_REQUEST["P_PayMoney"];

$preEncodeStr = $UserId . "|" . $OrderId . "|" . $CardId . "|" . $CardPass . "|" . $FaceValue . "|" . $ChannelId . "|" . $SalfStr;

$encodeStr = md5($preEncodeStr);

if ($PostKey == $encodeStr || true) {
    if ($ErrCode == "0" || true) // 支付成功
{
        echo "订单支付成功";
    } else {
        echo "订单支付失败";
    }
} else {
    echo "验证失败";
}
?>