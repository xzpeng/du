<?PHP
function insert_sql($arr,$table){
		$sql="insert into $table (";
		$sql1="";
		$sql2="";
		if(is_array($arr)){
			foreach($arr as $key => $value){
				if(is_array($value)){
					$sql1.=empty($sql1)?$value[0]:",".$value[0];
					$sql2.=empty($sql2)?"'$".$value[1]."'":",'$".$value[1]."'";
				}else{
					$sql1.=empty($sql1)?$value:",".$value;
					$sql2.=empty($sql2)?"'$".$value."'":",'$".$value."'";
				}
			}
		$sql=$sql.$sql1.') values ( '.$sql2.' )';
		return $sql;	
		}
		
	}
	
function get_cname($table,$needid=''){
global $db;
	$sql="SHOW COLUMNS FROM $table";

	$query=$db->query($sql);
	$colums = '';
	$i=0;
	while($a=$db->fetch_array($query)){
		$colums_=$a['Field'];
		if($needid==1){
			$colums.=empty($colums)?$colums_:",".$colums_;
		}else{
			if($i>0){
				$colums.=empty($colums)?"$colums_":",".$colums_."";
			}
		}
		$i++;	
	}
	$arr=explode(",",$colums);
	return  insert_sql($arr,$table);
	
}

function gc($table,$cd,$subject=''){
	global $db;
	if(empty($subject)){
		$a=$db->get_one("select count(*) as c from $table $cd ");
	}else{
		$a=$db->get_one("select $subject as c from $table $cd ");
	}	
	return $a['c'];
}

function gcs($table,$cd){
	global $db;
	return $db->get_one("select * from $table $cd limit 0,1");
}

function logs($msg){
	global $db;
	if($_SESSION['userid']){
		return $db->query("insert into editlog (subject,addtime,ifsee,userid) values ('$msg','".date("Y-m-d H:i:s")."',0,'$_SESSION[userid]')");
	}	
}
function wl($subject){
	global $db;
	$optime = date("Y-m-d H:i:s");
	return $db->query("insert into order_log(subject,oper,optime) values ('$subject','$_SESSION[Admin_Name]','$optime')");	
}
?>