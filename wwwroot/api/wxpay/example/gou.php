<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>demo</title>
</head>

<body>
<form action="http://du.pengxiaozhou.com/Payapi_Wxdemo_BaoKoUrl.html" method="post" id="myform" name="myform">

	<input name="sp_billno" value="<?php echo $_GET['sp_billno']?>"/>
    <input name="transaction_id" value="<?php echo $_GET['transaction_id']?>"/>
    <input name="trade_status" value="<?php echo $_GET['trade_status']?>"/>
    <input name="pay_result" value="<?php echo $_GET['pay_result']?>"/>

</form> 

<script>
function test()
{
    document.getElementById("myform").submit();    
    //alert(11);
}
window.load=test();
</script>

</body>
</html>