<?php
error_reporting(0); 
$pid=$_COOKIE[pid];
$uid=$_COOKIE[uid];
if ($pid==null){
$pid="admin";
}
if ($uid==null){
$uid="admin";
}

?>

<!DOCTYPE html>
<html>
<head>
<title>支付接口测试</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<br/>
<br/>
<br/>
<br/>
<div style="text-align:center"><span style="font-size:24px">&nbsp;支付接口测试</span>
<br/><br/>
&nbsp;*本页面支持电脑和手机访问*</div>
<br/>
<br/>
<center>
<form name="p" action="Pay.php" method="post" >
<!--  <input type="hidden" name="faceValue" value="0.01" >-->
<input type="hidden" name="subject" value="VIP服务">
<input type="hidden" name="description" value="测试">
<input type="hidden" name="cardId" value="<?php echo $pid?>">
<input type="hidden" name="notic" value="<?php echo $uid?>">
<input type="hidden" name="paytype" value="wxwap" id="wxwap">
<input type="hidden" name="pid" value="WXWAP" id="WXWAP">

支付金额:
<input type="text" name="faceValue" value="0.01" size="5">
<input type="submit"  value="点击支付">
</form>
</center>
</body>
</html>
