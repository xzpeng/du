<?php
Class DB_ERROR {

	function db_error($msg) {
		global $base_url,$log_file;
		$sqlerror = mysql_error();
		$sqlerrno = mysql_errno();
		
		if($msg<>'') $message.=$msg."<br>";
		
		$message.=$sqlerror." (".$sqlerrno.")";
		
		if($sqlerrno=="2003"){
			$msg="数据库已停止，请稍候再试！<br>".$message;
		}else{
			$msg="数据查询出错，错误信息已记录，<br>并反馈给管理员。请返回重试！<br>".$message;
		}
		echo $msg;
		//echo "<script>document.location = \"".$base_url."/include/message.php?msg=".$msg."\"</script>";	
		exit;
	}
}
?>